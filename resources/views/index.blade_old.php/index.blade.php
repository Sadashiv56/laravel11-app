@extends('layouts.app')

@section('content')
<div class="container">
    
        <!-- Display for other users -->
        <h2>Calendar Management</h2>
        <a href="{{ route('calendar.create-availability', ['user_id' => Auth::id()]) }}" class="btn btn-primary">Availability</a>
        <a href="{{ route('calendar.edit-unavailability', ['user_id' => Auth::id()]) }}" class="btn btn-primary">Unavailability</a>
        <a href="{{ route('calendar.create-unavailable-days') }}" class="btn btn-primary">Unavailable Days</a>
        <a href="{{ route('calendar.show-calendar') }}" class="btn btn-primary">Calendar</a>
</div>
@endsection
