@extends('layouts.app')

@section('title', $title . ' List')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">{{$title}}s Management <br>บริหารรายการคำสั่งซื้อ</span></h1>
            <div class="row">
                <div class="col-md-12">
                    <!--a id="addFeed" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Add New
                    </a-->
                </div>
                
            </div>

        </div>

        {{-- Alert Messages --}}
        @include('common.alert')

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">ข้อมูล {{$title}}s ทั้งหมด</h6>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                            <th class="text-center">No.</th>
                            <th class="text-center">Order ID</th>
                            <th class="text-center">จากสหกรณ์ <br>Union name</th>
                            <!--th class="text-center">รายการสินค้า <br>Product</th>
                            <th class="text-center">จำนวน <br> Quantity</th>
                            <th class="text-center">ค่าย <br>Simcard</th-->
                            <th class="text-center">Status</th>
                            <th class="text-center">Po. Number</th>
                            <th class="text-center">Document</th>
                            <th class="text-center">วันที่สั่งซื้อ<br>Date</th>  
                            <th class="text-center">ปุ่มคำสั่ง<br>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $key => $row)

                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td class="text-center">{{ $row->order_id }}</td>   
                                <td class="text-center">{{ $row->first_name }}</td> 
                                
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

                                    <a target="_blank" href="{{$file}}" data-toggle="tooltip" title="Uploaded Signed Document"><span class="badge badge-primary"><i class="fas fa-cloud-upload-alt" aria-hidden="true"></i></span></a>

                                @else

                                    <span class="badge badge-warning">N/A</span>

                                @endif
                            
                                </td>
                                <td class="text-center">{{ $row->created_at }}</td>
                                <td class="text-center text-red h4">

                                @hasrole('Admin')

                                    <!--a data-id="{{ $row->id }}" class="btn btn-primary m-2 assign_to_phone_vendor" data-toggle="tooltip" data-original-title="สร้างคำสั่งซื้อไปยังคู่ค้า"><i class="fas fa-user-plus"></i></a-->

                                    <a class="btn btn-success m-2 update_order_status" data-toggle="tooltip" title="เปลี่ยนแปลงสถานะงาน" data-status='<?php echo $row->status;?>' data-id="{{ $row->id }}"><i aria-hidden="true" class="fa fa-pen"></i></a>

                                    <a class="btn btn-primary m-2 view_order_item_list" data-toggle="tooltip" title="จัดทำ PO เพื่อส่งไปให้ผู้ผลิต" data-user='<?php echo json_encode($row);?>' data-id="{{ $row->id }}"><i aria-hidden="true" class="fa fa-eye"></i></a>

                                    <a href="{{ route('admin.vieworderUQ', ['order_id' => $row->id]) }}" target="_blank" data-id="{{ $row->id }}" class="btn btn-warning m-2 " data-toggle="tooltip" data-original-title="ดูเอกสารสั่งซื้อจากสหกรณ์"><i class="fas fa-print"></i></a>

                                    <!--a class="btn btn-primary m-2 edit-user" data-toggle="tooltip" title="Edit Product" data-user='<?php echo json_encode($row);?>'><i aria-hidden="true" class="fa fa-pen"></i></a-->
                                    
                                    <!--a  class="btn btn-danger m-2  delete-feed" data-id="{{ $row->id }}"><i aria-hidden="true" class="fas fa-trash"></i></a-->

                                    <a  class="btn btn-danger m-2 modal-delete-trigger" data-toggle="tooltip" title="ลบใบสั่งซื้อ" data-id="{{ $row->id }}" id="{{ $row->id }}"><i aria-hidden="true" class="fas fa-trash"></i></a>

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

                        <div class="modal-body">
                        
                        <div class="msg_status" ></div>
                    
                        <div class="form-horizontal">
                            
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="English ">Order Status</label>
                                    <select class="form-control" id="status" required>
                                        <option>Select</option>
                                        <option value="0">Draft PO Created</option>
                                        <option value="1">Received PO</option>
                                        <option value="2">Send Invoice To Supplier</option>
                                        <option value="3">Supplier Dispatch Items</option>
                                        <option value="4">Order Received By Client</option>
                                        <option value="5">Invoice Issued</option>
                                        <option value="6">Order Finish</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="quantity_cont">
                                
                            </div>
                            
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" id="btn_add_feed">Add</button>
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
                    <h5 class="modal-title" id="deleteModalExample">ลบข้อมูลนี้</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">คุณแน่ใจนะว่าจะลบข้อมูลนี้?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a id="btn_delete_feed" class="btn btn-danger" >
                        Delete
                    </a>
                </div>
            </div>
        </div>
    </div>

    
    <!-- View Order Item List Modal -->

    <div class="modal fade" id="view_order_item_list_modal" tabindex="-1" role="dialog" aria-labelledby="deleteModalExample"
    aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            
                    <div class="modal-header">
                        <h5 class="modal-title  text-primary" id="deleteModalExample">สร้างคำสั่งซื้อไปยังผู้ผลิต<br>ตามรายการสินค้าที่ได้รับ</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>


                    <div class="modal-body">
                        
                        <div class="msg_status" ></div>
                        <div class="ajax_content" ></div>
                    
                        
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                    </div>


            </div>
        </div>
    </div>

    
    <!-- View Order Assign Vendor List Modal -->

    <div class="modal fade" id="assign_to_phone_vendor_modal" tabindex="-1" role="dialog" aria-labelledby="deleteModalExample"
    aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            
                    <div class="modal-header">
                        <h5 class="modal-title  text-primary" id="deleteModalExample">จัดทำใบสั่งซื้อไปยังร้านค้า</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>


                    <div class="modal-body">
                        
                        <div class="msg_status" ></div>
                        <div class="ajax_content" ></div>
                    
                        
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
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

    <script src="{{ asset('js/orders.js') }}"></script>

    @endpush

@endsection

@section('scripts')
    
@endsection

