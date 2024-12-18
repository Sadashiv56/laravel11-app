@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h2>Add Unavailable Days</h2>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('calendar.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</section>

<div class="container">
    <form action="{{ route('calendar.store-unavailable-days') }}" method="POST">
        @csrf
        <div id="unavailable-days-container">
            <div class="form-group unavailable-day-row">
                <div class="row">
                    <div class="col-md-6">
                        <label for="date">Date</label>
                        <input type="date" class="form-control" name="dates[]" required>
                        @error('dates.*')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 d-flex align-items-center">
                        <label>
                            <input type="checkbox" class="toggle-time-fields"> Include Time
                        </label>
                        <input type="hidden" name="types[]" class="type-field" value="1">
                    </div>
                </div>
                <div class="row time-fields" style="display: none;">
                    <div class="col-md-6">
                        <label for="start_time">Start Time</label>
                        <input type="time" class="form-control" name="start_times[]">
                        @error('start_times.*')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="end_time">End Time</label>
                        <input type="time" class="form-control" name="end_times[]">
                        @error('end_times.*')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Save</button>
    </form>
</div>

<center><h1>Unavailable Day List</h1></center>
<div class="container">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Id</th>
                <th>Date</th>
                <th>Type</th>
                <th>Start Time</th>
                <th>End Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($unavailableDays as $day)
                <tr>
                    <td>{{ $day->id }}</td>
                    <td>{{ $day->date }}</td>
                    <td>{{ $day->type ? '1' : '0' }}</td>
                    <td>{{ $day->start_time ?? 'NULL' }}</td>
                    <td>{{ $day->end_time ?? 'NULL' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('customejs')
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script>
$(document).ready(function() {
    $(document).on('change', '.toggle-time-fields', function() {
        var $row = $(this).closest('.form-group');
        var $timeFields = $row.find('.time-fields');
        var $typeField = $row.find('.type-field');
        
        if (this.checked) {
            $timeFields.show();
            $typeField.val('0'); 
        } else {
            $timeFields.hide();
            $typeField.val('1'); 
        }
    });
});
</script>
@endsection
