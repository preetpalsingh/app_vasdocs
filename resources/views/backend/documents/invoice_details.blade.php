@extends('layouts.app')

@section('title', $title . ' List')

@section('content')

<style>
    /* Rest of your existing CSS styles */
    
.upload-files-container {
	padding: 5px 60px;
	border-radius: 40px;
	display: flex;
   	align-items: center;
   	justify-content: center;
	flex-direction: column;
}
.drag-file-area {
	border: 2px dashed #539BFF;
	border-radius: 40px;
	margin: 10px 0 15px;
	padding: 30px 50px;
	width: 350px;
	text-align: center;
}
.drag-file-area .upload-icon {
	font-size: 50px;
}
.drag-file-area h3 {
	font-size: 26px;
	margin: 15px 0;
}
.drag-file-area label {
	font-size: 19px;
}
.drag-file-area label .browse-files-text {
	color: #539BFF;
	font-weight: bolder;
	cursor: pointer;
}
.browse-files span {
	position: relative;
	top: -25px;
}
.default-file-input {
	opacity: 0;
}
.cannot-upload-message {
	background-color: #ffc6c4;
	font-size: 17px;
	display: flex;
	align-items: center;
	margin: 5px 0;
	padding: 5px 10px 5px 30px;
	border-radius: 5px;
	color: #BB0000;
	display: none;
}
@keyframes fadeIn {
  0% {opacity: 0;}
  100% {opacity: 1;}
}
.cannot-upload-message span, .upload-button-icon {
	padding-right: 10px;
}
.cannot-upload-message span:last-child {
	padding-left: 20px;
	cursor: pointer;
}
.file-block {
	color: #f7fff7;
	background-color: #539BFF;
  	transition: all 1s;
	width: 390px;
	position: relative;
	display: none;
	flex-direction: row;
	justify-content: space-between;
	align-items: center;
	margin: 10px 0 15px;
	padding: 10px 20px;
	border-radius: 25px;
	cursor: pointer;
}
.file-info {
	display: flex;
	align-items: center;
	font-size: 15px;
    flex-direction: row;
    justify-content: space-between;
    width: 100%;
}
.file-icon {
	margin-right: 10px;
}
.file-name, .file-size {
	padding: 0 3px;
}
.remove-file-icon {
	cursor: pointer;
}
.progress-bar {
	display: flex;
	position: absolute;
	bottom: 0;
	left: 4.5%;
	width: 0;
	height: 5px;
	border-radius: 25px;
	background-color: #4BB543;
}
.upload-files-container label.label {
    width: 100%;
}

th.sortable::after {
    content: ' ⇅';
}

th.sortable {
    cursor: pointer;
}


</style>

<div class="container-fluid">
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">

                        @if( !empty( $doc_user_name ) )

                            {{$doc_user_name}}

                        @else

                            {{$title}}s
                        

                        @endif
                    <!-- {{$title}}s -->
                
                    </h4>

                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted " href="#">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Clients</li>
                            <li class="breadcrumb-item" aria-current="page">{{$title}}s</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <!--  <img src="images/breadcrumb/ChatBc.png" alt="" class="img-fluid mb-n4">
                <img src="images/breadcrumb/agrovista_invoice.jpeg" alt="" class="img-fluid mb-n4"> -->
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
                <div class="col-md-8 col-xl-9 text-end d-flex  mt-3 mt-md-0">
                    <div class="action-btn show-btn" style="display: none">
                        <a href="javascript:void(0)" class="delete-multiple btn-light-danger btn me-2 text-danger d-flex align-items-center font-medium">
                            <i class="ti ti-trash text-danger me-1 fs-5"></i> Delete All Row
                        </a>
                    </div>
                    <a href="javascript:void(0)"  class="btn btn-success d-flex align-items-center float-start sp_reset" data-bs-toggle="tooltip" title="Reset Search" data-trigger="hover">
                        <i class="ti ti-refresh text-white me-1 fs-5"></i> Reset
                    </a>

                    <div class="w-100 d-flex justify-content-md-end  justify-content-end">

                        @if( Auth::user()->role_id == 1 || Auth::user()->role_id == 4 )

                            <form action="" class="sp_doc_status_form" method="POST">

                                <input type="hidden" name="multi_doc_ids" id="multi_doc_ids" />

                            </form>

                            <select class="form-select sp_select_hide_cont" name="status" id="sp_doc_status" style="width: 150px;margin-right: 15px;display:none;" >
                                <option value="">Select Status</option>
                                <option value="Processing">Processing</option>
                                <option value="Review">Reviewed</option>
                                <option value="Ready">Ready</option>
                                <option value="Archive" >Archive</option>
                            </select>
                            
                            <!--a href="javascript:void(0)"  class="btn btn-success align-items-center me-3 sp_select_hide_cont" style="display:none;" data-bs-toggle="tooltip" title="Export Zip {{$title}}">
                                <i class="ti ti-file-export text-white me-1 fs-5"></i> Export Zip
                            </a-->

                        @endif


                        <a href="javascript:void(0)" id="show_export" class="btn btn-success d-flex align-items-center me-3" data-bs-toggle="tooltip" title="Export Excel {{$title}}">
                            <i class="ti ti-file-export text-white me-1 fs-5"></i> Export
                        </a>

                        <a href="javascript:void(0)" id="addFeed" class="btn btn-info d-flex align-items-center" data-bs-toggle="tooltip" title="Add {{$title}}">
                            <i class="ti ti-clipboard-plus text-white me-1 fs-5"></i> Add
                        </a>

                    </div>

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


                <input type="hidden" name="sp_sort_filed_name" id="sp_sort_filed_name" value="id">
                <input type="hidden" name="sp_sort_filed_direction" id="sp_sort_filed_direction" value="DESC">
            </div>
        </div>

    </div>
