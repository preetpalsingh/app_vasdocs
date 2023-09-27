@extends('layouts.login_app')

@section('title', 'Forgot Password')

@section('content')

    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
      <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
        <div class="d-flex align-items-center justify-content-center w-100">
          <div class="row justify-content-center w-100">
            <div class="col-md-5 col-lg-5">
              <div class="card mb-0">
                <div class="card-body">
                <h2 class="mb-3 fs-7 fw-bolder text-center">Welcome to {{ env('APP_NAME') }}</h2>
                  
                  <div class="position-relative text-center my-4">
                    <p class="mb-0 fs-4 px-3 d-inline-block bg-white text-dark z-index-5 position-relative">Reset Password</p>
                    <span class="border-top w-100 position-absolute top-50 start-50 translate-middle"></span>
                  </div>

                  

                    @if (session('error'))
                        <span class="text-danger"> {{ session('error') }}</span>
                    @endif

                    @if (Session::has('status'))

                        <div class="alert customize-alert alert-dismissible border-success text-success fade show remove-close-icon" role="alert">
                            <div class="d-flex align-items-center font-medium me-3 me-md-0">
                            <i class="ti ti-info-circle fs-5 me-2 text-success"></i>
                            <strong>Success ! </strong> {{ session('status') }}
                            </div>
                        </div>

                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3">

                            <input id="email" type="email"
                                class="form-control form-control-user @error('email') is-invalid @enderror"
                                name="email" value="{{ $email ?? old('email') }}" required
                                autocomplete="email" autofocus placeholder="Enter Email Address.">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                        <div class="mb-3">
                            
                            <input id="password" type="password"
                                class="form-control form-control-user @error('password') is-invalid @enderror"
                                name="password" required autocomplete="new-password" placeholder="New Password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                        <div class="mb-3">
                            
                        <input id="password-confirm" type="password" class="form-control form-control-user"
                                            name="password_confirmation" required autocomplete="new-password"
                                            placeholder="Confirm Password">

                        </div>
                   
                    
                        <button type="submit" class="btn btn-primary w-100 py-8 mb-4 rounded-2">Reset Password</button>

                        <!--div class="d-flex align-items-center justify-content-center">
                            <p class="fs-4 mb-0 fw-medium">Remember your password?</p>
                            <a class="text-primary fw-medium ms-2" href="{{route('login')}}">Login Now</a>
                        </div-->

                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>   
     
@endsection