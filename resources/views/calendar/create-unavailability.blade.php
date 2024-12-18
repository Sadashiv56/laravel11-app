@extends('layouts.app')
@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h2>Edit Unavailability</h2>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('teachers.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</section>
<div class="container">
         <form action="{{ route('calendar.update-unavailability', ['teacher_id' => $teacher_id]) }}" method="POST">
        @csrf
         <input type="hidden" name="teacher_id" value="{{ $teacher_id }}">
        <div class="form-group">
            <label>Monday</label>
            <div class="row">
                <div class="col">
                    <input type="time" name="days[Monday][start_time]" class="form-control" placeholder="Start Time"
                        value="{{ $unavailability['Monday'][0]->start_time ?? '' }}">
                </div>
                <div class="col">
                    <input type="time" name="days[Monday][end_time]" class="form-control" placeholder="End Time"
                        value="{{ $unavailability['Monday'][0]->end_time ?? '' }}">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Tuesday</label>
            <div class="row">
                <div class="col">
                    <input type="time" name="days[Tuesday][start_time]" class="form-control" placeholder="Start Time"
                        value="{{ $unavailability['Tuesday'][0]->start_time ?? '' }}">
                </div>
                <div class="col">
                    <input type="time" name="days[Tuesday][end_time]" class="form-control" placeholder="End Time"
                        value="{{ $unavailability['Tuesday'][0]->end_time ?? '' }}">
                </div>
            </div>
        </div>

        <!-- Repeat for the rest of the days -->
        <div class="form-group">
            <label>Wednesday</label>
            <div class="row">
                <div class="col">
                    <input type="time" name="days[Wednesday][start_time]" class="form-control" placeholder="Start Time"
                        value="{{ $unavailability['Wednesday'][0]->start_time ?? '' }}">
                </div>
                <div class="col">
                    <input type="time" name="days[Wednesday][end_time]" class="form-control" placeholder="End Time"
                        value="{{ $unavailability['Wednesday'][0]->end_time ?? '' }}">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Thursday</label>
            <div class="row">
                <div class="col">
                    <input type="time" name="days[Thursday][start_time]" class="form-control" placeholder="Start Time"
                        value="{{ $unavailability['Thursday'][0]->start_time ?? '' }}">
                </div>
                <div class="col">
                    <input type="time" name="days[Thursday][end_time]" class="form-control" placeholder="End Time"
                        value="{{ $unavailability['Thursday'][0]->end_time ?? '' }}">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Friday</label>
            <div class="row">
                <div class="col">
                    <input type="time" name="days[Friday][start_time]" class="form-control" placeholder="Start Time"
                        value="{{ $unavailability['Friday'][0]->start_time ?? '' }}">
                </div>
                <div class="col">
                    <input type="time" name="days[Friday][end_time]" class="form-control" placeholder="End Time"
                        value="{{ $unavailability['Friday'][0]->end_time ?? '' }}">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Saturday</label>
            <div class="row">
                <div class="col">
                    <input type="time" name="days[Saturday][start_time]" class="form-control" placeholder="Start Time"
                        value="{{ $unavailability['Saturday'][0]->start_time ?? '' }}">
                </div>
                <div class="col">
                    <input type="time" name="days[Saturday][end_time]" class="form-control" placeholder="End Time"
                        value="{{ $unavailability['Saturday'][0]->end_time ?? '' }}">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Sunday</label>
            <div class="row">
                <div class="col">
                    <input type="time" name="days[Sunday][start_time]" class="form-control" placeholder="Start Time"
                        value="{{ $unavailability['Sunday'][0]->start_time ?? '' }}">
                </div>
                <div class="col">
                    <input type="time" name="days[Sunday][end_time]" class="form-control" placeholder="End Time"
                        value="{{ $unavailability['Sunday'][0]->end_time ?? '' }}">
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update Unavailability</button>
    </form>
</div>
@endsection
