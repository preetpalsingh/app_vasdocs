@extends('layouts.app')

@section('title', $title . ' List')

@section('content')

<style>
    .zoom-in-cursor {
    cursor: grabbing;
    }

    .default-cursor {
    cursor: default;
    }

    .controls button {
        border: 0;
        font-size: 20px;
        padding: 6px 10px;
    }

    .image-container {
        position: relative;
        max-width: 100%;
        overflow: hidden;
    }

    #image {
        max-width: 100%;
    }

    .controls {
        position: absolute;
        bottom: 10px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        background-color: rgba(255, 255, 255, 0.7);
        border-radius: 5px;
        padding: 5px;
    }

    .controls button {
        background-color: transparent;
        border: none;
        cursor: pointer;
        margin: 0 5px;
        font-size: 16px;
        color: #333;
    }

    .pdfjs-toolbar, .pdfjs-toolbar i {
            font-size: 14px;
        }
        .pdfjs-toolbar span {
            margin-right: 0.5em;
            margin-left: 0.5em;
            width: 4em !important;
            font-size: 12px;
        }
        .pdfjs-toolbar .btn-sm {
            padding: 0.12rem 0.25rem;

        }
        .pdfjs-toolbar {
            width: 100%;
            height: auto;
            background: #ddd;
            z-index: 100;
        }

        .pdfjs-toolbar .btn.btn-secondary {
            background: #000;
            border: #000;
            padding-top: 5px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #757272 !important;
            line-height: 28px;
        }

        .select2-container--default .select2-selection--single {
            border: 1px solid #e3e3e3 !important;
            height: 38px !important;
            padding: 5px !important;
        }

    /* Rest of your existing CSS styles */
