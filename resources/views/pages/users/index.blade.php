@extends('layouts.app')

@section('title', 'User List')

@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.bootstrap5.css">
    <style>
        .dt-info {
            position: absolute;
            padding-top: 0px !important;
        }

        .dt-search {
            margin: -10px 0px 10px 0px !important;
        }

        /* .table> :not(caption)>*>* {
                padding: 0.3rem 1.25rem !important;
            } */
    </style>

    <div class="d-flex bd-highlight">
        <div class="p-2 flex-grow-1 bd-highlight">
            <h5 class="">Users List</h5>
        </div>
        <div class="p-2 bd-highlight">
            <a class="btn btn-primary ml-4" href="{{ route('users.create') }}">
                <i class=' menu-icon tf-icons bx bx-user-plus'></i> Add New
            </a>
        </div>
        <div class="p-2 bd-highlight">
            <button id="deleteAllButton" class="btn btn-primary ml-4">
                <i class='menu-icon tf-icons bx bx-trash'></i> Delete All
            </button>
        </div>
    </div>


    <div class="card">
        <div class="card-header pb-0">
            <form action="{{ route('import-users-from-server') }}" method="POST" class="">
                @csrf
                <div class="row">
                    {{-- <h6>Add users From Server</h6> --}}
                    <div class="col-sm-3">
                        <select name="device_id" class="form-control">
                            <option value="">Please Select Device</option>
                            @foreach ($devices as $device)
                                <option value="{{ $device->id }}"
                                    {{ request()->device_id == old('device_id') ? 'selected' : '' }}>
                                    {{ $device->device_id . ' ' . $device->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <select name="users_type" class="form-control">
                            <option value="">Please Select User's Types</option>
                            <option value="EM_TMF">Staffs
                            </option>
                            <option value="SM">Male
                                Students
                            </option>
                            <option value="SF">Female
                                Students
                            </option>
                            <option value="SMF"> Male &
                                Female
                                Students</option>
                            <option value="EM_TMF_SMF">
                                Staffs &
                                All
                                Students</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <button type="submit" class="btn btn-primary mr-4">Add From Server
                        </button>
                    </div>
                </div>

            </form>
        </div>
        <hr class="m-2">
        <div class="card-body pt-0">
            @foreach ($errors as $error)
                <div class="alert alert-danger alert-dismissible" role="alert">
                    {{ $error['msg'] }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>
            @endforeach
            <div class="table-responsive text-nowrap">

                <table id="example" class="table table-striped table-bordered" style="width:100%">

                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Device Id</th>
                            <th>Role</th>
                            <th>Card No</th>
                            <th> Actions</a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($all_users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user['uid'] }}</td>
                                <td>{{ $user['name'] }}</td>
                                <td>{{ $user['device_id'] }}</td>
                                <td>{{ $user['role'] }}</td>
                                <td>{{ $user['cardno'] }}</td>
                                <td>
                                    <a class="btn btn-primary btn-sm" href="{{ route('users.show-single', $user) }}">
                                        View</a>

                                    <button type="button" class="btn btn-danger btn-sm" onclick="openDeleteModal(this);"
                                        data-id="user-{{ $user['userid'] }}">
                                        Delete
                                    </button>

                                    <form method="POST" id="user-{{ $user['userid'] }}"
                                        action="{{ route('users.destroy', ['uid' => $user['uid'], 'device_id' => $user['device_id']]) }}"
                                        class="d-none">
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
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $('#example').dataTable({
            dom: 'lifrtp',
            responsive: true,
            "pageLength": 100,
            "columnDefs": [{
                "targets": 'no-sort',
                "orderable": false,
            }],

        });



        // Handle click event on deleteAllButton
        $("#deleteAllButton").click(function(e) {
            e.preventDefault(); // Prevent default form submission

            // Show SweetAlert confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: 'This action will delete all users. This cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete all!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Make AJAX request to delete all users
                    $.ajax({
                        url: "{{ route('user.all-delete') }}",
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire(
                                'Success!',
                                response
                                .message, // Display the success message from PHP response
                                'success'
                            ).then(() => {
                                // Reload the current page
                                location.reload();
                            });
                        },
                        error: function(xhr, status, error) {
                            Swal.fire(
                                'Error!',
                                xhr
                                .responseText, // Display the error message from PHP response
                                'error'
                            );
                        }
                    });
                }
            });
        });
    </script>
@endsection
