@extends('layouts.app')

@section('title', $title . ' List')

@section('content')

<style>
    
      
    
</style>

<link rel="stylesheet" href="{{asset('dashboard-assets/libs/sweetalert2/dist/sweetalert2.min.css')}}">
<div class="container-fluid">
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">{{$title}}s</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted " href="#">Dashboard</a></li>
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
                        <input type="text" class="form-control product-search ps-5" id="search_records" placeholder="Search {{$title}}s..." />
                        <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                    </form>
                </div>
                <div class="col-md-8 col-xl-9 text-end d-flex justify-content-md-between  justify-content-between mt-3 mt-md-0">
                   
                    <a href="javascript:void(0)"  class="btn btn-info d-flex align-items-center float-start sp_reset" data-bs-toggle="tooltip" title="Reset Search">
                        <i class="ti ti-refresh text-white me-1 fs-5"></i> Reset
                    </a>

                    @if( Auth::user()->role_id == 1 || Auth::user()->role_id == 5 )

                    <a href="javascript:void(0)" id="addFeed" class="btn btn-info d-flex align-items-center">
                        <i class="ti ti-plus text-white me-1 fs-5"></i> Add {{$title}}
                    </a>

                    @endif

                </div>
            </div>
        </div>

        {{-- Alert Messages --}}
        @include('common.alert')

        <div class="card card-body" style="position: relative;min-height: 220px;">
            <div class="table-responsive" style="">
            <div id="sp_preloader" style="display: none;"><div class="shapes-8"></div></div>
                <div id="user-list">
                    

                </div>

            </div>
        </div>
        
    </div>
</div>



<!-- Add Modal -->
    
<div class="modal fade" id="add_modal" tabindex="-1" role="dialog" aria-labelledby="deleteModalExample"
    aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title">Add Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            
                <form class="sp_form">

                @csrf

                    <input type="hidden" value="0" name="edit_id" id="edit_id">
                    <input type="hidden" value="0" name="previous_img_val" id="previous_img_val">

                        <div class="modal-body">
                        
                        <div class="msg_status" ></div>
                    
                        <div class="form-horizontal"><div class="row">
                                
                            <div class="form-group">
                                <div class="col-md-12 mb-3">
                                    <label for="English ">Date</label>
                                    <input type="date" id="date" name="date" class="form-control singledate" placeholder="Date" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12 mb-3">
                                    <label for="English ">Title</label>
                                    <input type="text" id="title" name="title" class="form-control" placeholder="Title" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12 mb-3">
                                    <label for="English ">Description </label>
                                    <textarea type="text" class="form-control" name="description" id="description" ></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12 mb-3">
                                    <label for="English ">Status </label>
                                    <select class="form-select col-12 " name="status" id="status" required>
                                            <option value="Pending">Pending</option>
                                            <option value="Under Review">Under Review</option>
                                            <option value="Complete">Complete</option>
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                    </div>

                        </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary rounded-pill"  data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success rounded-pill" id="btn_add_feed">Add</button>
                    </div>

                </form>

            </div>
        </div>
    </div>


    <!-- view Modal -->
    
<div class="modal fade" id="view_modal" tabindex="-1" role="dialog" aria-labelledby="deleteModalExample"
    aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title">View Task Detail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            

                        <div class="modal-body">
                        
                        <div class="col-md-12 sp_view_text">
                                
                                       </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary rounded-pill"  data-bs-dismiss="modal">Close</button>
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


