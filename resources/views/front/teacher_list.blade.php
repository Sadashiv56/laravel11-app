@extends('front.layouts.app')

@section('content')
<main>
    <section class="teacher_list_section layout_padding">
        <div class="container">
            <div class="heading_container heading_center">
                <h2>Available Teachers</h2>
            </div>
            <div class="row">
                <!-- Dropdown Form -->
                <div class="col-12 mb-4">
                    <div class="form-group">
                        <label for="teacher">Select Teacher:</label>
                        <select id="teacher" name="teacher_id" class="form-control">
                            <option value="">Select a Teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <!-- Calendar Container -->
                <div class="col-12">
                    <div id="calendarContainer" style="display: none;">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

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

@section('customejs')
<script>
$(document).ready(function() {
    var calendarEl = $('#calendar')[0];
    var calendar;

    initCalendar();

    $('#teacher').on('change', function() {
        var teacherId = $(this).val(); 
        if (teacherId) {
            $('#calendarContainer').show(); 
            initCalendar(teacherId);
        } else {
            $('#calendarContainer').hide(); 
            if (calendar) {
                calendar.destroy(); 
            }
        }
    });

    function initCalendar(teacherId = '') {
        if (calendar) {
            calendar.destroy(); 
        }

        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            editable: true,
            selectable: true,
            dateClick: function(info) {
                var selectedDate = info.dateStr;
                fetchDayRecords(selectedDate, teacherId);
            },
            events: function(fetchInfo, successCallback, failureCallback) {
                $.ajax({
                    url: '{{ route("calendar.teachers_list", ["teacher_id" => ":teacherId"]) }}'.replace(':teacherId', teacherId || 'all'),
                    method: 'GET',
                    data: {
                        start: fetchInfo.startStr,
                        end: fetchInfo.endStr,
                        teacherId: teacherId
                    },
                    success: function(data) {
                        var events = [];

                        // Process availability
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

                        // Process unavailability
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

                        // Process unavailable days type 1
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

                        // Process unavailable days type 0
                        if (Array.isArray(data.unavailable_days_type_0)) {
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
                   
                });
            }
        });

        calendar.render();
    }

    function fetchDayRecords(date, teacherId) {
        $.ajax({
            url: "{{ route('calendar.front-get-day-records') }}",
            method: 'GET',
            data: { date: date, teacherId: teacherId },
            success: function(response) {
                $('#recordsContent').html(response);
                $('#recordsModal').modal('show');
            },
            
        });
    }
});
</script>
@endsection
