@extends('layouts.app')

@section('title', $title . ' List')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">{{$title}}s Management <br>ระบบจัดการสินค้าประเภทซิมการ์ด</h1>
            <div class="row">
                <div class="col-md-12" >
                    <a hidden id="addFeed" class="btn btn-sm btn-primary" type="hidden">
                        <i class="fas fa-plus"></i> Add New
                    </a>
                </div>
                
            </div>

        </div>

        {{-- Alert Messages --}}
        @include('common.alert')

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">รายการชนิดซิมการ์ดทั้งหมด | All {{$title}}s</h6>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                            <th class="text-center">No.</th>
                            <th class="text-center">ผู้ให้บริการซิม <br> Sim Provider</th>
                            <th class="text-center">ราคาต่อเดือน <br> Price Per Month</th>
                            <th class="text-center">ระยะเวลาสัญญา <br> Contract Period</th>
                            <th class="text-center">รายละเอียดซิมการ์ด <br> Sim Description</th>
                            <th class="text-center">Update วันที่</th>  
                            <th class="text-center">ปุ่มคำสั่ง <br> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $key => $row)

                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td class="text-center">{{ $row->simcard_provider }}</td>   
                                <td class="text-center">{{ number_format($row->monthly_price, 2) }}</td>
                                <td class="text-center">{{ $row->period }}</td> 
                                <td class="text-center">{{ Str::limit($row->description, 50) }}</td> 
                                <td class="text-center">{{ $row->updated_at }}</td>
                                <td class="text-center text-red h4">

                                @hasrole('Admin')

                                    <a class="btn btn-primary m-2 edit-user" data-toggle="tooltip" title="Edit Product" data-user='<?php echo json_encode($row);?>'><i aria-hidden="true" class="fa fa-pen"></i></a>
                                    
                                    <!--a  class="btn btn-danger m-2  delete-feed" data-id="{{ $row->id }}"><i aria-hidden="true" class="fas fa-trash"></i></a-->

                                    <!--a  class="btn btn-danger m-2 modal-delete-trigger" data-toggle="tooltip" title="Delete Product" data-id="{{ $row->id }}" id="{{ $row->id }}"><i aria-hidden="true" class="fas fa-trash"></i></a-->

                                @endhasrole

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{ $data->links() }}

                    
                </div>
            </div>
        </div>

    </div>

    
    <!-- Add feed Modal -->
    
    <div class="modal fade" id="add_modal" tabindex="-1" role="dialog" aria-labelledby="deleteModalExample"
    aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            
                <form class="sp_form">

                    <div class="modal-header">
                        <h5 class="modal-title  text-primary" id="deleteModalExample">Add {{$title}}</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <input type="hidden" value="0" id="edit_id">
                    <input type="hidden" value="0" id="previous_img_val">

                        <div class="modal-body">
                        
                        <div class="msg_status" ></div>
                    
                        <div class="form-horizontal">
                            
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="English ">รายการ Simcard</label>
                                    <input type="text" class="form-control" id="simcard_provider" value="" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="English ">ราคาซื้อเหมาทำสัญญา</label>
                                    <input type="number" step="any" class="form-control" id="monthly_price" value="" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="English ">ระยะเวลาโครงการ (เดือน)</label>
                                    <input type="number" class="form-control" id="period" value="" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="English ">ข้อมูลทั่วไป </label>
                                    <textarea class="form-control" cols="100" id="description"></textarea>
                                </div>
                            </div>
                            
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-success" id="btn_add_feed">เพิ่ม</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <!-- Delete Confirm Modal -->
    <div class="modal fade" id="delete_confirm_modal" tabindex="-1" role="dialog" aria-labelledby="deleteModalExample"
    aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalExample">Delete Permanently</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Are you Sure You wanted to Delete?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a id="btn_delete_feed" class="btn btn-danger" >
                        Delete
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('page_scripts')
   
    <!-- Custom JS -->

    <script>

        var base_url = "{{ Config::get('app.url') }}";
        var modal_title = "{{$title}}";

    </script>

    <script src="{{ asset('js/simcard.js') }}"></script>

    @endpush

@endsection

@section('scripts')
    
@endsection

