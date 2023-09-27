@extends('layouts.web-app')

@section('title', $title)

@section('content')

<style>

@import url(https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.4.3/css/mdb.min.css);

.sp_union_table {
    margin-top: 50px!important;
}

.hm-gradient {
    background-image: linear-gradient(to top, #f3e7e9 0%, #e3eeff 99%, #e3eeff 100%);
}
.darken-grey-text {
    color: #2E2E2E;
}
.input-group.md-form.form-sm.form-2 input {
    border: 1px solid #bdbdbd;
    border-top-left-radius: 0.25rem;
    border-bottom-left-radius: 0.25rem;
}
.input-group.md-form.form-sm.form-2 input.purple-border {
    border: 1px solid #9e9e9e;
}
.input-group.md-form.form-sm.form-2 input[type=text]:focus:not([readonly]).purple-border {
    border: 1px solid #ba68c8;
    box-shadow: none;
}
.form-2 .input-group-addon {
    border: 1px solid #ba68c8;
}
.danger-text {
    color: #ff3547; 
}  
.success-text {
    color: #00C851; 
}
.table-bordered.red-border, .table-bordered.red-border th, .table-bordered.red-border td {
    border: 1px solid #ff3547!important;
}        
.table.table-bordered th {
    text-align: center;
}

.mdb-color.darken-3 {
    background-color: #fb3 !important;
}

.pagination.pg-blue .active .page-link {
    background-color: #fb3;
    color: #fff !important;
    border-radius: 70% !important;
    height: 40px;
    width: 40px;
    text-align: center;
    line-height: 30px;
}

.sp_union_table td, .sp_union_table th, .sp_union_table a {
    font-family: "IBM Plex Sans Thai";
    font-size: 15px !important;
}

ul.pagination {
    justify-content: center!important;
}

.active span.page-link {
    background-color: #F80 !important;
    color: #fff !important;
    border-radius: 70% !important;
    height: 40px;
    width: 40px;
    text-align: center;
    line-height: 30px;
    font-size: 20px;
    font-family: 'IBM Plex Sans Thai';
}

.pagination a:hover {
    background-color: #F80 !important;
    color: #fff !important;
    border-radius: 70% !important;
    height: 40px;
    width: 40px;
    text-align: center;
    line-height: 30px;
    font-size: 20px;
    font-family: 'IBM Plex Sans Thai';
}

.page-item:first-child .page-link {
    font-size: 20px !important;
    font-family: 'IBM Plex Sans Thai';
}

.pagination li {
    padding-right: 21px;
}

.pagination a {
    color: #000;
    border-radius: 70% !important;
    height: 40px;
    width: 40px;
    text-align: center;
    line-height: 30px;
    font-size: 20px;
    font-family: 'IBM Plex Sans Thai';
}

.page-item:not(:first-child) .page-link {
    font-size: 20px;
    font-family: 'IBM Plex Sans Thai';
    color: #000;
}

a.btn.btn-warning.m-2 {
    background-color: #fb3 !important;
}

.badge-warning {
    background-color: #fb3 !important;
    color: #FFF!important;
}

.modal-header {
    border-radius: 0;
}

.table .btn {
    padding: 0.59rem 1.5rem !important;
}

#progress-bar {background-color: #12CC1A;padding: 6px 0px;height: 35px;color: #FFFFFF;width:0%;-webkit-transition: width .3s;-moz-transition: width .3s;transition: width .3s;}
		
#progress-div {border:#0FA015 1px solid;padding: 0px 0px;margin:30px 0px;text-align:center;}

#targetLayer {
    width: 100%;
    text-align: center;
}

#upload_union_signed_document_modal input#userFile {
    padding: 5px 5px !important;
}

.form-control::file-selector-button {
    margin: 0px 10px 0px 0px;
    width: 40%;
}

</style>

<!--MDB Tables-->
<div class="container mt-4 sp_union_table">

