<!DOCTYPE html>
<html lang="en">

{{-- Include Head --}}
@include('frontend.common.login_head')

<body >


                <!-- Begin Page Content -->
                @yield('content')
                <!-- /.container-fluid -->


    
    <!--  Import Js Files -->
    <script src="{{asset('dashboard-assets/libs/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{asset('dashboard-assets/libs/simplebar/dist/simplebar.min.js')}}"></script>
    <script src="{{asset('dashboard-assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
    <!--  core files -->
    <script src="{{asset('dashboard-assets/js/app.min.js')}}"></script>
    <script src="{{asset('dashboard-assets/js/app.init.js')}}"></script>
    <script src="{{asset('dashboard-assets/js/app-style-switcher.js')}}"></script>
    <script src="{{asset('dashboard-assets/js/sidebarmenu.js')}}"></script>
    
    <script src="{{asset('dashboard-assets/js/custom.js')}}"></script>

    @yield('scripts')

    @stack('page_scripts')


</body>

</html>