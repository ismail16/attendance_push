@extends('layouts.guest')

@section('content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register Card -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <a href="index.html" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">
                                    <img src="{{ asset('images/gpush.png') }}" alt="">
                                </span>
                            </a>
                        </div>
                        <!-- /Logo -->


                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="username" class="form-label">Company Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Enter your company name" autofocus />
                                <div class="clearfix"></div>
                                @if ($errors->has('name'))
                                    <span class="form-text">
                                        <strong class="text-danger form-control-sm">{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="email" name="email"
                                    placeholder="Enter your email" />
                                <div class="clearfix"></div>
                                @if ($errors->has('email'))
                                    <span class="form-text">
                                        <strong class="text-danger form-control-sm">{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="mobile" class="form-label">Mobile <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="mobile" name="mobile"
                                    placeholder="Enter your mobile" required />
                                <div class="clearfix"></div>
                                @if ($errors->has('mobile'))
                                    <span class="form-text">
                                        <strong class="text-danger form-control-sm">{{ $errors->first('mobile') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="device_name" class="form-label">Device Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="device_name" name="device_name"
                                    placeholder="Enter your device name" required />
                                <div class="clearfix"></div>
                                @if ($errors->has('device_name'))
                                    <span class="form-text">
                                        <strong
                                            class="text-danger form-control-sm">{{ $errors->first('device_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="device_sl" class="form-label">Device Sl No <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="device_sl" name="device_sl"
                                    placeholder="Enter your device serial no" required />
                                <div class="clearfix"></div>
                                @if ($errors->has('device_sl'))
                                    <span class="form-text">
                                        <strong
                                            class="text-danger form-control-sm">{{ $errors->first('device_sl') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address <span class="text-danger"></span></label>
                                <input type="text" class="form-control" id="address" name="address"
                                    placeholder="Enter your address" />
                                <div class="clearfix"></div>
                                @if ($errors->has('address'))
                                    <span class="form-text">
                                        <strong class="text-danger form-control-sm">{{ $errors->first('address') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" required />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    <div class="clearfix"></div>
                                    @if ($errors->has('password'))
                                        <span class="form-text">
                                            <strong
                                                class="text-danger form-control-sm">{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <label class="form-label" for="password_confirmation">Confirm Password <span
                                        class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password_confirmation" class="form-control"
                                        name="password_confirmation"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" required />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    <div class="clearfix"></div>
                                    @if ($errors->has('password_confirmation'))
                                        <span class="form-text">
                                            <strong
                                                class="text-danger form-control-sm">{{ $errors->first('password_confirmation') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms-conditions" name="terms" />
                                    <label class="form-check-label" for="terms-conditions">
                                        I agree to
                                        <a href="javascript:void(0);">privacy policy & terms</a>
                                    </label>
                                </div>
                            </div> --}}
                            <button class="btn btn-primary d-grid w-100">Submit</button>
                        </form>

                        <p class="text-center mt-3">
                            <span>Already have an account?</span>
                            <a href="{{ route('login') }}">
                                <span>Sign in instead</span>
                            </a>
                        </p>
                    </div>
                </div>
                <!-- Register Card -->
            </div>
        </div>
    </div>
@endsection
