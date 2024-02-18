@extends('layouts.app')
@section('title', 'Attendance')

@section('content')
    <div class="card">
        <div cla>

        </div>
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Attendance List </h5>
            <div>
                <a class="btn btn-primary btn-sm" href="{{ route('device.attendance') }}">Reload</a>
                {{-- <a class="btn btn-danger btn-sm" href="{{ route('device.clear_attendance') }}">Clear Attendance</a> --}}
                <a class="btn btn-success btn-sm" href="{{ route('attendances.export') }}">Export Excel</a>

            </div>

        </div>

        <div class="card-body">
            @foreach ($errors as $error)
                <div class="alert alert-danger alert-dismissible" role="alert">
                    {{ $error['msg'] }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>
            @endforeach
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Device ID</th>
                            <th scope="col">User ID</th>
                            <th scope="col">Timestamp</th>
                            <th scope="col">Type

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($final_attendances as $attendance)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $attendance['device_id'] }}</td>
                                <td>{{ $attendance['id'] }}</td>

                                @php
                                    $date = date_create($attendance['timestamp']);
                                @endphp
                                <td>{{ date_format($date, 'd-M-Y h:i:s a') }}</td>
                                <td>{{ $attendance['type'] == 0 ? 'Check IN' : 'Check Out' }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
