@extends('layouts.app')

@section('content')
<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: left;
    }
    th {
        background-color: #f4f4f4;
    }
</style>
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                    <h2>Calendar Overview</h2>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('calendar.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</section>
<div class="container">
    <center><h3>Available Slots</h3></center>
    <table>
        <thead>
            <tr>
                <th>Day of Week</th>
                <th>Start Time</th>
                <th>End Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($availability as $slot)
                <tr>
                    <td>{{ $slot->day_of_week }}</td>
                    <td>{{ $slot->start_time }}</td>
                    <td>{{ $slot->end_time }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <center><h3>Unavailable Slots</h3></center>
    <table>
        <thead>
            <tr>
                <th>Day of Week</th>
                <th>Start Time</th>
                <th>End Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($unavailability as $slot)
                <tr>
                    <td>{{ $slot->day_of_week }}</td>
                    <td>{{ $slot->start_time }}</td>
                    <td>{{ $slot->end_time }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <center><h3>Unavailable Days</h3></center>
    <table>
        <thead>
            <tr>
                <th>Day of Week</th>
                <th>Start Time</th>
                <th>End Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($unavailable_days as $day)
                <tr>
                    <td>{{ $day->date }}</td>
                    <td>{{ $slot->start_time }}</td>
                    <td>{{ $slot->end_time }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
   

    <!-- You can add a more sophisticated calendar display using a JavaScript calendar library -->
</div>
@endsection
