@extends('layouts.app')

@section('title', 'Create User')

@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Add User</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="user_id">Device</label>
                    <div class="col-sm-10">
                        <select name="device_id" class="form-control" >
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
                    <label class="col-sm-2 col-form-label" for="name">Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name" value="{{ old('name') }}">
                        @error('username')
                            <div class="form-text text-danger"> {{ $message }} </div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="role">Role</label>
                    <div class="col-sm-10">
                        <input type="number" name="role" class="form-control" id="role" placeholder="Enter Role ID" value="{{ old('role') }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="password">Password</label>
                    <div class="col-sm-10">
                        <input type="text" name="password" class="form-control" id="password" placeholder="*******">
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="card_no">Card No</label>
                    <div class="col-sm-10">
                        <input type="text" name="cardno" class="form-control" id="card_no" placeholder="98734234" value="{{ old('cardno') }}">
                    </div>
                </div>
                <div class="row justify-content-end">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary">Send</button>
                    </div>
                </div>
        </div>

        </form>
    </div>
    </div>
@endsection
