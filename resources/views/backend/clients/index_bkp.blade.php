@extends('layouts.sidebar_app')

@section('title', $title . ' List')

@section('content')

<link rel="stylesheet" href="{{asset('dashboard-assets/libs/sweetalert2/dist/sweetalert2.min.css')}}">
<div class="container-fluid">
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">{{$title}}s</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted " href="./index.html">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">{{$title}}s</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="images/breadcrumb/ChatBc.png" alt="" class="img-fluid mb-n4">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="widget-content searchable-container list">
        <!-- --------------------- start Contact ---------------- -->
        <div class="card card-body">
            <div class="row">
                <div class="col-md-4 col-xl-3">
                    <form class="position-relative">
                        <input type="text" class="form-control product-search ps-5" id="input-search" placeholder="Search {{$title}}s..." />
                        <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                    </form>
                </div>
                <div class="col-md-8 col-xl-9 text-end d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                    <div class="action-btn show-btn" style="display: none">
                        <a href="javascript:void(0)" class="delete-multiple btn-light-danger btn me-2 text-danger d-flex align-items-center font-medium">
                            <i class="ti ti-trash text-danger me-1 fs-5"></i> Delete All Row
                        </a>
                    </div>
                    <a href="javascript:void(0)" id="addFeed" class="btn btn-info d-flex align-items-center">
                        <i class="ti ti-users text-white me-1 fs-5"></i> Add {{$title}}
                    </a>
                </div>
            </div>
        </div>

        <div class="card card-body">
            <div class="table-responsive">
                <div id="user-list">
                    <table class="table search-table align-middle text-nowrap">
                        <thead class="header-item">
                            <!--th>
                                <div class="n-chk align-self-center text-center">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input primary" id="contact-check-all" />
                                        <label class="form-check-label" for="contact-check-all"></label>
                                        <span class="new-control-indicator"></span>
                                    </div>
                                </div>
                            </th-->
                            <th>Sr No.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Company Name</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Action</th>
                        </thead>
                        <tbody>

                        @php

                        $i = ($data->perPage() * ($data->currentPage() - 1)) + 1;

                        @endphp

                        @foreach ($data as $row)
                            <tr class="search-items">
                                <!--td>
                                    <div class="n-chk align-self-center text-center">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input contact-chkbox primary" id="checkbox8" />
                                            <label class="form-check-label" for="checkbox8"></label>
                                        </div>
                                    </div>
                                </td-->
                                <td >{{ $i }}</td>
                                <td>{{ $row->first_name }}</td>
                                <td>{{ $row->email }}</td>
                                <td>{{ $row->company_name }}</td>
                                <td>{{ $row->mobile_number }}</td>
                                <td>
                                    @if ($row->status == 0)
                                        <span class="mb-1 badge bg-danger">Inactive</span>
                                    @elseif ($row->status == 1)
                                        <span class="mb-1 badge bg-success">Active</span>
                                    @endif
                                </td>
                                <td style="display: flex">
                                    
                                    <div class="action-btn">

                                    @if ($row->status == 0)
                                        <a href="{{ route('admin.clientStatusUpdate', ['user_id' => $row->id, 'status' => 1]) }}"
                                            class="btn mb-1 btn-success btn-circle btn-sm d-inline-flex align-items-center justify-content-center" 
                            data-bs-toggle="tooltip"
                            title="Active" style="margin-right: 5px;">
                                                    <i class="fa fa-check"></i>
                                                </a>
                                            @elseif ($row->status == 1)
                                                <a href="{{ route('admin.clientStatusUpdate', ['user_id' => $row->id, 'status' => 0]) }}"
                                                    class="btn mb-1 btn-danger btn-circle btn-sm d-inline-flex align-items-center justify-content-center"
                            data-bs-toggle="tooltip"
                            title="Deactive" style="margin-right: 5px;">
                                                    <i class="ti ti-ban fs-5"></i>
                                                </a>
                                            @endif

                                        <a href="javascript:void(0)" class="btn mb-1 btn-info btn-circle btn-sm d-inline-flex align-items-center justify-content-center  edit-user" data-user='@php echo json_encode($row);@endphp' style="margin-right: 5px;" data-bs-toggle="tooltip" title="Edit Detail">
                                            <i class="ti ti-pencil fs-5"></i>
                                        </a>
                                        <!--a alt="alert" class="text-danger  ms-2 img-fluid model_img" id="sa-confirm">
                                            <i class="ti ti-trash fs-5"></i>
                                        </a-->
                                    </div>
                                </td>
                            </tr>

                                @php

                                $i = $i + 1;

                                @endphp

                            @endforeach
                        </tbody>
                    </table>

                </div>

            </div>
        </div>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="addContactModal" tabindex="-1" role="dialog" aria-labelledby="addContactModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h5 class="modal-title">{{$title}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="add-contact-box">
                    <div class="add-contact-content">
                        <form id="addContactModalTitle">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3 contact-name">
                                        <input type="text" id="c-name" class="form-control" placeholder="Name" />
                                        <span class="validation-text text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 contact-email">
                                        <input type="text" id="c-email" class="form-control" placeholder="Email" />
                                        <span class="validation-text text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3 contact-occupation">
                                        <input type="text" id="Company Name" class="form-control" placeholder="Company Name" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 contact-phone">
                                        <input type="text" id="c-phone" class="form-control" placeholder="Phone" />
                                        <span class="validation-text text-danger"></span>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3 contact-Password">
                                        <input type="text" id="c-Password" class="form-control" placeholder="Password" />
                                        <span class="validation-text text-danger"></span>
                                    </div>
                                </div>

                            </div>
                            <!-- <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3 contact-location">
                            <input type="text" id="c-location" class="form-control" placeholder="Location" />
                            </div>
                        </div>
                        </div> -->
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btn-add" class="btn btn-success rounded-pill px-4">Add</button>
                <button id="btn-edit" class="btn btn-success rounded-pill px-4">Save</button>
                <button class="btn btn-danger rounded-pill px-4" data-bs-dismiss="modal"> Discard </button>
            </div>
        </div>
    </div>
