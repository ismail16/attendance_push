@extends('layouts.app')

@section('title', 'Add Device')

@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Add Device</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('devices.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="device_id">Device ID<span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" name="device_id" class="form-control @error('device_id') is-invalid @enderror"
                            id="device_id" placeholder="Enter Device Id" required>

                        @error('device_id')
                            <div class="invalid-feedback"> {{ $message }} </div>
                        @enderror

                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="name">Device Name<span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name"
                            placeholder="Enter Device Name" required>
                        @error('name')
                            <div class="form-text text-danger"> {{ $message }} </div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="device_ip">Device IP<span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" name="device_ip" class="form-control @error('device_ip') is-invalid @enderror"
                            id="device_ip" placeholder="Enter Device IP" required>
                        @error('device_ip')
                            <div class="form-text text-danger"> {{ $message }} </div>
                        @enderror
                    </div>
                </div>


                <div class="row justify-content-end">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="reset" class="btn btn-danger">Cancel</button>
                    </div>
                </div>

            </form>
        </div>


    </div>

@endsection
