<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Calendar;
use App\Models\UnavailableDay;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DateTime;
use Carbon\Carbon;
class CalendarController extends Controller
{
    public function index()
    {
        $calendars = Calendar::where('user_id', Auth::id())->get();
        return view('calendar.index', compact('calendars'));
    }
    public function availability($user_id)
    {
        $availabilities = Calendar::where('user_id', $user_id)
                                    ->where('type', 'availability')
                                    ->get()
                                    ->keyBy('day_of_week'); 
        return view('calendar.create-availability', compact('availabilities', 'user_id'));
    }
    public function storeAvailability(Request $request, $user_id)
    {
        $validator = Validator::make($request->all(), [
            'days' => 'required|array',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        foreach ($request->days as $day => $times) {
            if (!empty($times['start_time']) && !empty($times['end_time'])) {
                $existingEntry = Calendar::where('user_id', $user_id)
                                        ->where('type', 'availability')
                                        ->where('day_of_week', $day)
                                        ->first();
                if ($existingEntry) {
                    $existingEntry->update([
                        'start_time' => $times['start_time'],
                        'end_time' => $times['end_time'],
                    ]);
                } else {
                    Calendar::create([
                        'user_id' => $user_id, 
                        'type' => 'availability',
                        'day_of_week' => $day,
                        'start_time' => $times['start_time'],
                        'end_time' => $times['end_time'],
                    ]);
                }
            }
        }
        return redirect()->route('calendar.index')->with('success', 'Availability added successfully.');
    }
    public function editUnavailability($user_id)
    {
        $unavailability = Calendar::where('user_id', $user_id)
                                  ->where('type', 'unavailability')
                                  ->get()
                                  ->groupBy('day_of_week');
    
        return view('calendar.create-unavailability', compact('user_id', 'unavailability'));
    }
    public function updateUnavailability(Request $request, $user_id)
    {
        $validator = Validator::make($request->all(), [
            'days' => 'required|array',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        foreach ($request->days as $day => $times) {
            if (!empty($times['start_time']) && !empty($times['end_time'])) {
                $existingEntry = Calendar::where('user_id', $user_id)
                                        ->where('type', 'unavailability')
                                        ->where('day_of_week', $day)
                                        ->first();
                if ($existingEntry) {
                    $existingEntry->update([
                        'start_time' => $times['start_time'],
                        'end_time' => $times['end_time'],
                    ]);
                } else {
                    Calendar::create([
                        'user_id' => $user_id,
                        'type' => 'unavailability',
                        'day_of_week' => $day,
                        'start_time' => $times['start_time'],
                        'end_time' => $times['end_time'],
                    ]);
                }
            }
        }
        return redirect()->route('calendar.index')->with('success', 'Unavailability updated successfully.');
    }
    public function createUnavailableDays()
    {
        return view('calendar.create-unavailable-days');
    }
    public function storeUnavailableDays(Request $request)
    {
        $request->validate([
            'dates.*' => 'required|date',
            'start_times.*' => 'nullable|date_format:H:i',
            'end_times.*' => 'nullable|date_format:H:i|after:start_times.*',
            'types.*' => 'required|in:0,1',
        ]);
        $dates = $request->input('dates');
        $start_times = $request->input('start_times');
        $end_times = $request->input('end_times');
        $types = $request->input('types');
        for ($i = 0; $i < count($dates); $i++) {
            $unavailableDay = UnavailableDay::where('user_id', Auth::id())
                                            ->where('date', $dates[$i])
                                            ->first();
            if ($unavailableDay) {
                $unavailableDay->update([
                    'type' => $types[$i], 
                    'start_time' => $start_times[$i] ?? null,
                    'end_time' => $end_times[$i] ?? null,
                ]);
            } else {
                UnavailableDay::create([
                    'user_id' => Auth::id(),
                    'type' => $types[$i], 
                    'date' => $dates[$i],
                    'start_time' => $start_times[$i] ?? null,
                    'end_time' => $end_times[$i] ?? null,
                ]);
            }
        }
        return redirect()->route('calendar.index')->with('success', 'Unavailable days added or updated successfully!');
    }
    public function UnavailableDays(Request $request)
    {
        $unavailableDays = UnavailableDay::where('user_id', Auth::id())->get();
        
        return view('calendar.create-unavailable-days', compact('unavailableDays'));
    }
    public function showCalendar(Request $request)
    {
        if ($request->ajax()) {
            $userId = Auth::id();
            $start = $request->query('start');
            $end = $request->query('end');
            $dayOfWeek = date('l', strtotime($start));
            $availabilitySlots = $this->getSlotRecords(60, 'availability', $dayOfWeek);
            $unavailabilitySlots = $this->getSlotRecords(60, 'unavailability', $dayOfWeek);
            $unavailableDaysType0 = UnavailableDay::where('type', 0)->get();
            $unavailableDaysType1 = UnavailableDay::where('type', 1)->get();
            $filteredSlots = $this->removeCommonSlots($availabilitySlots, $unavailabilitySlots);
            return response()->json([
                'availability' => $availabilitySlots,
                'unavailability' => $unavailabilitySlots,
                'filtered_slots' => $filteredSlots,
                'unavailable_days_type_0' => $unavailableDaysType0,
                'unavailable_days_type_1' => $unavailableDaysType1,
            ]);
        }
        return view('calendar.show-calendar');
    }
    public function getSlotRecords($interval, $type, $dayofweek)
    {
        $userId = Auth::id();
        $availability = Calendar::where('user_id', $userId)
                                ->where('type', $type)
                                ->where('day_of_week', $dayofweek)
                                ->get();
        $slots = [];
        foreach ($availability as $entry) {
            $start = new DateTime($entry->start_time);
            $end = new DateTime($entry->end_time);
            $startTime = $start->format('H:i');
            $endTime = $end->format('H:i');
            $i = 0;
            while (strtotime($startTime) < strtotime($endTime)) {
                $startSlot = $startTime;
                $endSlot = date('H:i', strtotime('+' . $interval . ' minutes', strtotime($startTime)));
                $startTime = date('H:i', strtotime('+' . $interval . ' minutes', strtotime($startTime)));
                if (strtotime($startTime) <= strtotime($endTime)) {
                    $slots[$entry->day_of_week][$i]['slot_start_time'] = $startSlot;
                    $slots[$entry->day_of_week][$i]['slot_end_time'] = $endSlot;
                    $i++;
                }
            }
        }
        return $slots;
    }
    /*public function getDayRecords(Request $request)
    {
        $date = $request->input('date');
        $dayOfWeek = date('l', strtotime($date));

        $availabilitySlots = $this->getSlotRecords(60, 'availability', $dayOfWeek);
        $unavailabilitySlots = $this->getSlotRecords(60, 'unavailability', $dayOfWeek);

        $unavailableDay1 = UnavailableDay::where('date', $date)
                                        ->where('type', 1)
                                        ->exists();
        $unavailableDaysType0 = UnavailableDay::where('type', 0)
                                              ->where('date', $date)
                                              ->get();

        $unavailableSlotsByDay = [];
        foreach ($unavailableDaysType0 as $day) {
            $slots = $this->getOneHourSlots($day->start_time, $day->end_time);
            $dayOfWeek = date('l', strtotime($day->date)); 
            foreach ($slots as $slot) {
                $unavailableSlotsByDay[$dayOfWeek][] = [
                    'slot_start_time' => $slot['start_time'],
                    'slot_end_time' => $slot['end_time']
                ];
            }
        }

        $filteredSlots = $this->removeCommonSlots($availabilitySlots, $unavailabilitySlots);
        $partial = $this->removeCommonSlots($filteredSlots, $unavailableSlotsByDay);

        $records = Calendar::where('user_id', Auth::id())
            ->where('day_of_week', $dayOfWeek)
            ->get();

        $daySlots = array_map(function($slot) {
            return [
                'start_time' => $slot['slot_start_time'] ?? 'N/A',
                'end_time' => $slot['slot_end_time'] ?? 'N/A',
                'type' => $slot['type'] ?? 'Slots'
            ];
        }, $partial[$dayOfWeek] ?? []);

        return view('calendar.day-records', compact('records', 'daySlots', 'dayOfWeek', 'unavailableDay1', 'unavailableDaysType0', 'unavailableSlotsByDay', 'date'))
               ->with('selectedDate', $date); 
    }*/
   public function getDayRecords(Request $request)
{
    $date = $request->input('date');
    $dayOfWeek = date('l', strtotime($date));
    $userId = Auth::id();
    $alreadyBookedSlots = Booking::where('user_id', $userId)
        ->where('date', $date)
        ->pluck('end_time', 'start_time')
        ->toArray();

    $formattedBookedSlots = [];
    foreach ($alreadyBookedSlots as $startTime => $endTime) {
        $formattedStartTime = date('H:i', strtotime($startTime));
        $formattedEndTime = date('H:i', strtotime($endTime));
        $formattedBookedSlots[$formattedStartTime] = $formattedEndTime;
    }

    $availabilitySlots = $this->getSlotRecords(60, 'availability', $dayOfWeek);
    $unavailabilitySlots = $this->getSlotRecords(60, 'unavailability', $dayOfWeek);

    // Combine unavailable slots and remove them from availability slots
    $filteredSlots = $this->removeCommonSlots($availabilitySlots, $unavailabilitySlots);

    // Remove already booked slots from filtered slots
    $availableSlots = array_filter($filteredSlots[$dayOfWeek] ?? [], function($slot) use ($formattedBookedSlots) {
        return !array_key_exists($slot['slot_start_time'], $formattedBookedSlots);
    });

    $records = Calendar::where('user_id', Auth::id())
        ->where('day_of_week', $dayOfWeek)
        ->get();

    $unavailableDay1 = UnavailableDay::where('date', $date)
                                    ->where('type', 1)
                                    ->exists();
    $unavailableDaysType0 = UnavailableDay::where('type', 0)
                                          ->where('date', $date)
                                          ->get();

    $unavailableSlotsByDay = [];
    foreach ($unavailableDaysType0 as $day) {
        $slots = $this->getOneHourSlots($day->start_time, $day->end_time);
        $dayOfWeek = date('l', strtotime($day->date)); 
        foreach ($slots as $slot) {
            $unavailableSlotsByDay[$dayOfWeek][] = [
                'slot_start_time' => $slot['start_time'],
                'slot_end_time' => $slot['end_time']
            ];
        }
    }

    $daySlots = array_map(function($slot) use ($formattedBookedSlots) {
        $isBooked = array_key_exists($slot['slot_start_time'], $formattedBookedSlots);
        return [
            'start_time' => $slot['slot_start_time'] ?? 'N/A',
            'end_time' => $slot['slot_end_time'] ?? 'N/A',
            'type' => $slot['type'] ?? 'Slots',
            'is_booked' => $isBooked,
        ];
    }, $availableSlots);

    return view('calendar.day-records', compact('records', 'daySlots', 'dayOfWeek', 'unavailableDay1', 'unavailableDaysType0', 'unavailableSlotsByDay', 'date'))
        ->with('selectedDate', $date); 
}




























    private function removeCommonSlots($availability, $unavailability)
    {
        $filteredSlots = [];
        foreach ($availability as $day => $slots) {
            if (!is_string($day)) {
                continue;
            }

            if (isset($unavailability[$day])) {
                foreach ($slots as $key => $slot) {
                    $isCommon = false;
                    foreach ($unavailability[$day] as $unavailableSlot) {
                        if (
                            $slot['slot_start_time'] == $unavailableSlot['slot_start_time'] &&
                            $slot['slot_end_time'] == $unavailableSlot['slot_end_time']
                        ) {
                            $isCommon = true;
                            break; 
                        }
                    }
                    if (!$isCommon) {
                        $filteredSlots[$day][] = $slot;
                    }
                }
            } else {
                $filteredSlots[$day] = $slots;
            }
        }

        return $filteredSlots;
    }
    private function getOneHourSlots($startTime, $endTime)
    {
        $slots = [];
        $current = new DateTime($startTime);
        $end = new DateTime($endTime);

        while ($current < $end) {
            $next = clone $current;
            $next->modify('+1 hour');
            $slots[] = [
                'start_time' => $current->format('H:i:s'),
                'end_time' => $next->format('H:i:s')
            ];
            $current = $next;
        }

        return $slots;
    }
    private function combineSlots($filteredSlots1, $unavailableSlotsType0)
{
    // dd('test');
    $combinedSlots = $filteredSlots1;

    // Remove common slots
    foreach ($unavailableSlotsType0 as $date => $slots) {
        if (isset($combinedSlots[$date])) {
            foreach ($slots as $slot) {
                $startTime = $slot['start_time'];
                $endTime = $slot['end_time'];

                // Filter out the slots in filteredSlots1 that match the unavailable slots
                $combinedSlots[$date] = array_filter($combinedSlots[$date], function($filteredSlot) use ($startTime, $endTime) {
                    return !($filteredSlot['start_time'] === $startTime && $filteredSlot['end_time'] === $endTime);
                });
            }
        }
    }

    return $combinedSlots;
}





  






}
