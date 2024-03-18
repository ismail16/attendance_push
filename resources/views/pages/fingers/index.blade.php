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

<div class="card">
    <div class="card-header pb-0">
        <form action="{{ route('fingerprint.import-to-device') }}" method="POST" class="">
            @csrf
            <div class="row">
                {{-- <h6>Add users From Server</h6> --}}
                <div class="col-sm-3">
                    <select name="device_id" class="form-control">
                        <option value="" selected>Please Select Device</option>
                        @foreach ($devices as $device)
                        <option value="{{ $device->id }}">
                            {{ $device->device_id . ' ' . $device->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-3">
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
                    <button type="submit" class="btn btn-primary mr-4">Import <i class='bx bx-fingerprint'></i> From Server To <i class='bx bx-devices'></i>
                    </button>
                </div>

                <!-- <div class="col-sm-3 d-flex flex-row-reverse">
                    <a href="{{ route('fingerprint.store') }}" class="btn btn-primary ml-4">Export <i class='bx bx-fingerprint'></i> to Server <i class='bx bx-server'></i></a>
                </div> -->
                <div class="col-sm-3 d-flex flex-row-reverse">
                    <button id="postButton" class="btn btn-primary ml-4">Export <i class='bx bx-fingerprint'></i> to Server <i class='bx bx-server'></i></button>
                </div>
            </div>

        </form>
    </div>
    <hr class="m-2">
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    $(document).ready(function() {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $("#postButton").click(function(event) {
            // Prevent default form submission behavior
            event.preventDefault();

            $.ajax({
                /* the route pointing to the post function */
                url: "{{ route('fingerprint.store') }}",
                type: 'POST',
                /* send the csrf-token and the input to the controller */
                data: {
                    _token: CSRF_TOKEN,
                },
                dataType: 'JSON',
                /* remind that 'data' is the response of the AjaxController */
                success: function(data) {
                    // Display success message using SweetAlert
                    Swal.fire({
                        title: 'Success!',
                        text: data.success,
                        icon: 'success',
                        confirmButtonText: 'Okay'
                    });

                    console.log(data);
                },
                error: function(xhr, textStatus, error) {
                    console.log(xhr.statusText);
                    console.log(textStatus);
                    console.log(error);
                }
            });
        });
    });
</script>
@endsection