</div>

<!-- --------------------- start Contact ---------------- -->

</div>
</div>


<!-- Export Modal -->
    
<div class="modal fade" id="export_modal" role="dialog" aria-labelledby="deleteModalExample"
    aria-hidden="true" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title">Export  Excel {{$title}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            
                <form class="" action="{{route('admin.invoiceExport')}}" method="post">

                @csrf

                        <div class="modal-body">
                        
                        <div class="msg_status" ></div>
                    
                        <div class="form-horizontal">

                            <div class="col-md-12">
                                <div class="mb-3 contact-occupation">
                                    <label for="English ">Start Date</label>
                                    <input type="text" id="start_date" name="start_date" class="form-control singledate" placeholder="Start Date" required />
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="mb-3 contact-phone">
                                    <label for="English ">End Date</label>
                                    <input type="text" id="end_date" name="end_date" class="form-control singledate" placeholder="End Date" required />
                                    <span class="validation-text text-danger"></span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3 contact-phone">
                                    <label for="English ">Status</label>
                                    <select class="form-select col-12" name="status" id="status" >
                                        <option value="" >Select</option>
                                        <option value="Processing" >Processing</option>
                                        <option value="Review" >Reviewed</option>
                                        <option value="Ready" >Ready</option>
                                        <option value="Archive" >Archive</option>
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary rounded-pill"  data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success rounded-pill upload-button" id="btn_export">Export</button>
                    </div>

                </form>

	


            </div>
        </div>
    </div>


<!-- Add Modal -->
    
