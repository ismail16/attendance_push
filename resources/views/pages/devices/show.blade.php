@extends('layouts.app')
@section('title', 'Device Information')

@section('content')
    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header">Device Information</h5>

            <h5 class="card-header">
                <a href="{{ route('device.test_sound', $device->id) }}" class="btn btn-primary  mr-2">
                     Test Sound
                </a>
                <a href="{{ route('device.restart', $device->id) }}" class="btn btn-primary  mr-2">
                     Restart 
                </a>
                <a href="{{ route('device.shutdown', $device->id) }}" class="btn btn-primary  mr-2">
                     Shut Down
                </a>
                
            </h5>

        </div>
        <div class="card-body">
            @if (count($device_info) > 0)
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Information</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>Device IP</td>
                                <td>{{ $device_info['deviceip'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td>Device Name</td>
                                <td>{{ $device_info['devicedeviceName'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">3</th>
                                <td>Device Version</td>
                                <td>{{ $device_info['deviceVersion'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">4</th>
                                <td>Device OS Version</td>
                                <td>{{ $device_info['deviceOSVersion'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">5</th>
                                <td>Device Platform</td>
                                <td>{{ $device_info['devicePlatform'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">6</th>
                                <td>Device Fm Version</td>
                                <td>{{ $device_info['devicefmVersion'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">7</th>
                                <td>Device Work Code</td>
                                <td>{{ $device_info['deviceworkCode'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">8</th>
                                <td>Device SSR</td>
                                <td>{{ $device_info['devicessr'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">9</th>
                                <td>Device Epin Width</td>
                                <td>{{ $device_info['devicepinWidth'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">10</th>
                                <td>Device Serial Number</td>
                                <td>{{ $device_info['deviceserialNumber'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">11</th>
                                <td>Device Time</td>
                                <td>{{ $device_info['devicegetTime'] }}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            @else
                Device Not Found
            @endif

        </div>
    </div>
@endsection
