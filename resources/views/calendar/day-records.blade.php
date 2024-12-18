@if($unavailableDay1)
    <h3 style="color: red;">We are on holidays</h3>
@elseif(!$unavailableDaysType0->isEmpty())
    <h3>This day is partially closed.</h3>
    <center><h5>Slots</h5></center>
    @if(empty($daySlots))
        <p>No available slots for this day.</p>
    @else
          <!--  <p>Product Id: {{ session('selected_product_id') }}</p> -->

        <form action="{{ route('front.checkout') }}" method="POST">
            @csrf
            <input type="hidden" name="date" value="{{ $selectedDate }}"> 
            <input type="hidden" name="teacher_id" value="{{ $teacherId }}">
            <input type="hidden" name="product_id" value="{{ session('selected_product_id') }}">
           
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
            

            <center>
               <button type="submit" class="btn btn-danger mt-3">Continue</button>
            </center>
               
        </form>
       

    @endif
@else
    @if(empty($daySlots))
        <p>No slots available for booking.</p>
    @else
        <form action="{{ route('front.checkout') }}" method="POST">
            @csrf
            <input type="hidden" name="date" value="{{ $selectedDate }}">
            <input type="hidden" name="teacher_id" value="{{ $teacherId }}">
            <input type="hidden" name="product_id" value="{{ session('selected_product_id') }}">
           
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
            
            <center>
                <button type="submit" class="btn btn-danger mt-3">Continue</button>
            </center>
                
        </form>
        

    @endif
@endif
