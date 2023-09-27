<!DOCTYPE html>
<html lang="en">

{{-- Include Head --}}
@include('common.dashboard_head')
  
  <body>
    <!-- Preloader -->
    <div class="preloader">
      <img src="{{asset('dashboard-assets/images/logos/favicon.png')}}" alt="loader" class="lds-ripple img-fluid" />
    </div>
    <!-- --------------------------------------------------- -->
    <!-- Body Wrapper -->
    <!-- --------------------------------------------------- -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">

      <!-- Sidebar -->
      @include('common.dashboard_sidebar')
        <!-- End of Sidebar -->

      <!-- Main Wrapper -->
      <!-- --------------------------------------------------- -->

      <div class="body-wrapper">

        <!-- --------------------------------------------------- -->
        <!-- Header Start -->
        <!-- --------------------------------------------------- -->
        @include('common.dashboard_header')
        <!-- --------------------------------------------------- -->
        <!-- Header End -->
        <!-- --------------------------------------------------- -->

        <!-- Begin Page Content -->
        @yield('content')
        <!-- /.container-fluid -->
        
      </div>
    </div>
    
  
 <!-- ---------------------------------------------- -->
    <!-- Customizer -->
    <!-- ---------------------------------------------- -->
    <!-- ---------------------------------------------- -->
    <!-- Import Js Files -->
    <!-- ---------------------------------------------- -->
    <script src="{{asset('dashboard-assets/libs/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{asset('dashboard-assets/libs/simplebar/dist/simplebar.min.js')}}"></script>
    <script src="{{asset('dashboard-assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
    <!-- ---------------------------------------------- -->
    <!-- core files -->
    <!-- ---------------------------------------------- -->
    <script src="{{asset('dashboard-assets/js/app.min.js')}}"></script>
    <script src="{{asset('dashboard-assets/js/app.init.js')}}"></script>
    <script src="{{asset('dashboard-assets/js/app-style-switcher.js')}}"></script>
    <script src="{{asset('dashboard-assets/js/sidebarmenu.js')}}"></script>
    
    <script src="{{asset('dashboard-assets/js/custom.js')}}"></script>
    <!-- ---------------------------------------------- -->
    <!-- current page js files -->
    <!-- ---------------------------------------------- -->
    <script src="{{asset('dashboard-assets/js/apps/contact.js')}}"></script>

    @yield('scripts')

    @stack('page_scripts')
    
  </body>
</html>