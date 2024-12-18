@extends('layouts.app')
@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                  <h2>Add Availability</h2>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('calendar.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</section>
<div class="container">
    <form action="{{ route('calendar.store-availability', ['user_id' => $user_id]) }}" method="POST">
        @csrf
    
        @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
            <div class="form-group">
                <label>{{ $day }}</label>
                <div class="row">
                    <div class="col">
                        <input type="time" 
                               name="days[{{ $day }}][start_time]" 
                               class="form-control" 
                               placeholder="Start Time"
                               value="{{ $availabilities->has($day) ? $availabilities[$day]->start_time : '' }}">
                    </div>
                    <div class="col">
                        <input type="time" 
                               name="days[{{ $day }}][end_time]" 
                               class="form-control" 
                               placeholder="End Time"
                               value="{{ $availabilities->has($day) ? $availabilities[$day]->end_time : '' }}">
                    </div>
                </div>
            </div>
        @endforeach
    
        <button type="submit" class="btn btn-primary">Update Availability</button>
    </form>
</div>
@endsection
