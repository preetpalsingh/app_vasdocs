<!DOCTYPE html>
<html lang="en">

{{-- Include Head --}}
@include('common.head')

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include('common.sidebar')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @include('common.header')
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                @yield('content')
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            @include('common.footer')
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    @include('common.logout-modal')

    
    <!-- js and css for autocomplete seacrh -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <!-- Bootstrap core JavaScript-->
    <script src="{{asset('js/app.js?dd=1')}}"></script>


    <!-- Custom scripts for all pages-->
    <script src="{{asset('admin/js/sb-admin-2.min.js')}}"></script>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script>

        // custom add active class to sidebar

        var url = window.location.pathname;
        var baseurl = document.location.origin; 
        jQuery('ul#accordionSidebar li a').each(function(){
            //var sp_a = jQuery(this).find('a');
            //var sidebarurl = jQuery(this).find('a').attr('href');
            var sidebarurl = jQuery(this).attr('href');
            var sidebarurl1 = sidebarurl.split('?')[0];
            var remain_sidebar_url  = sidebarurl1.replace(baseurl,'');

            if(remain_sidebar_url == url){

                // check parent class

                sp_check_parent = jQuery(this).parent().attr('class');

                console.log(sp_check_parent);

                if(sp_check_parent.indexOf('collapse-inner') != -1){

                    jQuery(this).parent().parent().addClass('show');
                    jQuery(this).parent().parent().siblings().removeClass('collapsed');
                    jQuery(this).parent().parent().parent().addClass('active');
                    jQuery(this).addClass('active');

                } else {

                    jQuery(this).parent().addClass('active');
                        
                }

            }

            
        });

        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });

    </script>

    <style>

    .ui-autocomplete {
        z-index: 99999 !important;
    }
    </style>

    @yield('scripts')

    @stack('page_scripts')


</body>

</html>