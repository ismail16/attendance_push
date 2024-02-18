@extends('layouts.app')
@section('title', 'Device Information')

@section('content')
    <div class="card">

        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header">Device List</h5>

            <h5 class="card-header">
                <a href="{{ route('devices.create') }}" class="btn btn-primary  mr-2">
                    <i class='bx bx-plus'></i> Add Device
                </a>
            </h5>

        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Device Id</th>
                            <th>Device Name</th>
                            <th>Device IP Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($devices as $device)
                            <tr>
                                <td>{{ $device->device_id }}</td>
                                <td>{{ $device->name }}</td>
                                <td>{{ $device->device_ip }}</td>
                        
                                <td>
                                    <a href="{{ route('devices.show', $device->id) }}" class="btn btn-sm btn-icon"><i
                                            class='bx bx-show'></i></a>
                                    <a href="{{ route('devices.edit', $device->id) }}" class="btn btn-sm btn-icon"><i
                                            class='bx bxs-edit-alt'></i></a>

                                    <button type="button" class="btn btn-sm btn-icon" onclick="openDeleteModal(this);"
                                        data-id="{{ $device->id }}">
                                        <i class='bx bxs-trash'></i>
                                    </button>

                                    <form method="POST" id="{{ $device->id }}"
                                        action="{{ route('devices.destroy', $device->id) }}" class="d-none">
                                        @csrf
                                        @method('DELETE')

                                    </form>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">No Data Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>


        </div>
    </div>
@endsection
