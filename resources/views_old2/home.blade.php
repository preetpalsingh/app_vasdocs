@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <!--a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a-->
    </div>

    <div class="row">
        <div class="col-md-12 pb-5">
            <h2 class="text-center mb-3">Welcome To Strongroot Control Panel</h2>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-4 col-md-6 mb-3 py-3">
            <div class="card border-left-primary shadow h-100 py-3">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Number of last order<br>จำนวนคำสั่งซื้อล่าสุด</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$data->total_new_orders}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-4 col-md-6 mb-3 py-3">
            <div class="card border-left-info shadow h-100 py-3">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Number of waiting for upload official PO<br>จำนวนคำสั่งซื้อที่กำลังรอเอกสารตัวจริง</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$data->total_new_orders_draft}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-upload fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <!--div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tasks
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">50%</div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar"
                                            style="width: 50%" aria-valuenow="50" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div-->
        <!-- Pending Requests Card Example -->
        <div class="col-xl-4 col-md-6 mb-4 py-3">
            <div class="card border-left-danger shadow h-100 py-3">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Waiting for Issue PO<br>จำนวนคำสั่งซื้อที่รอการออก PO</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$data->total_po_recieved}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pending Requests Card Example -->
        <div class="col-xl-4 col-md-6 mb-3 py-3">
            <div class="card border-left-warning shadow h-100 py-3">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Number of status waiting for delivery<br>จำนวนคำสั่งซื้อที่กำลังรอการจัดส่ง</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$data->total_wait_for_delivery}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-truck fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requests Card Example -->
        <div class="col-xl-4 col-md-6 mb-4 py-3">
            <div class="card border-left-success shadow h-100 py-3">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Finish order<br>จำนวนคำสั่งซื้อที่จัดส่งสำเร็จแล้ว</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$data->total_finish_orders}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Pending Requests Card Example -->
        <div class="col-xl-4 col-md-6 mb-4 py-3">
            <div class="card border-left-secondary shadow h-100 py-3">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                            Log Last event</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$data->log_count}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-flag fa-2x text-secondary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    

</div>
@endsection