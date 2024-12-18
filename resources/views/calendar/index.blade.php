@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Calendar Management</h2>
    <a href="{{ route('calendar.create-availability', ['teacher_id' => $teacher->id]) }}" class="btn btn-primary">Availability</a>
    <a href="{{ route('calendar.edit-unavailability', ['teacher_id' => $teacher->id]) }}" class="btn btn-primary">Unavailability</a>
    <a href="{{ route('calendar.create-unavailable-days', ['teacher_id' => $teacher->id]) }}" class="btn btn-primary">Unavailable Days</a>
    <a href="{{ route('calendar.show-calendar', ['teacher_id' => $teacher->id]) }}" class="btn btn-primary">Calendar</a>
</div> 
@endsection
