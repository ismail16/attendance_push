@extends('layouts.app')

@section('title', 'Export')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Datawise Attendance Search</h5>
        </div>

        <div class="card-body">
            <!-- Date Picker for Date Range -->
            <form action="{{ route('search.attendance') }}" method="GET" class="mb-3">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <label for="start_date">Start Date:</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label for="end_date">End Date:</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label></label>
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </form>

            <!-- Display Search Results -->
            @if (isset($finalAttendances))
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>APIKEY</th>
                            <th>Device ID</th>
                            <th>User ID</th>
                            <th>Date Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($finalAttendances as $attendance)
                            <tr>
                                <td>{{ $attendance['id'] }}</td>
                                <td>{{ $attendance['api_key'] }}</td>
                                <td>{{ $attendance['device_id'] }}</td>
                                <td>{{ $attendance['uid'] }}</td>
                                <td>{{ $attendance['timestamp'] }}</td>
                                {{-- <td>{{ $attendance['state'] == 1? 'Synced' : 'Not Synced' }}</td> --}}
                                <td>{{ $attendance['type'] == 0 ? 'Check IN' : 'Check Out' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif




            @if (isset($finalAttendances))
                <a href="{{ route('attendances.exportsearch', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                    class="btn btn-success">Export to Excel</a>
            @endif

            @if (isset($finalAttendances))
                <button id="postbutton" class="btn btn-primary">Export to DB</button>
            @endif



        </div>
    </div>
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
    </script>
@endsection
