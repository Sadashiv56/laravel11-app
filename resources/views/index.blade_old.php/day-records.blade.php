@if($unavailableDay1)
    <h3 style="color: red;">We are on holidays</h3>
@elseif(!$unavailableDaysType0->isEmpty())
    <h3>This day is partially closed.</h3>
    <!-- You can uncomment the following section if needed -->
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
        <form action="{{ route('book-slots') }}" method="POST">
            @csrf
            <input type="hidden" name="date" value="{{ $selectedDate }}"> <!-- Ensure date is passed -->

            <ul>
                @foreach($daySlots as $slot)
                    <li>
                        {{ $slot['start_time'] }} - {{ $slot['end_time'] }}: {{ $slot['type'] }}
                        <input type="checkbox" name="selected_slots[]" value="{{ $slot['start_time'] }}|{{ $slot['end_time'] }}">
                    </li>
                @endforeach
            </ul>

            <center><button type="submit" class="btn btn-primary mt-3">Submit</button></center>
        </form>
    @endif
@else
    @if(empty($daySlots))
        <p>No slots available for booking.</p>
    @else
        <!-- Uncomment this section if you want to display available slots when records are not empty -->
        <!-- <h3>Available Slots:</h3>
        <ul>
            @foreach($daySlots as $slot)
                <li>{{ $slot['start_time'] }} - {{ $slot['end_time'] }}: {{ $slot['type'] }}</li>
            @endforeach
        </ul> -->
        <!-- <form action="{{ route('book-slots') }}" method="POST">
            @csrf
            <input type="hidden" name="date" value="{{ $selectedDate }}"> 

            <ul>
                @foreach($daySlots as $slot)
                    <li>
                        {{ $slot['start_time'] }} - {{ $slot['end_time'] }}: {{ $slot['type'] }}
                        <input type="checkbox" name="selected_slots[]" value="{{ $slot['start_time'] }}|{{ $slot['end_time'] }}">
                    </li>
                @endforeach
            </ul>

            <center><button type="submit" class="btn btn-primary mt-3">Submit</button></center>
        </form> -->
     <form action="{{ route('book-slots') }}" method="POST">
    @csrf
    <input type="hidden" name="date" value="{{ $selectedDate }}">

    <ul>
        @foreach($daySlots as $slot)
            <li>
                {{ $slot['start_time'] }} - {{ $slot['end_time'] }}: {{ $slot['type'] }}
                <input type="checkbox" name="selected_slots[]" 
                       value="{{ $slot['start_time'] }}|{{ $slot['end_time'] }}"
                       @if($slot['is_booked']) checked disabled @endif>
            </li>
        @endforeach
    </ul>

    <center><button type="submit" class="btn btn-primary mt-3">Submit</button></center>
</form>






    @endif
@endif
