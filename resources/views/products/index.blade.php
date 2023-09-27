@extends('layouts.app')

@section('title', $title . ' List')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">{{$title}}s Catalogue Management<br>ระบบจัดการสินค้าประเภทโทรศัพท์</h1>
            <div class="row">
                <div class="col-md-12">
				@hasrole('Admin')
                    <a id="addFeed" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> เพิ่มรายการสินค้า
                    </a>
				@endhasrole	
                </div>
                
            </div>

        </div>

        {{-- Alert Messages --}}
        @include('common.alert')

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">รายการสินค้าทั้งหมด | All {{$title}}s</h6>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                            <th class="text-center">No.</th>
                            <th class="text-center">รูปภาพสินค้า<br>Image</th>
                            <th class="text-center">ชื่อสินค้า<br>Title</th>
                            <th class="text-center">Link Spec</th>

                            @hasrole('Admin')

                            <th class="text-center">ราคาขาย <br>Retail Price</th>
                            <th class="text-center">Update วันที่</th>   
                            
                            @endhasrole

                            @hasrole('Vendor')

                            <th class="text-center">ราคาเสนอ (Price)</th>
                            <th class="text-center">จำนวน Stock</th>

                            @endhasrole

                            <th class="text-center">หมวดคำสั่ง <br>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $key => $row)

                        @php

                            if ( empty( $row->sale_price ) ) {

                                $row->sale_price = 0;

                            }

                            if (empty( $row->stock ) ) {

                                $row->stock = 0;

                            }

                        @endphp
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td class="text-center">
                                
                                    <a href="{{ $row->image }}" target="blank">
                                    <img src="{{ $row->image }}" height="150" width="150" class="img-thumbnail">
                                    
                                    </a>
                                </td> 
                                <td class="text-center">{{ $row->title }}</td> 							
                                <td class="text-center">{{ Str::limit($row->link_spec, 50) }}	</td>
                                
                                @hasrole('Admin')

                                <td class="text-center">{{ number_format($row->price, 2) }}</td> 
                                <td class="text-center">{{ $row->updated_at }}</td>
                                
                                @endhasrole

                                @hasrole('Vendor')

                                <td class="text-center">{{ number_format($row->sale_price, 2) }}</td> 
                                <td class="text-center">{{ $row->stock }}</td> 

                                @endhasrole
                                
                                
                                <td class="text-center text-red h4">

                                @hasrole('Admin')

                                    <a class="btn btn-warning m-2 view-price-stock" data-toggle="tooltip" title="ดูราคาขาย และสินค้าใน Stock ร้านค้า" data-user='<?php echo json_encode($row);?>' data-id="{{ $row->id }}"><i aria-hidden="true" class="fa fa-eye"></i></a>

                                    <a class="btn btn-primary m-2 edit-user" data-toggle="tooltip" title="แก้ไขข้อมูล" data-user='<?php echo json_encode($row);?>'><i aria-hidden="true" class="fa fa-pen"></i></a>
                                    
                                    <!--a  class="btn btn-danger m-2  delete-feed" data-id="{{ $row->id }}"><i aria-hidden="true" class="fas fa-trash"></i></a-->

                                    <a  class="btn btn-danger m-2 modal-delete-trigger" data-toggle="tooltip" title="ลบรายการสินค้า" data-id="{{ $row->id }}" id="{{ $row->id }}"><i aria-hidden="true" class="fas fa-trash"></i></a>

                                @endhasrole

                                @hasrole('Vendor')

                                    <a class="btn btn-primary m-2 edit-price-stock" data-toggle="tooltip" title="แก้ไขราคา และข้อมูล" data-user='<?php echo json_encode($row);?>'><i aria-hidden="true" class="fa fa-pen"></i></a>

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
                                    <label for="English ">ชื่อสินค้า</label>
                                    <input type="text" class="form-control" id="title" value="" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="English ">ราคาขาย </label>
                                    <input type="number" step="any" class="form-control" id="price" value="" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="English ">Link Spec </label>
                                    <input type="url" step="any" class="form-control" id="link_spec" value="" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="English ">รูปภาพ Jpg,png ขนาด 900x900 px</label>
                                    <input type="hidden" id="img_val">
                                    <input type="file" class="form-control" id="img_url" accept="image/gif, image/jpeg, image/png">
                                </div>
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

    
    <!-- Edit price and stock by vendor  Modal -->
    
    <div class="modal fade" id="edit_vendor_modal" tabindex="-1" role="dialog" aria-labelledby="deleteModalExample"
    aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            
                <form class="sp_form_edit_price_stock">

                    <div class="modal-header">
                        <h5 class="modal-title  text-primary" id="deleteModalExample">ปรับราคา และ Stock</h5>
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
                                    <label for="English ">ราคาขายให้กับ STR (บาท)</label>
                                    <input type="number" step="any" class="form-control" id="sale_price" value="" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="English ">Stock</label>
                                    <input type="number" class="form-control" id="stock" value="" required>
                                </div>
                            </div>
                            
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-success" id="btn_add_feed">Update</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    
    <!-- View price and stock by vendor Modal -->

    <div class="modal fade" id="viewt_vendor_modal" tabindex="-1" role="dialog" aria-labelledby="deleteModalExample"
    aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            
                    <div class="modal-header">
                        <h5 class="modal-title  text-primary" id="deleteModalExample">ราคาสินค้าของร้านค้า และ จำนวน Stock</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>


                    <div class="modal-body">
                        
                        <div class="msg_status" ></div>
                    
                        
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

    <script src="{{ asset('js/products.js') }}"></script>

    @endpush

@endsection

@section('scripts')
    
@endsection

