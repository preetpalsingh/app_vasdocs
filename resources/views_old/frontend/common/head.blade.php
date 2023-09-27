
<head>
    <title>{{ config('app.name', 'Laravel') }} | @yield('title')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Manrope:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
	<link id="u-page-google-font" rel="stylesheet" href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans+Thai:100,200,300,400,500,600,700">
    

    <!-- Font Awesome UI KIT-->
    <script src="https://kit.fontawesome.com/f75ab26951.js" crossorigin="anonymous"></script>
    
    <link href="{{asset('css/frontend_style.css')}}" rel="stylesheet" type="text/css" />
</head>
