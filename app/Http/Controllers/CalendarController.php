<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Calendar;
use App\Models\UnavailableDay;
use App\Models\Booking;
use App\Models\OrderMeta;
use App\Models\Teacher;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DateTime;
use Carbon\Carbon;
class CalendarController extends Controller
{
    public function index($id)
    {
        $teacher = Teacher::find($id); 
        $calendars = Calendar::where('user_id', Auth::id())->get(); 
        return view('calendar.index', compact('teacher', 'calendars')); 
    }
    public function availability($teacher_id)
    {
        $availabilities = Calendar::where('teacher_id', $teacher_id)
                                    ->where('type', 'availability')
                                    ->get()
                                    ->keyBy('day_of_week');
        return view('calendar.create-availability', compact('availabilities', 'teacher_id'));
    }
    public function storeAvailability(Request $request, $teacher_id)
    {
        // dd($request->all()); 
        $user_id = Auth::id(); 
        // dd($user_id);
        foreach ($request->days as $day => $times) {
            if (!empty($times['start_time']) && !empty($times['end_time'])) {
                $existingEntry = Calendar::where('teacher_id', $teacher_id)
                                        ->where('user_id', $user_id) 
                                        ->where('type', 'availability')
                                        ->where('day_of_week', $day)
                                        ->first();
                                        // dd($existingEntry);

                if ($existingEntry) {
                    $existingEntry->update([
                        'start_time' => $times['start_time'],
                        'end_time' => $times['end_time'],
                    ]);
                } else {
                    Calendar::create([
                        'teacher_id' => $teacher_id, 
                        'user_id' => $user_id, 
                        'type' => 'availability',
                        'day_of_week' => $day,
                        'start_time' => $times['start_time'],
                        'end_time' => $times['end_time'],
                    ]);
                }
            }
        }
        return redirect()->route('teachers.index')->with('success', 'Availability added successfully.');
    }
    public function editUnavailability($teacher_id)
    {
        $unavailability = Calendar::where('teacher_id', $teacher_id)
                                  ->where('type', 'unavailability')
                                  ->get()
                                  ->groupBy('day_of_week');
        return view('calendar.create-unavailability', compact('teacher_id', 'unavailability'));
    }
    public function updateUnavailability(Request $request, $teacher_id)
    {
        $user_id = Auth::id(); 
        foreach ($request->days as $day => $times) {
            if (!empty($times['start_time']) && !empty($times['end_time'])) {
                $existingEntry = Calendar::where('teacher_id', $teacher_id)                    ->where('user_id', $user_id) 
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
                        'teacher_id' => $teacher_id, 
                         'user_id' => $user_id, 
                        'type' => 'unavailability',
                        'day_of_week' => $day,
                        'start_time' => $times['start_time'],
                        'end_time' => $times['end_time'],
                    ]);
                }
            }
        }
        return redirect()->route('teachers.index')->with('success', 'Unavailability updated successfully.');
    }
    public function createUnavailableDays($teacher_id)
    {
        $teacher = Teacher::find($teacher_id);
        return view('calendar.create-unavailable-days', compact('teacher', 'teacher_id'));
    }
    public function storeUnavailableDays(Request $request, $teacher_id)
    {
        $request->validate([
            'dates.*' => 'required|date',
            'start_times.*' => 'nullable|date_format:H:i',
            'end_times.*' => 'nullable|date_format:H:i|after:start_times.*',
            'types.*' => 'required|in:0,1',
        ]);
        $user_id = Auth::id();
        $dates = $request->input('dates');
        $start_times = $request->input('start_times');
        $end_times = $request->input('end_times');
        $types = $request->input('types');
        for ($i = 0; $i < count($dates); $i++) {
            $unavailableDay = UnavailableDay::where('teacher_id', $teacher_id)
                ->where('date', $dates[$i])
                ->where('user_id', $user_id)
                ->first();

            if ($unavailableDay) {
                $unavailableDay->update([
                    'type' => $types[$i],
                    'start_time' => $start_times[$i] ?? null,
                    'end_time' => $end_times[$i] ?? null,
                ]);
            } else {
                UnavailableDay::create([
                    'teacher_id' => $teacher_id,
                    'user_id' => $user_id, 
                    'type' => $types[$i],
                    'date' => $dates[$i],
                    'start_time' => $start_times[$i] ?? null,
                    'end_time' => $end_times[$i] ?? null,
                ]);
            }
        }
        return redirect()->route('calendar.index', ['id' => $teacher_id])
                         ->with('success', 'Unavailable days added or updated successfully!');
    }
    public function listUnavailableDays($teacher_id)
    {
        $unavailableDays = UnavailableDay::where('teacher_id', $teacher_id)
                                         ->orderBy('date', 'desc')
                                         ->get();
        $teacher = Teacher::find($teacher_id);
        return view('calendar.create-unavailable-days', compact('unavailableDays', 'teacher'));
    }
    public function showCalendar(Request $request, $teacher_id)
    {
        if ($request->ajax()) {
            $userId = Auth::id();
            if (is_null($userId) || $userId < 0) {
                $teacher = Teacher::find($teacher_id);
                if ($teacher) {
                    $userId = $teacher->user_id;
                }
            }
            $start = $request->query('start');
            $end = $request->query('end');
            $dayOfWeek = date('l', strtotime($start));
            $availabilitySlots = $this->getSlotRecords(60, 'availability', $dayOfWeek, $teacher_id);
            $unavailabilitySlots = $this->getSlotRecords(60, 'unavailability', $dayOfWeek, $teacher_id);
            $unavailableDaysType0 = UnavailableDay::where('type', 0)->where('teacher_id', $teacher_id)->get();
            $unavailableDaysType1 = UnavailableDay::where('type', 1)->where('teacher_id', $teacher_id)->get();
            $filteredSlots = $this->removeCommonSlots($availabilitySlots, $unavailabilitySlots);
            return response()->json([
                'availability' => $availabilitySlots,
                'unavailability' => $unavailabilitySlots,
                'filtered_slots' => $filteredSlots,
                'unavailable_days_type_0' => $unavailableDaysType0,
                'unavailable_days_type_1' => $unavailableDaysType1,
            ]);
        }
        return view('calendar.show-calendar', compact('teacher_id'));
    }

    public function getSlotRecords($interval, $type, $dayofweek,$teacherId)
    {
        // dd('1');
        // $userId = Auth::id();
        $userId = Auth::id();
        if (is_null($userId) || $userId < 0) {
            $teacher = Teacher::find($teacherId);
            if ($teacher) {
                $userId = $teacher->user_id;
            }
        }
        // dd($userId);
        $availability = Calendar::
                                where('user_id', $userId)
                                ->where('teacher_id', $teacherId)
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
    public function getDayRecords(Request $request)
    {
        $products = Product::all();
        $date = $request->input('date');
        $teacherId = $request->input('teacherId');
        $dayOfWeek = date('l', strtotime($date));
        $userId = Auth::id();
        if ($userId < 0) {
            $teacher = Teacher::find($teacherId);
            if ($teacher) {
                $userId = $teacher->user_id;
            }
        }
        // dd($userId);
        /*$alreadyBookedSlots = Booking::where('teacher_id', $teacherId)*/
        $alreadyBookedSlots = OrderMeta::where('teacher_id', $teacherId)
            ->where('date', $date)
            ->get();
            // dd($alreadyBookedSlots);
        $formattedBookedSlots = [];
        foreach ($alreadyBookedSlots as $k => $v) {
        $dayOfWeek = date('l', strtotime($v->date)); 
            $start = new DateTime($v->start_time);
            $end = new DateTime($v->end_time);
            $formattedBookedSlots[$dayOfWeek][] = [
                'slot_start_time' => $start->format('H:i'),
                'slot_end_time' => $end->format('H:i')
            ];
        }
        // dd($formattedBookedSlots);
        
       /* $formattedBookedSlots = array_map(function($startTime, $endTime) {
            return [date('H:i', strtotime($startTime)), date('H:i', strtotime($endTime))];
        }, array_keys($alreadyBookedSlots), $alreadyBookedSlots);*/
        // dd($formattedBookedSlots);
        
        $availabilitySlots = $this->getSlotRecords(60, 'availability', $dayOfWeek, $teacherId);
        $unavailabilitySlots = $this->getSlotRecords(60, 'unavailability', $dayOfWeek, $teacherId);
        
        /*$availableSlots = array_filter($filteredSlots[$dayOfWeek] ?? [], function($slot) use ($formattedBookedSlots) {
            return !array_key_exists($slot['slot_start_time'], $formattedBookedSlots);
        });*/
        // dd($availableSlots);
        
        $records = Calendar::where('user_id', Auth::id())
            ->where('day_of_week', $dayOfWeek)
            ->get();
        
        $unavailableDay1 = UnavailableDay::where('date', $date)
                                        ->where('type', 1)
                                        ->where('teacher_id', $teacherId)
                                        ->exists();
        
        $unavailableDaysType0 = UnavailableDay::where('type', 0)
            ->where('date', $date)
            ->where('teacher_id', $teacherId)
            ->get();
        
        $unavailableSlotsByDay = [];
        foreach ($unavailableDaysType0 as $day) {
            $slots = $this->getOneHourSlots($day->start_time, $day->end_time);
            $dayOfWeek = date('l', strtotime($day->date)); 
            foreach ($slots as $slot) {
                $startTime = new DateTime($slot['start_time']);
                $endTime = new DateTime($slot['end_time']);
                $unavailableSlotsByDay[$dayOfWeek][] = [
                    'slot_start_time' => $startTime->format('H:i'),
                    'slot_end_time' => $endTime->format('H:i')
                ];
            }
        }
        
        $filteredSlots = $this->removeCommonSlots($availabilitySlots, $unavailabilitySlots, $teacherId);
        $partial = $this->removeCommonSlots($filteredSlots, $unavailableSlotsByDay);
        // dd($partial);
        $book = $this->removeCommonSlots($partial, $formattedBookedSlots);
        // dd($partial,$formattedBookedSlots);
        // dd($book);
        
        $daySlots = array_map(function($slot) use ($formattedBookedSlots) {
            $isBooked = array_key_exists($slot['slot_start_time'], $formattedBookedSlots);
            return [
                'start_time' => $slot['slot_start_time'] ?? 'N/A',
                'end_time' => $slot['slot_end_time'] ?? 'N/A',
                'type' => $slot['type'] ?? 'Slots',
                'is_booked' => $isBooked,
            ];
        }, $book[$dayOfWeek] ?? []);
        // dd($daySlots);
        
        return view('calendar.day-records', compact('records', 'daySlots', 'dayOfWeek', 'unavailableDay1', 'unavailableDaysType0', 'unavailableSlotsByDay', 'date', 'teacherId','products'))
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

}
