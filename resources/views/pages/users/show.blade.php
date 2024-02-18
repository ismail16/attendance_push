@extends('layouts.app')
@section('title', 'User View')

@section('content')
    <div class="card mb-4">
        <h5 class="card-header">User Information</h5>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Label</th>
                            <th>Information</th>
                        </tr>
                    </thead>
                    <tbody>

           
                        <tr>
                            <th scope="row">2</th>
                            <td>User ID</td>
                            <td>{{ $userid }}</td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td>Name</td>
                            <td>{{ $name }}</td>
                        </tr>
                        <tr>
                            <th scope="row">4</th>
                            <td>Role</td>
                            <td>{{ $role }}</td>
                        </tr>
                        <tr>
                            <th scope="row">5</th>
                            <td>Password</td>
                            <td>{{ $password }}</td>
                        </tr>
                        <tr>
                            <th scope="row">6</th>
                            <td>Card No</td>
                            <td>{{ $cardno }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <h5 class="card-header">User Fingerprints</h5>
        <div class="card-body">
            <div class="">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Sl</th>
                            <th scope="col">Fingerprint</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($userfingerprints as $userfingerprint)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{!! $userfingerprint !!}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
