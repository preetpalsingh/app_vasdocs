@extends('auth.layouts.app')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center">

    <div class="text-center mt-5">
        <h1 class="text-white">{{ env('APP_NAME') }} - Admin Panel1</h1>
    </div>

    <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                    <div class="col-lg-6">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Login | STR Control Panel</h1>
                            </div>

                            @if (session('error'))
                                <span class="text-danger"> {{ session('error') }}</span>
                            @endif

                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="form-group">
                                    <input id="email" type="email" class="form-control form-control-user @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Login Email Address">

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                </div>
                                <div class="form-group">
                                    <input id="password" type="password" class="form-control form-control-user @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox small">
                                        <input class="custom-control-input" type="checkbox" name="remember" id="customCheck" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="custom-control-label" for="customCheck">Remember
                                            Me | Auto Login</label>
                                    </div>
                                </div>
                                <button class="btn btn-warning btn-user btn-block ">
                                    Login
                                </button>
                            </form>
                            <hr> 
                            <div class="text-center">
                                <a class="small" href="{{route('password.request')}}">ลืม Password ใช่ไหม?</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<div>
		<p><span style="color: #ffffff; font-weight: 500;">System portal @Strongroot.co.th Version 1.0.0</span>
		</p>
	</div>
</div>





@push('page_scripts')
   
   <!-- Custom css -->

    <style>
        #wrapper {
            display: block !important;
        }
    </style>

   @endpush

@endsection