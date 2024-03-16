@extends('layouts.app')

@section('title', 'Export')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Datawise Attendance Search</h5>
        </div>
        <div class="card-body">
            <!-- Date Picker for Date Range -->
            <div class="row">
                <div class="col-md-10">
                    <form action="{{ route('search.attendance') }}" method="GET" class="mb-3">
                        @csrf
                        <div class="row">
                            <div class="col-md-5">
                                <label for="start_date">Start Date:</label>
                                <input type="date" name="start_date" id="start_date" class="form-control"
                                    value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-5">
                                <label for="end_date">End Date:</label>
                                <input type="date" name="end_date" id="end_date" class="form-control"
                                    value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-2">
                                <label></label>
                                <button type="submit" class="btn btn-primary mt-4">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-2">
                    @if (isset($finalAttendances))
                        <button id="postbutton" class="btn btn-primary mt-4">Export to Server</button>
                    @endif
                </div>
            </div>


            {{-- @foreach ($final_attendances as $final_attendance)
                {{ $final_attendance['uid'] }}
            @endforeach --}}


            <!-- Display Search Results -->
            @if (isset($finalAttendances))
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User ID</th>
                            {{-- <th>APIKEY</th> --}}
                            <th>Device ID</th>
                            <th>Check IN Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($finalAttendances as $attendance)
                            <tr>
                                <td>{{ $attendance['uid'] }}</td>
                                <td>{{ $attendance['id'] }}</td>
                                {{-- <td>{{ $attendance['api_key'] }}</td> --}}
                                <td>{{ $attendance['device_id'] }}</td>
                                <td>{{ $attendance['timestamp'] }}</td>
                                {{-- <td>{{ $attendance['state'] == 1? 'Synced' : 'Not Synced' }}</td> --}}
                                <td>{{ $attendance['type'] == 0 ? 'Check IN' : 'Check Out' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            {{-- @if (isset($finalAttendances))
                <a href="{{ route('attendances.exportsearch', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="btn btn-success">Export to Excel</a>
            @endif --}}

        </div>
    </div>
    <!-- <script>
        $(document).ready(function() {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $("#postbutton").click(function() {
                $.ajax({
                    /* the route pointing to the post function */
                    url: "{{ route('device_attendance_push') }}",
                    type: 'POST',
                    /* send the csrf-token and the input to the controller */
                    data: {
                        _token: CSRF_TOKEN,
                        start_date: $("#start_date").val(),
                        start_date: $("#end_date").val()
                    },
                    dataType: 'JSON',
                    /* remind that 'data' is the response of the AjaxController */
                    success: function(data) {
                        console.log(data)
                    }
                });
            });
        });
    </script> -->

    <!-- Include SweetAlert library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        $(document).ready(function() {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $("#postbutton").click(function() {
                $.ajax({
                    /* the route pointing to the post function */
                    url: "{{ route('device_attendance_push') }}",
                    type: 'POST',
                    /* send the csrf-token and the input to the controller */
                    data: {
                        _token: CSRF_TOKEN,
                        start_date: $("#start_date").val(),
                        end_date: $("#end_date").val() // fix the duplicated key
                    },
                    dataType: 'JSON',
                    /* remind that 'data' is the response of the AjaxController */
                    success: function(data) {
                        // Display success message using SweetAlert
                        Swal.fire({
                            title: 'Success!',
                            text: data.success,
                            icon: 'success',
                            confirmButtonText: 'Okay'
                        });

                        console.log(data);
                    },
                    error: function(xhr, textStatus, error) {
                        console.log(xhr.statusText);
                        console.log(textStatus);
                        console.log(error);
                    }
                    // error: function(error) {
                    //     // Display error message using SweetAlert
                    //     Swal.fire({
                    //         title: 'Error!',
                    //         text: 'An error occurred while processing your request',
                    //         icon: 'error',
                    //         confirmButtonText: 'Okay'
                    //     });

                    //     console.error(error);
                    // }
                });
            });
        });
    </script>

@endsection
