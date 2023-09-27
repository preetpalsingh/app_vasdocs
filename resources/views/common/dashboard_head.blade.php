<head>
    <!-- --------------------------------------------------- -->
    <!-- Title -->
    <!-- --------------------------------------------------- -->
    <title>{{ config('app.name', 'Laravel') }} | @yield('title')</title>
    <!-- --------------------------------------------------- -->
    <!-- Required Meta Tag -->
    <!-- --------------------------------------------------- -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="handheldfriendly" content="true" />
    <meta name="MobileOptimized" content="width" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <meta name="keywords" content="" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- --------------------------------------------------- -->
    <!-- Favicon -->
    <!-- --------------------------------------------------- -->
    <link rel="shortcut icon" type="image/png" href="{{asset('dashboard-assets/images/logos/favicon.png')}}" />
    <!-- --------------------------------------------------- -->
    <!-- Core Css -->
    <!-- --------------------------------------------------- -->
    <link  id="themeColors"  rel="stylesheet" href="{{asset('dashboard-assets/css/style.min.css?dd=5484')}}" />
    <style>
      header.app-header.fixed-header {
          position: relative !important;
      }
      
      h5.modal-title {
          color: #539BFF;
      }

      .modal-header.d-flex.align-items-center {
          border-bottom: 1px solid #e3e6f0;
          background-color: #f8f9fc;
      }

      .modal-footer {
          background-color: #f8f9fc;
          border-top: 1px solid #e3e6f0;
      }

      .shapes-8 {
        display: inline-block;
    position: absolute !important;
    top: calc(50% - 3.5px);
    left: 0;
    right: 0;
    margin: 0 auto;
        width: 40px;
        height: 40px;
        color:#f03355;
        position: relative;
        background: radial-gradient(10px,currentColor 94%,#0000);
      }
      .shapes-8:before {
        content:'';
        position: absolute;
        inset:0;
        border-radius: 50%;
        background:
          radial-gradient(9px at bottom right,#0000 94%,currentColor) top    left,
          radial-gradient(9px at bottom left ,#0000 94%,currentColor) top    right,
          radial-gradient(9px at top    right,#0000 94%,currentColor) bottom left,
          radial-gradient(9px at top    left ,#0000 94%,currentColor) bottom right;
        background-size:20px 20px;
        background-repeat: no-repeat;
        animation: sp8 1.5s infinite cubic-bezier(0.3,1,0,1);
      }
      @keyframes sp8 {
         33%  {inset:-10px;transform: rotate(0deg)}
         66%  {inset:-10px;transform: rotate(90deg)}
         100% {inset:0    ;transform: rotate(90deg)}
      }

    div#sp_preloader {
        width: 100%;
        height: 100%;
        top: 0;
        position: absolute;
        z-index: 99999;
        background: #ffffff78;
        left: 0;
    }

    .table-responsive {
    overflow-x: auto !important;
}

a#sp_app_progress_btn {
    position: fixed;
    bottom: 10px;
}

    </style>
  </head>