<script>

    $(document).ready(function() {
    
        // Show loader before the request starts
        showLoader();

        sp_load_records();

        var typingTimer;
        var doneTypingInterval = 500; // milliseconds

        $('#search_records').on('input', function() {

            // Show loader before the request starts
            showLoader();

            clearTimeout(typingTimer);
            var query = $(this).val();

            if (query.length >= 3) {
                
                typingTimer = setTimeout(function() {
                    sp_load_records(query);
                }, doneTypingInterval);

            } else{

                hideLoader();

            }
        });

        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            // Show loader before the request starts
            showLoader();
        
            var page = $(this).attr('href').split('page=')[1];
            sp_load_records($('#search_records').val(), page);
        });

        
    });



    $(document).on('click', '.sp_reset', function(e) {

        $('#search_records').val('');

        // Show loader before the request starts
        showLoader();

        sp_load_records();

    });

    function showLoader() {

        $('#sp_preloader').show();

    }

    function hideLoader() {
        
        $('#sp_preloader').hide();
    }

    function sp_load_records(query, page) {

        $.ajax({
            url: base_url+'/admin/developer-search',
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { query: query, page: page },
            before:{  },
            success: function(response) {
                //setTimeout(function() {
            
                    hideLoader();
                    $('#user-list').html(response.view);
                    $('#pagination').html(response.pagination);

                    // =================================
                    // Tooltip
                    // =================================
                    var tooltipTriggerList = [].slice.call(
                        document.querySelectorAll('.sp_ajax_tooltip')
                    );
                    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                    });

                //}, 600);
                
            }
        });
    }

    var editFeedId = 0;
    $('#addFeed').click(function () {
        $('.msg_status').html('');
        editFeedId = 0;
        $('#add_modal .modal-title').html('Add Task');
        $('#add_modal').modal('show');
        $('#btn_add_feed').text('Add');
        $('#date').val('');
        $('#title').val('');
        $('#description').html('');
        $('#status').val('Pending');
        $('#edit_id').val(0);
        $('#date').focus();
    });
    
    $(document).on("click", ".edit-user", function () {
        
        $('.msg_status').html('');
        
        editFeedId = $(this).data('id');
        $('#add_modal .modal-title').html('Edit Task');
        $('#add_modal').modal('show');
        $('#btn_add_feed').text('Edit');
        
        $('#date').val($(this).data('user').date);
        $('#title').val($(this).data('user').title);

        $('#description').html($(this).data('desc'));
        $('#status').val($(this).data('user').status);
        $('#edit_id').val($(this).data('user').id);

    });

    $(document).on("click", ".sp_view_detail", function () {

        $('#view_modal').modal('show');

        var html = $(this).data('desc').replace(/\n/g, '<br>');

        $('#view_modal .sp_view_text').html(html);

    });

    //$('#btn_add_feed').click(function () {

    $(document).on("submit",".sp_form",function(event) {

        event.preventDefault();

        $('.sp_ajax_loader').remove();

        $('#add_modal .modal-content').append('<div class="sp_custom_loader sp_ajax_loader" style="z-index: 1000; border: none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background: rgb(255, 255, 255); opacity: 0.6; cursor: default; position: absolute;"></div>');
		
        var url = base_url+'/admin/developer-add';
        /* var first_name          =  $('#first_name').val();
        var email      =  $('#email ').val();
        var mobile_number                =  $('#mobile_number').val();
        var company_name             =  $('#company_name').val();
        var edit_id             =  $('#edit_id').val(); */

        var edit_id             =  $('#edit_id').val();

        if( edit_id > 0 ){

            url = base_url+'/admin/developer-update';
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

                    //setTimeout(function(){location.reload();},2500);

                    setTimeout(function(){
                        
                        $('#add_modal').modal('hide');

                    },2000);

                    setTimeout(function(){

                        // Show loader before the request starts
                        showLoader();

                        var page = $('.pagination .active .page-link').html();
                        sp_load_records($('#search_records').val(), page);

                    },2500);

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

    

    $(document).on("click", ".modal-delete-trigger", function(event) {

        event.stopPropagation();
        delete_id = $(this).attr('id');


        swal({
            title: "Delete Permanently",
            text: "Are you Sure You wanted to Delete?",
            icon: "warning",
            buttons: {
                cancel: 'No, cancel please!',
                confirm: {
                    text: 'Yes, delete it!',
                    className: 'sweet-warning',
                    closeModal: false,
                }
            },
            dangerMode: true,
        })
        .then((willDelete) => {

            if (willDelete) {

                $(".sa-confirm-button-container .confirm").prop('disabled', true);

                var url = base_url + '/admin/developer-delete';

                var FormData = {
                    delete_id: delete_id
                };

                datastring = 'delete_id=' + delete_id;
                $.ajax({
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    data: datastring,

                    beforeSend: function() {
                        $(".sa-confirm-button-container .confirm").prop('disabled', true);

                        $('.sa-confirm-button-container .confirm').html('<center><i class="fa fa-refresh fa-spin  fa-fw"></i> Wait...</center>');

                    },

                    success: function(data) {

                        //var obs = $.parseJSON(data);
                        var obs = data;

                        if (obs['status'] == true) {
                            swal("Deleted!", 'Record Delete Successfully !', "success");

                            setTimeout(function() {

                                swal.close();

                                // Show loader before the request starts
                                showLoader();

                                var page = $('.pagination .active .page-link').html();
                                sp_load_records($('#search_records').val(), page);

                            }, 2500);

                        } else {

                            swal("Cancelled", 'Technical Error !', "error");
                        }

                    },

                    error: function(data, textStatus, errorThrown) {
                        if (data.status == '403') {

                            swal("Cancelled", 'You does not have the right permissions.', "error");
                        }
                    }
                });

                return false;

            }
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