@extends('layouts.app')

@section('title', 'Add Device')

@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Edit Device</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('devices.update', $device->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="device_id">Device ID</label>
                    <div class="col-sm-10">
                        <input type="text" name="device_id" class="form-control @error('device_id') is-invalid @enderror"
                            id="device_id" placeholder="Enter Device Id" value="{{ $device->device_id }}">
                        @error('device_id')
                            <div class="invalid-feedback"> {{ $message }} </div>
                        @enderror

                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="name">Device Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name"
                            placeholder="Enter Device Name" value="{{ $device->name }}">
                        @error('name')
                            <div class="form-text text-danger"> {{ $message }} </div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="device_ip">Device IP</label>
                    <div class="col-sm-10">
                        <input type="text" name="device_ip" class="form-control @error('device_ip') is-invalid @enderror"
                            id="device_ip" placeholder="Enter Device IP" value="{{ $device->device_ip }}">
                        @error('device_ip')
                            <div class="form-text text-danger"> {{ $message }} </div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="status">Status</label>
                    <div class="col-sm-10">
                        <div class="form-check form-switch mt-1">
                            <input class="form-check-input" name="status" value="1" type="checkbox" id="status" {{ $device->status == 1? 'checked' : '' }}>
                          </div>
                        @error('status')
                            <div class="form-text text-danger"> {{ $message }} </div>
                        @enderror
                    </div>
                </div>

                <div class="row justify-content-end">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
