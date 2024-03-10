@extends('layouts.app')
@section('title', 'Home')
@section('content')
    <div class="row">
        <div class="col-lg-8 mb-4 order-0">
            <div class="card mb-4">
                <div class=" row">
                    <div class="col-sm-10 d-flex justify-content-between align-items-center">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Welcome to Status PICode Push</h5>
                            <p class="mb-0">You are currently using as <span class="fw-bold">{{ $org_name }}</span></p>
                        </div>

                        {{-- <form method="post" action="{{ route('sync') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary" id="syncButton">Sync Data</button>
                        </form> --}}
                    </div>
                </div>
            </div>

           

            <div class="row">
                <div class="col-md-4">
                    <a href="{{ route('users.index') }}" >
                    <div class="card">
                        <div class="card-body" style="padding: 16px">
                            <div class="d-flex gap-3">
                                <div class="d-flex align-items-start">
                                    <div class="d-inline bg-label-success p-2 rounded text-success">
                                        <i class='bx bxs-user'></i>
                                    </div>
                                </div>

                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <small class="text-muted d-block mb-1"> Total Users</small>
                                        <h2 class="mb-0">{{ $user_count }}</h2>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="{{ route('devices.index') }}">
                    <div class="card">
                        <div class="card-body" style="padding: 16px">
                            <div class="d-flex gap-3">
                                <div class="d-flex align-items-start">
                                    <div class="d-inline bg-label-primary p-2 rounded text-primary">
                                        <i class='bx bxs-book-content'></i>
                                    </div>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <small class="text-muted d-block mb-1">Devices Connected</small>
                                        <h2 class="mb-0">{{ $device_count }}</h2>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="{{ route('attendance.index') }}">
                    <div class="card">
                        <div class="card-body" style="padding: 16px">
                            <div class="d-flex gap-3">
                                <div class="d-flex align-items-start">
                                    <div class="d-inline bg-label-info p-2 rounded text-info">
                                        <i class='bx bxs-spreadsheet'></i>
                                    </div>
                                </div>

                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <small class="text-muted d-block mb-1">Today's Attendance </small>
                                        <h2 class="mb-0">{{ $todays_attendance }}</h2>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    </a>
                </div>
                <br>

            
            </div>

          



        </div>
        <div class="col-lg-4 col-md-4 order-1">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-12 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h3 class="card-title text-center ">Customer Support</h3>
                            <h6 class="text-center"><i class='bx bxs-phone'></i> +8801686254438</h6>
                        </div>
                        <div class="card bg-dark border-0 text-white" style="border-radius: 0px">
                            <img class="card-img" style="border-radius: 0px" src="{{ asset('images/support.jpg') }}"
                                alt="Card image">
                            
                        </div>

                        <div class="card-footer support-info">
                            <h4 class="card-title text-center">Support Time</h4>
                            <p class="text-center"> 09:00 AM - 6:00 PM</p>
                            <p class="text-center">(Without Friday, Saturday & Govt. Holidays)</p>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection


@push('css')

<style>

    .support-info h4{
        font-size: 15px;
    }
    .support-info p{
        font-size: 12px;
    }

    @keyframes blink {
        0% { background-color: red; }
        25% { background-color: rgb(255, 0, 191); }
        50% { background-color: green; }
        75% { background-color: blue; }
        100% { background-color: red; }
    }

    .blinking {
        animation: blink 15s infinite;
    }
</style>
    
@endpush


<!-- Add this JavaScript in your <head> or before the closing </body> tag -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var syncButton = document.getElementById('syncButton');
    
            // Add blinking class to the button
            syncButton.classList.add('blinking');
        });
    </script>
