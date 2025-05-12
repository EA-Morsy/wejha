@extends('admin.layouts.auth')
@php $assetsPath = asset('assets/admin'); @endphp
@section('content')
    <div class="content-body">
        <div class="auth-wrapper auth-v1 px-2">
            <div class="auth-inner py-2">
                <!-- Login v1 -->
                <div class="card mb-0 bg-dark">
                    <div class="card-body" style="background-color: #0d0d0d; border-radius: 10px; box-shadow: 0 8px 24px rgba(0, 173, 163, 0.1);">
                        <a href="#" class="brand-logo d-flex justify-content-center mb-2">
                            <img src="{{ $assetsPath }}/images/logo.png" height="60">
                        </a>
                        <h2 class="col-md-12 text-center text-light mb-4">{{__('portal.wejha')}}</h2>
                        <form class="auth-login-form mt-2" action="{{ route('admin.postLogin') }}" method="POST">
                            @csrf
                            
                            @if (session()->has('error') || session()->has('errors') || count($errors) > 0)
                                <div class="alert alert-warning" style="background:#333;color:#fff;font-size:13px">
                                    <strong>Debug Info:</strong>
                                    <ul style="margin-bottom:0">
                                        @foreach (session()->all() as $key => $val)
                                            <li><b>{{ $key }}:</b> {{ is_array($val) ? json_encode($val) : $val }}</li>
                                        @endforeach
                                        @if ($errors && count($errors) > 0)
                                            @foreach ($errors->all() as $err)
                                                <li style="color:#ff4d4f">Error: {{ $err }}</li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                            @endif

                            @if(session('error'))
                            <div class="alert alert-danger text-white mb-3" role="alert" style="background-color: #ff4d4f; border-color: #ff4d4f; color: white; font-weight: 500; font-size: 14px; border-radius: 5px;">
                                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            </div>
                            @endif
                            
                            @if($errors->has('auth'))
                            <div class="alert mb-3" role="alert" style="background-color: #ff4d4f; border-color: #ff4d4f; color: white; font-weight: 500; font-size: 14px; border-radius: 5px;">
                                <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first('auth') }}
                            </div>
                            @endif
                            <div class="mb-3 @error('email') is-invalid @enderror">

                                <label for="login-email" class="form-label text-light">{{ __('admin.email') }}</label>
                                <input type="email" class="form-control bg-dark text-light border-0" style="border-bottom: 1px solid #00ada3 !important;" id="login-email" name="email" placeholder="" aria-describedby="login-email" tabindex="1" autofocus value="{{ old('email') }}" />
                                @error('email')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 @error('password') is-invalid @enderror">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label text-light" for="login-password">{{ __('admin.password') }}</label>
                                    {{-- @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="text-info">
                                            <small>{{ __('admin.forgot_password') }}</small>
                                        </a>
                                    @endif --}}
                                </div>
                                <div class="input-group input-group-merge form-password-toggle">
                                    <input type="password" class="form-control form-control-merge bg-dark text-light border-0" style="border-bottom: 1px solid #00ada3 !important;" id="login-password" name="password" tabindex="2" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="login-password" />
                                    <span class="input-group-text cursor-pointer bg-dark border-0" style="border-bottom: 1px solid #00ada3 !important;"><i data-feather="eye" class="text-light"></i></span>
                                </div>
                            </div>
                            <div>
                                @error('password')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input teal-checkbox" type="checkbox" id="remember" name="remember" tabindex="3" />
                                    <label class="form-check-label text-light" for="remember"> {{ __('admin.remember_me') }} </label>
                                </div>
                            </div>
                            <button class="btn w-100" style="background-color: #00ada3; color: #0d0d0d; font-weight: bold; border-radius: 5px; padding: 10px;" tabindex="4">{{ __('admin.login') }}</button>
                        </form>
                    </div>
                </div>
                <!-- /Login v1 -->
            </div>
        </div>

    </div>
@endsection
