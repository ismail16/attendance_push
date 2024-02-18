@extends('layouts.app')

@section('title', 'User List')

@section('content')
<h5 class="mb-0">Users List</h5><br>
    <div class="card">
        
        <div class="card-header d-flex justify-content-between align-items-center">
            {{-- <h5 class="mb-0">Users List</h5> --}}
            <a class="btn btn-primary btn-sm" href="{{ route('users.create') }}">Add User</a>
            <a class="btn btn-success btn-sm" href="{{ route('users.export') }}">Export Excel</a>
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
                            <th>SL</th>
                            <th>Device Id</th>
                            <th>User Id</th>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Password</th>
                            <th>Card No</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($all_users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user['device_id'] }}</td>
                                <td>{{ $user['userid'] }}</td>
                                <td>{{ $user['name'] }}</td>
                                <td>{{ $user['role'] }}</td>
                                <td>{{ $user['password'] }}</td>
                                <td>{{ $user['cardno'] }}</td>
                                <td>
                                    <a class="btn btn-primary btn-sm" href="{{ route('users.show-single', $user) }}">
                                        View</a>

                                    <button type="button" class="btn btn-danger btn-sm" onclick="openDeleteModal(this);"
                                        data-id="user-{{ $user['userid'] }}">
                                        Delete
                                    </button>

                                    <form method="POST" id="user-{{ $user['userid'] }}"
                                        action="{{ route('users.destroy', ['uid' => $user['uid'], 'device_id' => $user['device_id'] ]) }}" class="d-none">
                                        @csrf
                                        @method('DELETE')

                                    </form>

                                </td>
                            </tr>
                        @endforeach


                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