</style>

    
        
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @if( $file_type == 'pdf' )

    <script src="{{asset('dashboard-assets/pdf/js/pdfjs-viewer.js?dd=5484')}}"></script>
    <link  id="themeColors"  rel="stylesheet" href="{{asset('dashboard-assets/pdf/css/pdfjs-viewer.css?dd=5484')}}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

    @endif

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
                    </h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted " href="#">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Clients</li>
                            <li class="breadcrumb-item" aria-current="page">{{$title}}s</li>
                            <li class="breadcrumb-item" aria-current="page">@php echo 'CH-' . str_pad($data->id, 6, '0', STR_PAD_LEFT) @endphp </li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-end ">
                        <a href="{{ url()->previous() }}" class="btn btn-primary">Go Back</a>
                        <!--img src="images/breadcrumb/ChatBc.png" alt="" class="img-fluid mb-n4">
                        <img src="images/breadcrumb/agrovista_invoice.jpeg" alt="" class="img-fluid mb-n4"-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="widget-content searchable-container list">
        <!-- --------------------- start Contact ---------------- -->

        <div class="row">
            <div class="col-6 col-lg-6 mb-5 mb-md-0">

            @if( $file_type == 'pdf' )

            <div class="row col-12  pdfviewer p-0  " style="height: 600px !important;">
            
                <div class="pdfjs-viewer h-100" pdf-document="{{asset('/documents')}}/{{ $data->file }}" initial-zoom="fit" on-document-ready="pdfViewer = this.pdfViewer;">
                </div>
                
                <div class="pdfjs-toolbar text-center row m-0 p-0">
                    <div class="col-12 col-lg-6 my-1">
                        <button class="btn btn-secondary btn-sm btn-first" onclick="pdfViewer.first()"><i class="material-icons-outlined">skip_previous</i></button>
                        <button class="btn btn-secondary btn-sm btn-prev" onclick="pdfViewer.prev(); return false;"><i class="material-icons-outlined">navigate_before</i></button>
                        <span class="pageno"></span>
                        <button class="btn btn-secondary btn-sm btn-next" onclick="pdfViewer.next(); return false;"><i class="material-icons-outlined">navigate_next</i></button>
                        <button class="btn btn-secondary btn-sm btn-last" onclick="pdfViewer.last()"><i class="material-icons-outlined">skip_next</i></button>
                        <a class="btn btn-secondary btn-sm btn-last" data-bs-toggle="tooltip" title="Download Invoice" href="{{ route('admin.documentsDownload', ['invoice_id' =>  $invoice_id ]) }}"><i class="material-icons-outlined">download</i></a>
                    </div>
                    <div class="col-12 col-lg-6 my-1">
                        <button class="btn btn-secondary btn-sm" onclick="pdfViewer.setZoom('out')"><i class="material-icons-outlined">zoom_out</i></button>
                        <span class="zoomval">100%</span>
                        <button class="btn btn-secondary btn-sm" onclick="pdfViewer.setZoom('in')"><i class="material-icons-outlined">zoom_in</i></button>
                        <button class="btn btn-secondary btn-sm ms-3" onclick="pdfViewer.setZoom('width')"><i class="material-icons-outlined">swap_horiz</i></button>
                        <button class="btn btn-secondary btn-sm" onclick="pdfViewer.setZoom('height')"><i class="material-icons-outlined">swap_vert</i></button>
                        <button class="btn btn-secondary btn-sm" onclick="pdfViewer.setZoom('fit')"><i class="material-icons-outlined">fit_screen</i></button>
                    </div>
                </div>
                
            </div>

            @else

                <div class="image-container">
                <div class="image-wrapper">
                    <img id="image" src="{{asset('/documents')}}/{{ $data->file }}" alt="Image" style=" min-height: 600px;">
                </div>
                    <div class="controls">
                        <button id="rotateLeft"><i class="fas fa-undo"></i></button>
                        <button id="rotateRight"><i class="fas fa-redo"></i></button>
                        <button id="zoomIn"><i class="fas fa-search-plus"></i></button>
                        <button id="zoomOut"><i class="fas fa-search-minus"></i></button>
                        <button id="resetZoom"><i class="fas fa-expand-arrows-alt"></i></button>
                        <button id="download"><i class="fas fa-download"></i></button>
                    </div>
                </div>

            @endif

            </div>

            <div class="col-12 col-lg-6">

                <div class="card">
                    <div class="card-body" style="position: relative;">
                        <div id="sp_preloader" style="display: none;"><div class="shapes-8"></div></div>
                        <h3 style="display: flex;flex-direction: row;justify-content: space-between;">Invoice Details:<a href="javascript:void(0)" class="btn btn-danger d-flex align-items-center modal-delete-trigger" data-bs-toggle="tooltip" data-bs-original-title="Delete Invoice" data-id="{{ $data->id }}" id="{{ $data->id }}" style="font-size: 14px;">
                            <i class="fas fa-trash text-white me-1 fs-2"></i>Delete</a></h3>
                        <p class="card-subtitle mb-5"></p>
                        <form class="form sp_form" method="post">

                            @csrf

                            <div class="mb-3 row">
                                <label for="example-url-input" class="col-md-3 col-form-label">Supplier</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" value="{{ $data->supplier }}" name="supplier" id="supplier" />
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="example-tel-input" class="col-md-3 col-form-label">Invoice No.</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="numbers" value="{{ $data->invoice_number }}" name="invoice_number" id="invoice_number" />
                                </div>
                            </div>


                            <div class="mb-3 row">
                                <label for="example-date-input" class="col-md-3 col-form-label">Invoice Date</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text "  value="{{ $data->invoice_date }}" name="invoice_date" id="invoice_date" placeholder="DD/MM/YYYY" />
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="example-date-input" class="col-md-3 col-form-label">Due Date</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text"  value="{{ $data->due_date }}" name="due_date" id="due_date" placeholder="DD/MM/YYYY" />
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="example-time-input" class="col-md-3 col-form-label">A/c Code</label>
                                <div class="col-md-9">
                                    <select class="form-select col-12 sp_multiselect" name="account_code" id="account_code" required>
                                        <option value="" >Choose</option>
                                        @foreach ( $account_codes as $code )

                                        @php

                                        $selected = '';

                                        if( $code->id == $data->account_code ){

                                            $selected = 'selected';
                                        }

                                        @endphp

                                            <option value="{{ $code->id }}" {{ $selected }}>{{ $code->code.'-'.$code->report_code.'-'.$code->name }}</option>

                                        @endforeach
                                        
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="example-time-input" class="col-md-3 col-form-label">Gross Amount</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="number"  value="{{ $data->total_amount }}" name="total_amount" id="total_amount" step="any" />
                                    <!--small class="">(Net Amount + Tax + Standard Vat)</small-->
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="example-time-input" class="col-md-3 col-form-label">Tax </label>
                                <div class="col-md-9">
                                    <select class="form-select col-12 " name="tax_percent" id="tax_percent" required>
                                            <option value="0" @if( $data->tax_percent == 0 ) selected @endif>0%</option>
                                            <option value="5" @if( $data->tax_percent == 5 ) selected @endif>5%</option>
                                            <option value="20" @if( $data->tax_percent == 20 ) selected @endif>20%</option>
                                    </select>
                                    <small class="sp_tax_amount">@if( $data->tax_amount > 0 )Tax Value : {{ $data->tax_amount }} @endif</small>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="example-week-input" class="col-md-3 col-form-label">Standard Vat</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="number"  value="{{ $data->standard_vat }}" name="standard_vat" id="standard_vat" step="any" />
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="example-week-input" class="col-md-3 col-form-label">Net Amount</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="number"  value="{{ $data->net_amount }}" name="net_amount" id="net_amount" step="any" />
                                </div>
                            </div>

                            <!--div class="mb-3 row">
                                <label for="example-time-input" class="col-md-3 col-form-label">Tax</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="number"  value="{{ $data->tax_amount }}" name="tax_amount" id="tax_amount" step="any" readonly />
                                </div>
                            </div-->


                            @php

                            $tax_amount = '0.00';

                            if( !empty( $data->tax_amount ) ){

                                $tax_amount = $data->tax_amount;
                            }

                            @endphp

                            <input class="form-control" type="hidden"  value="{{ $tax_amount }}" name="tax_amount" id="tax_amount" step="any" />
                            
                            <div class="mb-3 row">
                                <label for="example-time-input" class="col-md-3 col-form-label">Status</label>
                                <div class="col-md-9">
                                    <select class="form-select col-12" name="status" id="status" required>
                                        <option value="Processing" @if($data->status == 'Processing') selected @endif>Processing</option>
                                        <option value="Review" @if($data->status == 'Review') selected @endif>Reviewed</option>
                                        <option value="Ready" @if($data->status == 'Ready') selected @endif>Ready</option>
                                        <option value="Archive" @if($data->status == 'Archive') selected @endif>Archive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="example-time-input" class="col-md-3 col-form-label">Payment Board </label>
                                <div class="col-md-9">
                                    <select class="form-select col-12 " name="payment_method" id="payment_method" required>
                                            <option value="Cash" @if( $data->payment_method == 'Cash' ) selected @endif>Cash</option>
                                            <option value="Bank" @if( $data->payment_method == 'Bank' ) selected @endif>Bank</option>
                                            <option value="Credit Card" @if( $data->payment_method == 'Credit Card' ) selected @endif>Credit Card</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <button class=" btn rounded-pill px-4 btn-success  font-weight-medium  waves-effect waves-light " type="submit">
                                    <i class="ti ti-send fs-5"></i>
                                    Submit
                                </button>
                            </div>
                                
                            <div class="msg_status" ></div>
                            
                            <input type="hidden" value="{{ $invoice_id }}" name="edit_id" id="edit_id">

                        </form>
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
                                                <input type="text" id="c-occupation" class="form-control" placeholder="Occupation" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 contact-phone">
                                                <input type="text" id="c-phone" class="form-control" placeholder="Phone" />
                                                <span class="validation-text text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3 contact-location">
                                                <input type="text" id="c-location" class="form-control" placeholder="Location" />
                                            </div>
                                        </div>
                                    </div>
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

        <!-- <script src="https://chathaandco.propertyhomes.in/public/oimage.js"></script> -->

        @if( $file_type != 'pdf' )

        <script>
            $(document).ready(function() {

            let currentRotation = 0;
            let currentScale = 1;
            let isDragging = false;
            let startPositionX = 0;
            let startPositionY = 0;

            const $image = $("#image");
            const $imageWrapper = $(".image-wrapper");

            // Function to rotate the image left
            $("#rotateLeft").click(function() {
                currentRotation -= 90;
                $image.css("transform", `rotate(${currentRotation}deg) scale(${currentScale})`);
            });

            // Function to rotate the image right
            $("#rotateRight").click(function() {
                currentRotation += 90;
                $image.css("transform", `rotate(${currentRotation}deg) scale(${currentScale})`);
            });

            // Function to zoom in
            $("#zoomIn").click(function() {
                currentScale += 0.1;
                $image.css("transform", `rotate(${currentRotation}deg) scale(${currentScale})`);
                $imageWrapper.addClass("zoom-in-cursor");
            });

            // Function to reset zoom and rotation
            $("#resetZoom").click(function() {
                currentRotation = 0;
                currentScale = 1;
                $image.css("transform", `rotate(0deg) scale(1)`);
                $imageWrapper.removeClass("zoom-in-cursor");
            });

            // Function to download the image
            $("#download").click(function() {
                // Replace 'your_image_url_here' with the actual URL of the image you want to download
                const imageUrl = $image.attr("src");
                const a = document.createElement("a");
                a.href = imageUrl;
                a.download = "image.jpg";
                a.style.display = "none";
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            });

            // Enable image dragging
            $imageWrapper.on("mousedown", function(event) {
                if (currentScale > 1) {
                isDragging = true;
                startPositionX = event.clientX - $imageWrapper.offset().left;
                startPositionY = event.clientY - $imageWrapper.offset().top;
                $imageWrapper.addClass("zoom-in-cursor");
                }
            });

            $(document).on("mousemove", function(event) {
                if (isDragging) {
                const offsetX = event.clientX - $imageWrapper.offset().left - startPositionX;
                const offsetY = event.clientY - $imageWrapper.offset().top - startPositionY;
                $image.css("transform", `translate(${offsetX}px, ${offsetY}px) rotate(${currentRotation}deg) scale(${currentScale})`);
                }
            });

            $(document).on("mouseup", function() {
                isDragging = false;
                $imageWrapper.removeClass("zoom-in-cursor");
            });
        });

        </script>

        @else 

            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
            <script>
            // Let's initialize the PDFjs library
            var pdfjsLib = window['pdfjs-dist/build/pdf'];
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';
            </script>
            <script>
                var pdfViewer;
            </script>
            
        @endif

        <script>
            $('.sp_multiselect').select2();
        </script>

        @push('page_scripts')

        <!-- Custom JS -->

        <script>
            var base_url = "{{ Config::get('app.url') }}";
            var modal_title = "{{$title}}";

            $(document).ready(function() {
                // Function to calculate tax amount
                /* function calculateTax() {
                    const netAmount = parseFloat($('#net_amount').val());
                    const taxPercent = parseFloat($('#tax_percent').val());
                    const standard_vat = parseFloat($('#standard_vat').val());
                    const taxAmount = (netAmount * taxPercent) / 100;

                    if( taxAmount > 0){

                        $('#tax_amount').val(taxAmount.toFixed(2));

                        $('.sp_tax_amount').html('Tax Value : '+taxAmount.toFixed(2));

                    } else {

                        
                        $('#tax_amount').val('0.00');

                        $('.sp_tax_amount').html('');
                    }

                    

                    // Calculate total amount
                    const totalAmount = netAmount + taxAmount + standard_vat;
                    $('#total_amount').val(totalAmount.toFixed(2));

                } */

                function calculateTax() {
                    const total_amount = parseFloat($('#total_amount').val());
                    //const netAmount = parseFloat($('#net_amount').val());
                    const taxPercent = parseFloat($('#tax_percent').val());
                    const standard_vat = parseFloat($('#standard_vat').val());
                    netAmount = total_amount / (1 + (taxPercent / 100));
                    const taxAmount = total_amount - netAmount;

                    if( taxAmount > 0){

                        $('#tax_amount').val(taxAmount.toFixed(2));

                        $('.sp_tax_amount').html('Tax Value : '+taxAmount.toFixed(2));

                    } else {

                        
                        $('#tax_amount').val('0.00');

                        $('.sp_tax_amount').html('');
                    }

                    
                    $('#net_amount').val(netAmount.toFixed(2));

                }

                // Initial calculation
                calculateTax();

                // Event listener for tax_percent dropdown change
                $('#tax_percent,#standard_vat,#total_amount').on('change', function() {
                    calculateTax();
                });

            });

            

            function showLoader() {

                $('#sp_preloader').show();

            }

            function hideLoader() {
                
                $('#sp_preloader').hide();
            }

            $(document).on("submit",".sp_form",function(event) {

                showLoader();

                event.preventDefault();

                $('.sp_ajax_loader').remove();

                $('#add_modal .modal-content').append('<div class="sp_custom_loader sp_ajax_loader" style="z-index: 1000; border: none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background: rgb(255, 255, 255); opacity: 0.6; cursor: default; position: absolute;"></div>');
                
                var url = base_url+'/admin/invoice-update';

                var edit_id             =  $('#edit_id').val();

                var data = new FormData(this);
                
                    axios.post(url, data).then(function (response) {
                        console.log(response.data+'fetch');
                        if (response.data.status == true) {

                            $('.msg_status').html('<div class="alert alert-success">'+response.data.message+'</div>');

                            /* //setTimeout(function(){location.reload();},2500);

                            setTimeout(function(){
                                
                                $('#add_modal').modal('hide');

                            },2000);

                            setTimeout(function(){

                                // Show loader before the request starts
                                showLoader();

                                var page = $('.pagination .active .page-link').html();
                                sp_load_records($('#search_records').val(), page);

                            },2500); */

                        } else {
                            
                            $('.msg_status').html('<div class="alert alert-danger">'+response.data.message+'</div>');
                        }

                        hideLoader();

                        //$('.sp_ajax_loader').remove();

                    }).catch(function (error) {

                        if (error == 'Error: Request failed with status code 403') {

                            $('.msg_status').html('<div class="alert alert-danger">You does not have the right permissions.</div>');

                        }
                        
                        hideLoader();
                        
                        //$('.sp_ajax_loader').remove();

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

                    window.location.replace("{{ url()->previous() }}");



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