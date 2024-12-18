@extends('layouts.app')
@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h2>Calendar</h2>
            </div>
        </div>
    </div>
</section>
<div class="container">
    <h2>My Calendar</h2>
    <div id='calendar'></div>
</div>
<!-- Modal Structure -->
<div class="modal fade" id="recordsModal" tabindex="-1" aria-labelledby="recordsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title" id="recordsModalLabel">Day Records</h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="recordsContent">
                <!-- Records will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('customejs')

<script>
$(document).ready(function() {
            // console.log('test');
    var calendarEl = $('#calendar')[0];
    var teacherId = '{{ $teacher_id}}'; 
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        editable: true,
        selectable: true,
        dateClick: function(info) {
            var selectedDate = info.dateStr;
            fetchDayRecords(selectedDate);
        },
        events: function(fetchInfo, successCallback, failureCallback) {
             
            $.ajax({
                url: '{{ route("calendar.show-calendar", ["teacher_id" => ":teacherId"]) }}'.replace(':teacherId', teacherId),
                method: 'GET',
                data: {
                    start: fetchInfo.startStr,
                    end: fetchInfo.endStr,
                    teacherId: teacherId
                },
                success: function(data) {
                    var events = [];

                    if (Array.isArray(data.availability)) {
                        events = events.concat(data.availability.flatMap(function(slot) {
                            return slot.map(function(item) {
                                return {
                                    title: item.slot_start_time + ' - ' + item.slot_end_time,
                                    start: fetchInfo.startStr + 'T' + item.slot_start_time,
                                    end: fetchInfo.startStr + 'T' + item.slot_end_time,
                                    backgroundColor: 'orange',
                                    borderColor: 'darkorange',
                                    textColor: 'white'
                                };
                            });
                        }));
                    }

                    if (Array.isArray(data.unavailability)) {
                        events = events.concat(data.unavailability.flatMap(function(slot) {
                            return slot.map(function(item) {
                                return {
                                    title: 'Unavailable',
                                    start: fetchInfo.startStr + 'T' + item.slot_start_time,
                                    end: fetchInfo.startStr + 'T' + item.slot_end_time,
                                    backgroundColor: 'red',
                                    borderColor: 'darkred',
                                    textColor: 'white'
                                };
                            });
                        }));
                    }

                    if (Array.isArray(data.unavailable_days_type_1)) {
                        events = events.concat(data.unavailable_days_type_1.map(function(day) {
                            return {
                                title: 'Unavailable Day',
                                start: day.date,
                                backgroundColor: 'red',
                                borderColor: 'darkred',
                                textColor: 'white'
                            };
                        }));
                    }

                    if (Array.isArray(data.unavailable_days_type_0) && data.unavailable_days_type_0.length > 0) {
                        events = events.concat(data.unavailable_days_type_0.map(function(day) {
                            return {
                                title: 'Partial Day',
                                start: day.date,
                                backgroundColor: 'green',
                                borderColor: 'darkgreen',
                                textColor: 'white'
                            };
                        }));
                    }

                    successCallback(events);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching events:', error);
                    successCallback([]);
                }
            });
}

    });

    calendar.render();

    function fetchDayRecords(date) {
        $.ajax({
            url: "{{ route('calendar.get-day-records') }}",
            method: 'GET',
            data: { date: date ,teacherId:teacherId},
            success: function(response) {
                $('#recordsContent').html(response);
                $('#recordsModal').modal('show');
            },
            error: function(xhr, status, error) {
                alert('There was an error while fetching records!');
            }
        });
    }
});
</script>








@endsection