<div class="modal fade" id="add_modal" tabindex="-1" role="dialog" aria-labelledby="deleteModalExample"
    aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title">Add {{$title}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            
                <form class="sp_form" method="post">

                @csrf

                    <input type="hidden" value="0" name="edit_id" id="edit_id">
                    <input type="hidden" value="0" name="previous_img_val" id="previous_img_val">

                        <div class="modal-body">
                        
                        <div class="msg_status" ></div>
                    
                        <div class="form-horizontal">

                        @if( Auth::user()->role_id == 1 || Auth::user()->role_id == 4 )

                            <div class="form-group">
                                <div class="col-md-12 mb-3">
                                    <label for="English ">Clients </label>
                                    <select class="form-select col-12" name="user_id" id="user_id" required>
                                        <option value="">Choose...</option>
                                        @foreach ( $clients as $client )

                                        @php

                                        $selected = '';

                                        if( isset( $doc_user_id ) ){

                                            if( $client->id == $doc_user_id ){

                                                $selected = 'selected';
                                            }

                                        }

                                        @endphp

                                            <option value="{{ $client->id }}" {{ $selected }}>{{ $client->company_name }}</option>

                                        @endforeach

                                    </select>
                                </div>
                            </div>

                            @endif

                            <div class="form-group">
                                <div class="col-md-12 mb-3">
                                    <label for="English ">Document </label>
                                    <div class="upload-files-container">
                                <div class="drag-file-area">
                                <i class="ti ti-upload fs-8 upload-icon text-info"></i>
                                    <h3 class="dynamic-message"> Drag & drop any file here </h3>
                                    <label class="label"> or <span class="browse-files"> <input type="file" name="file" class="default-file-input" required /> <span class="browse-files-text">browse file</span> <span>from device</span> </span> </label>
                                </div>
                                <span class="cannot-upload-message"> <span class="material-icons-outlined">error</span> Please select a file first <span class="material-icons-outlined cancel-alert-button">cancel</span> </span>
                                <div class="file-block">
                                    <div class="file-info"> <span class="file-name"> </span><span class="file-size">  </span> </div>
                                    <!--span class="material-icons remove-file-icon">delete</span-->
                                    <div class="progress-bar"> </div>
                                </div>
                            </div>
                                    <!--input type="file" class="form-control" name="file" id="file" required -->
                                </div>
                            </div>

                            
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary rounded-pill"  data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success rounded-pill upload-button" id="btn_add_feed">Upload</button>
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

    
    <!-- ---------------------------------------------- -->
    <!-- daterange js and css files -->
    <!-- ---------------------------------------------- -->
    
    <link rel="stylesheet" href="{{asset('dashboard-assets/libs/daterangepicker/daterangepicker.css')}}">

    <script src="{{asset('dashboard-assets/libs/bootstrap-material-datetimepicker/node_modules/moment/moment.js')}}"></script>
    <script src="{{asset('dashboard-assets/libs/daterangepicker/daterangepicker.js')}}"></script>

    <!-- ---------------------------------------------- -->
    <!-- current page js files -->
    <!-- ---------------------------------------------- -->
    <script src="{{asset('dashboard-assets/libs/sweetalert2/dist/sweetalert2.min.js')}}"></script>
    <script src="{{asset('dashboard-assets/js/forms/sweet-alert.init.js')}}"></script>
    <script src="{{asset('dashboard-assets/js/bootstrap-notify.min.js')}}"></script>


<script>