</div>

<!-- Add feed Modal -->
    
<div class="modal fade" id="add_modal" tabindex="-1" role="dialog" aria-labelledby="deleteModalExample"
    aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title">Add {{$title}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            
                <form class="sp_form">

                @csrf

                    <input type="hidden" value="0" name="edit_id" id="edit_id">
                    <input type="hidden" value="0" name="previous_img_val" id="previous_img_val">

                        <div class="modal-body">
                        
                        <div class="msg_status" ></div>
                    
                        <div class="form-horizontal">

                            <div class="form-group">
                                <div class="col-md-12 mb-3">
                                    <label for="English ">Name </label>
                                    <input type="text" class="form-control" name="first_name" id="first_name" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12 mb-3">
                                    <label for="English ">email </label>
                                    <input type="email" class="form-control" name="email" id="email" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12 mb-3">
                                    <label for="English ">Phone </label>
                                    <input type="text" class="form-control" name="mobile_number" id="mobile_number" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12 mb-3">
                                    <label for="English ">Company Name </label>
                                    <input type="text" class="form-control" name="company_name" id="company_name" required>
                                </div>
                            </div>
                            
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary rounded-pill" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success rounded-pill" id="btn_add_feed">Add</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

@push('page_scripts')

<!-- Custom JS -->

<script>
    var base_url = "{{ Config::get('app.url') }}";
    var modal_title = "{{$title}}";
</script>



<!-- ---------------------------------------------- -->
<!-- current page js files -->
<!-- ---------------------------------------------- -->
<script src="{{asset('dashboard-assets/libs/sweetalert2/dist/sweetalert2.min.js')}}"></script>
<script src="{{asset('dashboard-assets/js/forms/sweet-alert.init.js')}}"></script>

<script src="https://unpkg.com/axios/dist/axios.min.js"></script>


<script>

var editFeedId = 0;
    $('#addFeed').click(function () {
        $('.msg_status').html('');
        editFeedId = 0;
        $('#add_modal .modal-title').html('Add Client');
        $('#add_modal').modal('show');
        $('#btn_add_feed').text('Add');
        $('#first_name').val('');
        $('#email').val('');
        $('#mobile_number').val('');
        $('#company_name').val('');
        $('#edit_id').val(0);
        $('#first_name').focus();
    });

    $(document).on("click", ".edit-user", function () {
        
        $('.msg_status').html('');
        
        editFeedId = $(this).data('id');
        $('#add_modal .modal-title').html('Edit Client');
        $('#add_modal').modal('show');
        $('#btn_add_feed').text('Edit');
        
        $('#first_name').val($(this).data('user').first_name);
        $('#email').val($(this).data('user').email);
        $('#mobile_number').val($(this).data('user').mobile_number);
        $('#company_name').val($(this).data('user').company_name);
        $('#edit_id').val($(this).data('user').id);
    });

    //$('#btn_add_feed').click(function () {

    $(document).on("submit",".sp_form",function(event) {

        event.preventDefault();

        $('.sp_ajax_loader').remove();

        $('#add_modal .modal-content').append('<div class="sp_custom_loader sp_ajax_loader" style="z-index: 1000; border: none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background: rgb(255, 255, 255); opacity: 0.6; cursor: default; position: absolute;"></div>');
		
        var url = base_url+'/admin/client-add';
        /* var first_name          =  $('#first_name').val();
        var email      =  $('#email ').val();
        var mobile_number                =  $('#mobile_number').val();
        var company_name             =  $('#company_name').val();
        var edit_id             =  $('#edit_id').val(); */

        var edit_id             =  $('#edit_id').val();

        if( edit_id > 0 ){

            url = base_url+'/admin/client-update';
        }

        /* var data = {
            first_name          : first_name,
            email     : email,
            mobile_number                : mobile_number,
            company_name             : company_name,
            edit_id             : edit_id,
        }; */

        var data = $('.sp_form').serialize();
        
            axios.post(url, data).then(function (response) {
                console.log(response.data+'fetch');
                if (response.data.status == true) {

                    $('.msg_status').html('<div class="alert alert-success">'+response.data.message+'</div>');

                    setTimeout(function(){location.reload();},2500);

                } else {
                    
					$('.msg_status').html('<div class="alert alert-danger">'+response.data.message+'</div>');
                }

                $('.sp_ajax_loader').remove();

            }).catch(function (error) {

                if (error == 'Error: Request failed with status code 403') {

                    $('.msg_status').html('<div class="alert alert-danger">You does not have the right permissions.</div>');

                }
				
				$('.sp_ajax_loader').remove();

            });
        
    });

    $(function () {
        // =================================
        // Tooltip
        // =================================
        var tooltipTriggerList = [].slice.call(
            document.querySelectorAll('[data-bs-toggle="tooltip"]')
        );
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

@endpush


@endsection