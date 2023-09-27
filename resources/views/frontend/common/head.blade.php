
<head>
    <title>{{ config('app.name', 'Laravel') }} | @yield('title')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    

    <!-- Font Awesome UI KIT-->
    <!--script src="https://kit.fontawesome.com/f75ab26951.js" crossorigin="anonymous"></script-->
    
    <!--link href="{{asset('css/frontend_style.css')}}" rel="stylesheet" type="text/css" /-->
    <!-- Core Css -->
    <link  id="themeColors"  rel="stylesheet" href="{{asset('dashboard-assets/css/style.min.css')}}" />
</head>