<div class="card mb-4">
                <div class="card-body" style="min-height: 600px; font-family: 'IBM Plex Sans Thai';">

                    <div class="row">
                        <!-- Grid column -->
                        <div class="col-md-12">
                            <h2 class="py-3 text-center font-bold font-up black-text">รายการ และ สถานะคำสั่งซื้อ <br><span style="font-size:16px;">Purchase Order Status</span></h2>
                        </div>
                        <!-- Grid column -->
                    </div>

                    <!--Table-->
                    <table class="table table-hover">
                        <!--Table head-->
                        <thead class="badge-warning">
                            <tr class="text-white">
                                <th>No.</th>
                                <th>หมายเลขคำสั่งซื้อ <br> Order ID</th>
                                <th class="text-center">Status</th>
                                <th>หมายเลขใบสั่งซื้อ<br>ของผู้ซื้อ</th>
                                <th>ใบสั่งซื้อประทับตรา</th>
                                <th>วันที่สั่งซื้อ</th>
                                <th style="width: 300px;">Actions</th>
                            </tr>
                        </thead>
                        <!--Table head-->
                        <!--Table body-->
                        <tbody>
                        
                        @if( count($data) > 0)

                        @foreach ($data as $key => $row)

                            <tr>
                                <th scope="row">{{($data->currentPage() - 1) * $data->perPage() + $key + 1 }}</th>
                                <td>{{ $row->order_id }}</td> 
                                
                                <td class="text-center">

                                @php

                                    $status_text = '';
                                    $status_class = '';

                                    if( $row->status == '0' ){

                                        $status_text .= 'Draft PO Created';
                                        $status_class .= 'danger';

                                    } else if( $row->status == '1' ){

                                        $status_text .= 'Received PO';
                                        $status_class .= 'primary';

                                    } else if( $row->status == '2' ){

                                        $status_text .= 'Send Invoice To Supplier';
                                        $status_class .= 'primary';
                                        
                                    } else if( $row->status == '3' ){

                                        $status_text .= 'Supplier Dispatch Items';
                                        $status_class .= 'warning';
                                        
                                    } else if( $row->status == '4' ){

                                        $status_text .= 'Order Received By Client';
                                        $status_class .= 'warning';
                                        
                                    } else if( $row->status == '5' ){

                                        $status_text .= 'Invoice Issued';
                                        $status_class .= 'warning';
                                        
                                    } else if( $row->status == '6' ){

                                        $status_text .= 'Order Finish';
                                        $status_class .= 'success';
                                        
                                    }
                                    
                                    $document_show_status = false;

                                    if( !empty( $row->file ) ){

                                        $file = $row->file;

                                        $domain_url = url('');
                                    
                                        $item = str_replace($domain_url,'',$file);

                                        $file_exist = public_path().'/'.$item;
            
                                        if (is_file($file_exist) && file_exists($file_exist)) {
                                        
                                            $document_show_status = true;
                                            
                                        }
                                    }
						
                                    

                                @endphp
                                    
                                    <span class="badge badge-{{$status_class}}">{{$status_text}}</span>
                                    
                                </td>
                                <td>{{ $row->po_number }}</td>
                                <td>

                                @if($document_show_status)

                                    <a target="_blank" href="{{$file}}" data-toggle="tooltip" title="สำเนาเอกสารสั่งซื้อตัวจริง"><span class="badge badge-primary"><i class="fas fa-cloud-upload-alt" aria-hidden="true"></i></span></a>

                                @else

                                    <span class="badge badge-warning">N/A</span>

                                @endif
                            
                                </td>
                                <td>{{ $row->created_at }}</td>
                                <td>

                                    <a class="btn btn-primary m-2 view_order_item_list" data-toggle="tooltip" title="รายการสินค้าในคำสั่งซื้อ" data-user='<?php echo json_encode($row);?>' data-id="{{ $row->id }}"><i aria-hidden="true" class="fa fa-eye"></i></a>

                                    <a href="{{ route('union.vieworderUQF', ['order_id' => $row->id]) }}" target="_blank" data-id="{{ $row->id }}" class="btn btn-warning m-2 " data-toggle="tooltip" data-original-title="ดูใบสั่งซื้อในระบบ"><i class="fas fa-print"></i></a>

                                    <a  target="_blank" data-id="{{ $row->id }}" data-file="{{ $row->file }}" class="btn btn-success m-2 sp_upload_order_document" data-toggle="tooltip" data-original-title="Upload สำเนาเอกสารสั่งซื้อตัวจริง"><i class="fas fa-cloud-upload-alt"></i></a>

                                </td>
                            </tr>
                        
                         @endforeach

                         @else

                            <tr>
                                <td colspan="6" style="text-align: center;">No Record Found</th>
                                
                            </tr>

                         @endif
                            
                        </tbody>
                        <!--Table body-->
                    </table>
                    <!--Table-->

                    {{ $data->links() }}

                    

                </div>
            </div>
            </div>

            <!-- View Order Item List Modal -->

            <div class="modal fade" id="view_order_item_list_modal" tabindex="-1" role="dialog" aria-labelledby="deleteModalExample"
            aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                    
                            <div class="modal-header">
                                <h5 class="modal-title  text-white" id="deleteModalExample">View Order Item List | รายการสินค้า</h5>
                                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>


                            <div class="modal-body">
                                
                                <div class="msg_status" ></div>
                                <div class="ajax_content" ></div>
                            
                                
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
                            </div>


                    </div>
                </div>
            </div>

            <!-- Upload Union Signed Document Modal -->

            <div class="modal fade" id="upload_union_signed_document_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                    <div class="moving" style="
                        width: 100%;
                        height: 100%;
                        background: #00000059;
                        display: block;
                        position: absolute;
                        left: 0;
                        z-index: 9;
                        text-align: center;
                        display: none !important;
                        display: flex;
                        justify-content: center;
                        flex-direction: column;
                    "><p style="
                        z-index: 99;
                        color: #fff;
                        font-size: 25px;
                    ">Moving File....</p></div>
                        
                    <form class="sp_upload_union_signed_document_form" id="sp_upload_union_signed_document_form" enctype="multipart/form-data">

                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Upload Signed Document</h1>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="color: #fff !important;">×</span></button>
                        </div>

                        <div class="modal-body">

                            
                            <div class="msg_status" ></div>
                                
                            <div class="form-horizontal">

                                <div class="mb-3">
                                    <label for="Union-name" class="col-form-label">Document</label>
                                
                                    <input type="file" id="userFile" name="file" class="form-control" required >
                                </div>

                            
                            </div>

                            <div id="progress-div"><div id="progress-bar"></div></div>
			                <div id="targetLayer"></div>

                        </div>

                        <div class="modal-footer">

                            <button type="button" class="btn btn-secondary btn-outline-warning" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-warning sp_form_submit">Upload</button>

                            <input type="hidden" name="order_id" id="order_id" value="0">
			                <input type="hidden" name="file_prev" id="file_prev" value="0">
                            {!! csrf_field() !!}

                        </div>

                    </form>

                    </div>
                </div>
            </div>

      @endsection
      
      @push('page_scripts')
   
      <!-- Custom JS -->

      <script>

            $(document).tooltip({selector: '[data-toggle="tooltip"]'});

      </script>

      <script src="{{ asset('js/frontend_index.js') }}"></script>

      @endpush