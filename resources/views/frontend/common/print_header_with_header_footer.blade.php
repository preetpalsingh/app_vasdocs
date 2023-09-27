<!--html>
    
<head>
    <meta charset="utf-8">
    <title>Quotation</title-->
    <!--link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css"-->

    <!-- Font Awesome UI KIT-->
    <script src="https://kit.fontawesome.com/f75ab26951.js" crossorigin="anonymous"></script>

    <!--link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet"-->

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
            .row{

                width: 100% !important;
    display: block !important;
    float: left !important;
    margin-left: 0px !important;
    margin-top: 0px !important;
    font-family: 'IBM Plex Sans Thai' !important;
            }

            .col-12.border-top.d-flex.justify-content-between p{
    border-top: #dee2e6 !important;
}

            nav.navbar.navbar-expand-lg.bg-light , section.Questions {
            display: none;
        }

        </style>

    @php

    }

    @endphp

    <style>
    .row {
        width: auto;
        display: flex;
        float: inherit;
        margin-left: 0px;
        margin-top: 0px;
        padding: 0px 38px;
    }
    body {
        margin-top: 0px;
        color: #2e323c;
        background: #f5f6fa;
        padding-left: 0px;
        padding-right: 0px;
        padding-top: 0px;
        font-family: 'IBM Plex Sans Thai';
    }

    section.Questions {
        background: #0D5E02 !important;
        padding: 20px 0px 5px;
        margin-top: 50px;
        float: left;
        width: 100%;
    }

    .text-right {
        text-align: right!important;
    }

    .font-weight-bold {
        font-weight: 700!important;
    }

    .table .thead-light th {
        color: #495057;
        background-color: #e9ecef;
        border-color: #dee2e6;
    }

    .table .thead-light th {
        color: #495057;
        background-color: #e9ecef;
        border-color: #dee2e6;
    }

    .table-bordered thead td, .table-bordered thead th {
        border-bottom-width: 2px;
    }
    .table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #dee2e6;
    }
    .table-bordered td, .table-bordered th {
        border: 1px solid #dee2e6;
    }
    .table td, .table th {
        padding: .75rem;
        vertical-align: top;
        border-top: 1px solid #dee2e6;
    }
    .font-weight-bold {
        font-weight: 700!important;
    }
    .text-uppercase {
        text-transform: uppercase!important;
    }
    .small, small {
        font-size: 80%;
        font-weight: 400;
    }
    th {
        text-align: inherit;
    }

    .table {
        width: 100%;
        margin-bottom: 1rem;
        color: #212529;
    }

    table {
        border-collapse: collapse;
    }

    .mb-0, .my-0 {
        margin-bottom: 0!important;
    }

    th.text-uppercase.small.font-weight-bold {
        width: 0%;
    }

    
    .btn:not(:disabled):not(.disabled) {
        cursor: pointer;
    }
    .mb-4, .my-4 {
        margin-bottom: 1.5rem!important;
    }

    .float-right {
        float: right!important;
    }
    .btn {
        display: inline-block;
        font-weight: 400;
        color: #fff;
        text-align: center;
        vertical-align: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        border: 1px solid transparent;
        padding: .375rem .75rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: .25rem;
        transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }

  .tooltip {
     width: 200px !important;
  }
    
    @media print {

        nav.navbar.navbar-expand-lg.bg-light , section.Questions {
            display: none;
        }

        body {
            background: #fff !important;
            padding-left: 0px;
            padding-right: 0px;
            padding-top: 0px;
        }

    }
</style>

</head>

<body cz-shortcut-listen="true">

  <div class="container" id="sp_print">