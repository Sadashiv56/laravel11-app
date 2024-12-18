@if($unavailableDay1)
    <h3 style="color: red;">We are in holidays</h3>
@elseif(!$unavailableDaysType0->isEmpty())
    <h3>This day is partially closed.</h3>
    <!-- <center><h5>Unavailable Slots</h5></center>
    @if(empty($unavailableSlotsByDay[$dayOfWeek]))
        <p>No unavailable slots for this day.</p>
    @else
        <ul>
            @foreach($unavailableSlotsByDay[$dayOfWeek] as $slot)
                <li>{{ $slot['slot_start_time'] }} - {{ $slot['slot_end_time'] }}</li>
            @endforeach
        </ul>
    @endif -->

    <center><h5>Slots</h5></center>
    @if(empty($daySlots))
        <p>No available slots for this day.</p>
    @else
        <ul>
            @foreach($daySlots as $slot)
                <li>{{ $slot['start_time'] }} - {{ $slot['end_time'] }}: {{ $slot['type'] }}</li>
            @endforeach
        </ul>
    @endif
@else
   <!--  @if($records->isEmpty())
        <p>No slot available</p>
    @else
        <h3>Recorded Availability/Unavailability:</h3>
        <ul>
            @foreach($records as $record)
                <li>{{ $record->start_time }} - {{ $record->end_time }}: {{ ucfirst($record->type) }}</li>
            @endforeach
        </ul>
    @endif -->

    @if(empty($daySlots))
        <p></p>
    @else
       <!-- <h3>Available Slots:</h3>
        <ul>
            @foreach($daySlots as $slot)
                <li>{{ $slot['start_time'] }} - {{ $slot['end_time'] }}: {{ $slot['type'] }}</li>
            @endforeach
        </ul>  -->
    <ul>
        @foreach($daySlots as $slot)
            <li>
                {{ $slot['start_time'] }} - {{ $slot['end_time'] }}: {{ $slot['type'] }}
                <input type="checkbox" name="selected_slots[]" value="{{ $slot['start_time'] }}|{{ $slot['end_time'] }}">
            </li>
        @endforeach
    </ul>
    <center><button type="submit" class="btn btn-primary mt-3">Submit</button></center>



    @endif
@endif
