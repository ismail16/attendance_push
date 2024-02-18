@extends('layouts.app')

@section('title', 'User List')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Todays Attendance List</h5>
          </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Key</th>
                            <th>Device Id</th>
                            <th>User Id</th>
                            <th>Punch Time</th>
                            <th>Punch Mode</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($attendance as $data)
                            <tr>
                                <td>{{ $data->api_key }}</td>
                                <td>{{ $data->device_id }}</td>
                                <td>{{ $data->user_id }}</td>
                                <td>{{ $data->punch_time }}</td>
                                <td>{{ $data->punch_mode }}</td>
                                <td>{{ $data->status == 1? 'Synced' : 'Not Synced' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
