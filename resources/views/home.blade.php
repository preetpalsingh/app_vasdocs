@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="container-fluid">

    <div class="d-flex border-bottom title-part-padding px-0 mb-3 align-items-center">
        <div>
        <h4 class="mb-0 fs-5">Home</h4>
        </div>
    </div>

    <!--div class="row">
        <div class="col-md-12 pb-5">
            <h2 class="text-center mb-3">Welcome To Chatha & CO</h2>
        </div>
    </div-->

    <!-- Content Row -->
    <div class="row">

        <div class="col-md-4 d-flex align-items-stretch">
            <a href="{{ route('admin.clientList') }}" class="card bg-success text-white w-100 card-hover">
            <div class="card-body">
                <div class="d-flex align-items-center">
                <i class="ti ti-users display-6"></i>
                <div class="ms-auto">
                    <i class="ti ti-arrow-right fs-8"></i>
                </div>
                </div>
                <div class="mt-4">
                <h4 class="card-title mb-1 text-white">{{ $data->client_count }}</h4>
                <h6 class="card-text fw-normal text-white-50">
                    Number of Clients
                </h6>
                </div>
            </div>
            </a>
        </div>

        @hasrole('Admin')

        <div class="col-md-4 d-flex align-items-stretch">
            <a href="{{ route('admin.staffList') }}" class="card bg-warning text-white w-100 card-hover">
            <div class="card-body">
                <div class="d-flex align-items-center">
                <i class="ti ti-user-check display-6"></i>
                <div class="ms-auto">
                    <i class="ti ti-arrow-right fs-8"></i>
                </div>
                </div>
                <div class="mt-4">
                <h4 class="card-title mb-1 text-white">{{ $data->staff_count }}</h4>
                <h6 class="card-text fw-normal text-white-50">
                    Number of Staff
                </h6>
                </div>
            </div>
            </a>
        </div>

        @endhasrole

        <!--div class="col-md-4 d-flex align-items-stretch">
            <a href="javascript:void(0)" class="card bg-danger text-white w-100 card-hover">
            <div class="card-body">
                <div class="d-flex align-items-center">
                <i class="ti ti-calendar display-6"></i>
                <div class="ms-auto">
                    <i class="ti ti-arrow-right fs-8"></i>
                </div>
                </div>
                <div class="mt-4">
                <h4 class="card-title mb-1 text-white">
                    Dispatch Products
                </h4>
                <h6 class="card-text fw-normal text-white-50">
                    Shoes, Jeans, Party wear, Watchs
                </h6>
                </div>
            </div>
            </a>
        </div-->


    </div>

    

</div>
@endsection