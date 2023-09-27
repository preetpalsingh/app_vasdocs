<!-- custom redirect -->
@if (!Auth::check())

<script>

var url = window.location.pathname;
var baseurl = document.location.origin;

if( url == baseurl){

    window.location = "{{ Config::get('app.url') }}";
    
}


</script>

@endif
<!DOCTYPE html>
<html lang="en">

{{-- Include Head --}}
@include('frontend.common.head')

<body >

                <!-- Topbar -->
                @include('frontend.common.header')
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                @yield('content')
                <!-- /.container-fluid -->


            <!-- Footer -->
            @include('frontend.common.footer')
            <!-- End of Footer -->



    <!-- Bootstrap core JavaScript-->
    <script src="{{asset('js/app.js')}}"></script>


    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script>
        
        var base_url = "{{ Config::get('app.url') }}";

        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });

    </script>

    @yield('scripts')

    @stack('page_scripts')


</body>

</html>