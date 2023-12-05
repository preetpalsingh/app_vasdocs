<!DOCTYPE html>
<html lang="en">
<meta name="csrf-token" content="{{ csrf_token() }}">
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
    <script src="{{asset('dashboard-assets/js/app.init.js?dd=11')}}"></script>
    <script src="{{asset('dashboard-assets/js/app-style-switcher.js')}}"></script>
    <script src="{{asset('dashboard-assets/js/sidebarmenu.js')}}"></script>
    
    <script src="{{asset('dashboard-assets/js/custom.js')}}"></script>
    <!-- ---------------------------------------------- -->
    <!-- current page js files -->
    <!-- ---------------------------------------------- -->
    <!--script src="{{asset('dashboard-assets/js/apps/contact.js')}}"></script-->

    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script>

      // show search modal

      $(document).on("click", ".sp_search_client", function () {

        $('#sp_search_client_modal').modal('show');

        // Show loader before the request starts
        $('#sp_search_client_modal .message-body').append('<div id="sp_preloader"><div class="shapes-8"></div></div>');

        sp_load_clients('');

      });

  $(document).ready(function() {

      var typingTimer;
      var doneTypingInterval = 500; // milliseconds

      $('#sp_search_client_modal_input').on('input', function() {

          // Show loader before the request starts
          $('#sp_search_client_modal .message-body').append('<div id="sp_preloader"><div class="shapes-8"></div></div>');

          clearTimeout(typingTimer);
          var query = $(this).val();

          //if (query.length >= 3) {
              
              //typingTimer = setTimeout(function() {
                sp_load_clients(query);
              //}, doneTypingInterval);

          //} else{

            //sp_load_clients('');

          //}
      });

    });

      // search clients for modal

      function sp_load_clients(query) {

        // Show loader before the request starts
        //$('#sp_search_client_modal .message-body').append('<div id="sp_preloader"><div class="shapes-8"></div></div>');

        $.ajax({
            url: base_url+'/admin/client-search-for-modal',
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { query: query },
            before:{  },
            success: function(response) {
                //setTimeout(function() {

                    // Hide loader before the request starts
                    $('#sp_search_client_modal #sp_preloader').remove();

                    $('#sp_search_client_modal .sp_search_client_container').html(response.view);

                //}, 600);
                
            }
        });
    }

     

    </script>

    @yield('scripts')

    @stack('page_scripts')
    
  </body>
</html>