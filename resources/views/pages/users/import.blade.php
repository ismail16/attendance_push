@extends('layouts.app')

@section('title', 'Users Import From Server')

@section('content')

<div class="card mb-4">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Import Users From Server</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('import-users-from-server') }}" method="POST">
            @csrf

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="user_id">Device</label>
                <div class="col-sm-10">
                    <select name="device_id" class="form-control">
                        <option value="">Please Select</option>
                        @foreach ($devices as $device)
                        <option value="{{ $device->id }}"> {{ $device->device_id. ' ' .$device->name }}</option>
                        @endforeach
                    </select>

                    @error('device_id')
                    <div class="form-text text-danger"> {{ $message }} </div>
                    @enderror

                </div>


            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="user_id">Types Of User</label>
                <div class="col-sm-10">
                    <select name="users_type" class="form-control">
                        <option value="">Please Select</option>
                        <option value="EM_TMF">Staffs</option>
                        <option value="SM">Male Students</option>
                        <option value="SF">Female Students</option>
                        <option value="SMF"> Male & Female Students</option>
                        <option value="EM_TMF_SMF">Staffs & All Students</option>
                    </select>

                    @error('users_type')
                    <div class=" form-text text-danger"> {{ $message }}
                    </div>
                    @enderror

                </div>
            </div>


            <div class="row justify-content-end">
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </div>


        </form>
    </div>

    @endsection