@extends('layouts.login_app')

@section('title', 'Login')

@section('content')

    
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
      <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
        <div class="d-flex align-items-center justify-content-center w-100">
          <div class="row justify-content-center w-100">
            <div class="col-md-5 col-lg-5">
              <div class="card mb-0">
                <div class="card-body">
                <h2 class="mb-3 fs-7 fw-bolder text-center">{{ env('APP_NAME') }}</h2>
                

                  

                    @if (session('error'))
                      <span class="text-danger"> {{ session('error') }}</span>
                    @endif

                    @if (session('success'))
                      <span class="text-success"> {{ session('success') }}</span>
                    @endif

                    <form method="POST" action="{{ route('login') }}">

                  @csrf

                    <div class="mb-3">
                      <label for="exampleInputEmail1" class="form-label">Username</label>
                      <input id="email" type="email" class="form-control form-control-user @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email Address">

                        @error('email')
                           <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                           </span>
                        @enderror

                    </div>
                    <div class="mb-4">
                      <label for="exampleInputPassword1" class="form-label">Password</label>
                      <input id="password" type="password" class="form-control form-control-user @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">

                        @error('password')
                              <span class="invalid-feedback" role="alert">
                                 <strong>{{ $message }}</strong>
                              </span>
                        @enderror

                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-4">
                      <div class="form-check">
                        <input class="form-check-input primary"  type="checkbox" name="remember" id="customCheck" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label text-dark" for="flexCheckChecked">
                        Remember Me 
                        </label>
                      </div>
                      <a class="text-primary fw-medium" href="{{route('password.request')}}">Forgot Password ?</a>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-8 mb-4 rounded-2">Sign In</button>
                    
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
@endsection