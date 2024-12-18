@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h2>Add Unavailable Days</h2>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('teachers.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</section>

<div class="container">
   <form action="{{ route('calendar.store-unavailable-days', ['teacher_id' => $teacher_id]) }}" method="POST">
    @csrf
    <input type="hidden" name="teacher_id" value="{{ $teacher_id }}">
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