$(".singledate").daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        locale: {
            format: "DD-MM-YYYY",
        }
        });

    /*******************************************/
    // Single Date Range Picker
    /*******************************************/
    

    $('#show_export').click(function () {
        $('#export_modal').modal('show');
        
    });

    // check all

    

    $(document).on('click', '#contact-check-all', function(e) {

        // Get its current checked state
        var isChecked = $(this).prop("checked");

        $('.sp_chkbox').prop('checked', isChecked);

        if( isChecked ){

            $('.sp_select_hide_cont').fadeIn('fast');

            var checkedIds = $('input.sp_chkbox[type="checkbox"]:checked').map(function () {
                return this.id;
            }).get().join(',');

            $('#multi_doc_ids').val(checkedIds);

        } else {

            $('.sp_select_hide_cont').fadeOut('fast');

            $('#multi_doc_ids').val('');
        }

    });

    $(document).on('change', '.sp_chkbox', function(e) {

        var checkedIds = $('input.sp_chkbox[type="checkbox"]:checked').map(function () {
            return this.id;
        }).get().join(',');

        $('#multi_doc_ids').val(checkedIds);

        if( checkedIds != '' ){

            $('.sp_select_hide_cont').fadeIn('fast');

        } else {

            $('.sp_select_hide_cont').fadeOut('fast');
        }

    });

    // multi select form submit for status update

    $(document).on('change', '.sp_doc_status', function(e) {
        $('#sp_doc_status_form').submit();
    });

    var editFeedId = 0;
    $('#addFeed').click(function () {
        $('.msg_status').html('');
        editFeedId = 0;
        $('#add_modal').modal('show');
        $('#btn_add_feed').text('Upload');
        $('#first_name').focus();

        $(".dynamic-message").html(' Drag & drop any file here ');
        $(".file-block").attr('style' , '');
    });

    $(document).ready(function() {
    var isAdvancedUpload = function() {
    var div = document.createElement('div');
    return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
    }();

    let draggableFileArea = document.querySelector(".drag-file-area");
    let browseFileText = document.querySelector(".browse-files");
    let uploadIcon = document.querySelector(".upload-icon");
    let dragDropText = document.querySelector(".dynamic-message");
    //let fileInput = document.querySelector(".default-file-input");
    let fileInput = $(".default-file-input")[0];
    let cannotUploadMessage = document.querySelector(".cannot-upload-message");
    let cancelAlertButton = document.querySelector(".cancel-alert-button");
    let uploadedFile = document.querySelector(".file-block");
    let fileName = document.querySelector(".file-name");
    let fileSize = document.querySelector(".file-size");
    let progressBar = document.querySelector(".progress-bar");
    //let removeFileButton = document.querySelector(".remove-file-icon");
    let uploadButton = document.querySelector(".upload-button");
    let fileFlag = 0;

    $(document).on('click', '.default-file-input', function(e) {
        //$(".default-file-input").val('');
        console.log(fileInput.value+'ddddd');
    });

    $(document).on('change', '.default-file-input', function(e) {
        console.log(" > " + fileInput.value)
        uploadIcon.innerHTML = 'check_circle';
        dragDropText.innerHTML = 'File Dropped Successfully!';
        //document.querySelector(".label").innerHTML = `drag & drop or <span class="browse-files"> <input type="file" name="file" class="default-file-input" style=""/> <span class="browse-files-text" style="top: 0;"> browse file</span></span>`;
        uploadButton.innerHTML = `Upload`;
        fileName.innerHTML = fileInput.files[0].name;
        fileSize.innerHTML = (fileInput.files[0].size/1024).toFixed(1) + " KB";
        uploadedFile.style.cssText = "display: flex;";
        progressBar.style.width = 0;
        fileFlag = 0;
    }); 

    cancelAlertButton.addEventListener("click", () => {
        cannotUploadMessage.style.cssText = "display: none;";
    });

    if(isAdvancedUpload) {
        ["drag", "dragstart", "dragend", "dragover", "dragenter", "dragleave", "drop"].forEach( evt => 
            draggableFileArea.addEventListener(evt, e => {
                e.preventDefault();
                e.stopPropagation();
            })
        );

        ["dragover", "dragenter"].forEach( evt => {
            draggableFileArea.addEventListener(evt, e => {
                e.preventDefault();
                e.stopPropagation();
                uploadIcon.innerHTML = 'file_download';
                dragDropText.innerHTML = 'Drop your file here!';
            });
        });

        /* draggableFileArea.addEventListener("drop", e => {
            uploadIcon.innerHTML = 'check_circle';
            dragDropText.innerHTML = 'File Dropped Successfully!';
            document.querySelector(".label").innerHTML = `drag & drop or <span class="browse-files"> <input type="file" name="file" class="default-file-input" style=""/> <span class="browse-files-text" style="top: -23px; left: -20px;"> browse file</span> </span>`;
            uploadButton.innerHTML = `Upload`;
            
            let files = e.dataTransfer.files;
            fileInput.files = files;
            console.log(files[0].name + " " + files[0].size);
            console.log(document.querySelector(".default-file-input").value);
            fileName.innerHTML = files[0].name;
            fileSize.innerHTML = (files[0].size/1024).toFixed(1) + " KB";
            uploadedFile.style.cssText = "display: flex;";
            progressBar.style.width = 0;
            fileFlag = 0;

            // Get the dropped file
            var file = e.dataTransfer.files[0];

            // Update the file input's value
            $('.default-file-input').prop('files', [file]);

            console.log("Droop" + fileInput.value)
        }); */

        draggableFileArea.addEventListener("drop", e => {
    uploadIcon.innerHTML = 'check_circle';
    dragDropText.innerHTML = 'File Dropped Successfully!';
    document.querySelector(".label").innerHTML = `drag & drop or <span class="browse-files"> <input type="file" name="file" class="default-file-input" style=""/> <span class="browse-files-text" style="top: -23px; left: -20px;"> browse file</span> </span>`;
    uploadButton.innerHTML = `Upload`;

    // Get the dropped files
    let files = e.dataTransfer.files;

    // Set the files property of the file input
    $('.default-file-input').prop('files', files);

    fileName.innerHTML = files[0].name;
    fileSize.innerHTML = (files[0].size / 1024).toFixed(1) + " KB";
    uploadedFile.style.cssText = "display: flex;";
    progressBar.style.width = 0;
    fileFlag = 0;
});
    }

});

    /* removeFileButton.addEventListener("click", () => {
        uploadedFile.style.cssText = "display: none;";
        fileInput.value = '';
        uploadIcon.innerHTML = 'file_upload';
        dragDropText.innerHTML = 'Drag & drop any file here';
        document.querySelector(".label").innerHTML = `or <span class="browse-files"> <input type="file" class="default-file-input"/> <span class="browse-files-text">browse file</span> <span>from device</span> </span>`;
        uploadButton.innerHTML = `Upload`;
    }); */

    function checkall(clickchk, relChkbox) {
      var checker = $("#" + clickchk);
      var multichk = $("." + relChkbox);
  
      checker.click(function () {
        multichk.prop("checked", $(this).prop("checked"));
        $(".show-btn").toggle();
      });
    }

    

    checkall("contact-check-all", "contact-chkbox");

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

    $(document).on('click', 'body', function(e) {
        $('[data-bs-toggle="tooltip"]').tooltip('hide');
    });

    $(document).on('click', '.sp_reset', function(e) {


        $('#search_records').val('');

        // Show loader before the request starts
        showLoader();

        sp_load_records();

    });

    
     
    $(document).on('click', 'th.sortable', function(e) {

        var direction = ( $(this).attr('data-direction') == 'ASC' ) ? 'DESC' : 'ASC';

        console.log(direction);

        $(this).attr('data-direction', direction);

        $('#sp_sort_filed_name').val( $(this).data('val') );
        $('#sp_sort_filed_direction').val( $(this).data('direction') );

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

    @if( empty($doc_status) )

    function sp_load_records(query, page) {

        var sp_sort_filed_name = $('#sp_sort_filed_name').val();
        var sp_sort_filed_direction = $('#sp_sort_filed_direction').val();

        console.log(sp_sort_filed_name);
        console.log('hiittt');

        $.ajax({
            url: base_url+'/admin/invoice-search',
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { query: query, page: page, sp_sort_filed_name: sp_sort_filed_name, sp_sort_filed_direction: sp_sort_filed_direction },
            before:{  },
            success: function(response) {
                setTimeout(function() {
            
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
                        return new bootstrap.Tooltip(
                            tooltipTriggerEl, {
                            trigger : 'hover'
                        });
                    });

                }, 600);
                
            }
        });
    }

    @else

    function sp_load_records(query, page) {

        var doc_status = '{{$doc_status}}';
        var doc_user_id = '{{$doc_user_id}}';
        var sp_sort_filed_name = $('#sp_sort_filed_name').val();
        var sp_sort_filed_direction = $('#sp_sort_filed_direction').val();

        console.log(sp_sort_filed_name);
        console.log('hiittt');

        $.ajax({
            url: base_url+'/admin/invoice-search-by-status',
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { query: query, page: page, doc_status: doc_status, doc_user_id: doc_user_id, sp_sort_filed_name: sp_sort_filed_name, sp_sort_filed_direction: sp_sort_filed_direction },
            before:{  },
            success: function(response) {
                setTimeout(function() {
            
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
                        return new bootstrap.Tooltip(
                            tooltipTriggerEl, {
                            trigger : 'hover'
                        });
                    });

                }, 600);
                
            }
        });
    }

    @endif

    $(document).on("submit",".sp_form",function(event) {

        event.preventDefault();

        $('.sp_ajax_loader').remove();

        $('#add_modal .modal-content').append('<div class="sp_custom_loader sp_ajax_loader" style="z-index: 1000; border: none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background: rgb(255, 255, 255); opacity: 0.6; cursor: default; position: absolute;"></div>');
		
        var url = base_url+'/admin/invoice-add';

        var edit_id             =  $('#edit_id').val();

        var user_id           = $('#user_id').val();

        var previous_img_val = $('#previous_img_val').val();
        var data = {
            user_id: user_id,
            previous_img_val: previous_img_val,
            file: $('.default-file-input').val()
        };

        var data = new FormData(this);
        
            axios.post(url, data).then(function (response) {
                //console.log(response.data+'fetch');
                if (response.data.status == true) {

                    $('.msg_status').html('<div class="alert alert-success">'+response.data.message+'</div>');

                    //setTimeout(function(){location.reload();},2500);

                    //setTimeout(function(){
                        
                        //$('#add_modal').modal('hide');
                        
                        $(".dynamic-message").html(' Drag & drop any file here ');
                        $(".file-block").attr('style' , '');

                    //},2000);

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

// Archive Document 

$(document).on('click', '.sp_put_document_to_archive', function(e) {

    // Show loader before the request starts
    showLoader();

    var edit_id = $(this).attr('data-id');

    $.ajax({
        url: base_url+'/admin/invoice-update-status',
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: { edit_id: edit_id },
        before:{  },
        success: function(response) {
            //console.log(response.message);

            if (response.status == true) {

                $.notify({
                    icon: 'ti ti-circle-check-filled fs-5 sp_notify_icon',
                    message: response.message,
                },{
                    allow_dismiss: false,
                    type: "success",
                    placement: {
                        from: "top",
                        align: "right"
                    },
                    z_index: 9999,
                });

                var page = $('.pagination .active .page-link').html();
                sp_load_records($('#search_records').val(), page);

            } else {

                $.notify({
                    icon: 'glyphicon glyphicon-remove-circle',
                    message: response.message,
                },{
                    allow_dismiss: false,
                    type: "danger",
                    placement: {
                        from: "top",
                        align: "right"
                    },
                    z_index: 9999,
                });
                
            }

            hideLoader();
            
        }
    });

});

// Multiple select update status 

$(document).on('change', '#sp_doc_status', function(e) {

    // Show loader before the request starts
    showLoader();

    var checkedIds = $('input.sp_chkbox[type="checkbox"]:checked').map(function () {
            return this.id;
        }).get().join(',');

    var status = $(this).val();

    $.ajax({
        url: base_url+'/admin/multiple-invoice-update-status',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: { edit_ids: checkedIds,status: status },
        before:{  },
        success: function(response) {
            //console.log(response.message);

            if (response.status == true) {

                $.notify({
                    icon: 'ti ti-circle-check-filled fs-5 sp_notify_icon',
                    message: response.message,
                },{
                    allow_dismiss: false,
                    type: "success",
                    placement: {
                        from: "top",
                        align: "right"
                    },
                    z_index: 9999,
                });

                var page = $('.pagination .active .page-link').html();
                sp_load_records($('#search_records').val(), page);

            } else {

                $.notify({
                    icon: 'glyphicon glyphicon-remove-circle',
                    message: response.message,
                },{
                    allow_dismiss: false,
                    type: "danger",
                    placement: {
                        from: "top",
                        align: "right"
                    },
                    z_index: 9999,
                });
                
            }

            hideLoader();
            
        }
    });

});

$(document).on("click",".modal-delete-trigger",function(event) {	

event.stopPropagation();
delete_id = $(this).attr('id');

images = $(this).data("images");

swal({
title: "Delete Permanently",
text: "Are you Sure You wanted to Delete?",
icon: "warning",
buttons: {
    cancel : 'No, cancel please!',
    confirm : {text:'Yes, delete it!',className:'sweet-warning',
closeModal: false,}
},
dangerMode: true,
})
.then((willDelete) => {

if (willDelete) {

    $(".sa-confirm-button-container .confirm").prop('disabled',true);

    var url = base_url+'/admin/invoice-delete';

    var FormData = {
        delete_id: delete_id
        };

    datastring = 'delete_id='+delete_id;
    $.ajax({
        url:url,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: "POST",
        data:datastring,
        
        beforeSend: function()
        {
            $(".sa-confirm-button-container .confirm").prop('disabled',true);
            
            $('.sa-confirm-button-container .confirm').html('<center><i class="fa fa-refresh fa-spin  fa-fw"></i> Wait...</center>');
            
        },
        
        success: function(data)   
        {  
    
            //var obs = $.parseJSON(data);
            var obs = data;
        
            if(obs['status']== true)
            {  
                swal("Deleted!", 'Record Delete Successfully !', "success");

                /* swal({
                title: 'Deleted!',
                text: 'Record Delete Successfully!',
                type: 'success',
                timer: 3000, // Set the timer in milliseconds (3 seconds in this example)
                showConfirmButton: true // Hide the "OK" button
                }); */

                //setTimeout(function(){location.reload();},3000);

                setTimeout(function(){

                    swal.close();

                    // Show loader before the request starts
                    showLoader();

                    var page = $('.pagination .active .page-link').html();
                    sp_load_records($('#search_records').val(), page);

                },2500);
                    
            }
            else{
                
                swal("Cancelled", 'Technical Error !', "error");
            }
            
        },

        error: function(data, textStatus, errorThrown)
        {
            if(data.status == '403'){

                swal("Cancelled", 'You does not have the right permissions.', "error");
            }
        }
    });
    
    return false;
    
} else {
    swal.close();
    //swal("Cancelled", "This process has been cancelled :)", "error");

}
});
});

    

</script>

    @endpush

    @endsection

    @section('scripts')

    @endsection