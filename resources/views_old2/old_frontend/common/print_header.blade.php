<html>
    
<head>
    <meta charset="utf-8">
    <title>Quotation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

    <!-- Font Awesome UI KIT-->
    <script src="https://kit.fontawesome.com/f75ab26951.js" crossorigin="anonymous"></script>

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link href="{{asset('css/print_style.css')}}" rel="stylesheet" type="text/css" />

    @php

    if( $data['options'] == 'hide' ){

    @endphp

        <style>

            body {
                background: #fff !important;
                padding-left: 0px;
                padding-right: 0px;
                padding-top: 0px;
            }

        </style>

    @php

    }

    @endphp

</head>

<body cz-shortcut-listen="true">

  <div class="container" id="sp_print">