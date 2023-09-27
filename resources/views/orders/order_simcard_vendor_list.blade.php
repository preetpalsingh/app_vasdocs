@extends('layouts.app')

@section('title', $title . ' List')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">{{$title}}s</h1>
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
                <h6 class="m-0 font-weight-bold text-primary">All {{$title}}s</h6>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                            <th class="text-center">No.</th>
                            <th class="text-center">Order ID</th>
                            <th class="text-center">Simcard</th>
                            <th class="text-center">Quantity</th>
                            <!--th class="text-center">Status</th-->
                            <th class="text-center">Date</th>  
                            <!--th class="text-center">Actions</th-->
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $key => $row)

                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td class="text-center">{{ $row->order_id }}</td>      
                                <td class="text-center">{{ $row->title }}</td>   
                                <td class="text-center">{{ $row->order_qty }}</td>

                                <!--td class="text-center">{{ $row->status }}</td--> 
                                <td class="text-center">{{ $row->created_at }}</td>
                                <!--td class="text-center text-red h4">

                                @hasrole('Admin')

                                    <a data-id="{{ $row->id }}" class="btn btn-primary m-2 assign_to_phone_vendor" data-toggle="tooltip" data-original-title="Order Assign To Phone Vendor"><i class="fas fa-user-plus"></i></a>

                                    <a href="{{ route('admin.vieworderUQ') }}" target="_blank" data-id="{{ $row->id }}" class="btn btn-warning m-2 " data-toggle="tooltip" data-original-title="View Union Quotation"><i class="fas fa-print"></i></a>

                                    <!--a class="btn btn-primary m-2 edit-user" data-toggle="tooltip" title="Edit Product" data-user='<?php echo json_encode($row);?>'><i aria-hidden="true" class="fa fa-pen"></i></a-->
                                    
                                    <!--a  class="btn btn-danger m-2  delete-feed" data-id="{{ $row->id }}"><i aria-hidden="true" class="fas fa-trash"></i></a-->

                                    <a  class="btn btn-danger m-2 modal-delete-trigger" data-toggle="tooltip" title="Delete Product" data-id="{{ $row->id }}" id="{{ $row->id }}"><i aria-hidden="true" class="fas fa-trash"></i></a>

                                @endhasrole

                                </td-->
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
                                    <label for="English ">Phone Vendor</label>
                                    <select class="form-control" id="monthly_price" required>
                                        <option>Select</option>
                                        <option value="4">Vendor 1</option>
                                        <option value="5">Vendor 2</option>
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

    
    <!-- View Order Assign Vendor List Modal -->

    <div class="modal fade" id="assign_to_phone_vendor_modal" tabindex="-1" role="dialog" aria-labelledby="deleteModalExample"
    aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            
                    <div class="modal-header">
                        <h5 class="modal-title  text-primary" id="deleteModalExample">View Order Assign Vendor List</h5>
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

