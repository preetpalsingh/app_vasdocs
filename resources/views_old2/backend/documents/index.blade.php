@extends('layouts.sidebar_app')

@section('title', $title . ' List')

@section('content')

<style>

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

/* Rest of your existing CSS styles */

</style>
    
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
                <img src="images/breadcrumb/agrovista_invoice.jpeg" alt="" class="img-fluid mb-n4">
                </div>
            </div>
            </div>
        </div>
        </div>
        <div class="widget-content searchable-container list">
        <!-- --------------------- start Contact ---------------- -->
       
        <div class="row">
            <div class="col-12 col-lg-6 mb-5 mb-md-0">
                <div class="image-container">
                    <img id="image" src="/images/agrovista_invoice.jpeg" alt="Image">
                    <div class="controls">
                        <button id="rotateLeft"><i class="fas fa-undo"></i></button>
                        <button id="rotateRight"><i class="fas fa-redo"></i></button>
                        <button id="zoomIn"><i class="fas fa-search-plus"></i></button>
                        <button id="zoomOut"><i class="fas fa-search-minus"></i></button>
                        <button id="resetZoom"><i class="fas fa-expand-arrows-alt"></i></button>
                        <button id="download"><i class="fas fa-download"></i></button>
                    </div>
                </div>
            </div>
        
            <div class="col-12 col-lg-6">

                <div class="card">
                    <div class="card-body">
                        <h3>Invoice Details:</h3>
                        <p class="card-subtitle mb-5"></p>
                        <form class="form">
                        
                        <div class="mb-3 row">
                            <label for="example-url-input" class="col-md-3 col-form-label">Supplier</label>
                            <div class="col-md-9">
                            <input class="form-control" type="text" value="" id="example-url-input" />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="example-tel-input" class="col-md-3 col-form-label">Invoice No.</label>
                            <div class="col-md-9">
                            <input class="form-control" type="numbers" value="" id="example-tel-input" />
                            </div>
                        </div>
                    
                        
                        <div class="mb-3 row">
                            <label for="example-date-input" class="col-md-3 col-form-label">Invoice Date</label>
                            <div class="col-md-9">
                            <input class="form-control" type="text " value="" id="example-date-input" placeholder="DD/MM/YYYY"/>
                            </div>
                        </div>
                    
                        <div class="mb-3 row">
                            <label for="example-date-input" class="col-md-3 col-form-label">Due Date</label>
                            <div class="col-md-9">
                            <input class="form-control" type="text" value="" id="example-date-input" placeholder="DD/MM/YYYY" />
                            </div>
                        </div>
                    
                        <div class="mb-3 row">
                            <label for="example-week-input" class="col-md-3 col-form-label">Sub Total</label>
                            <div class="col-md-9">
                            <input class="form-control" type="number" value="" id="example-week-input" />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="example-time-input" class="col-md-3 col-form-label">Tax</label>
                            <div class="col-md-9">
                            <input class="form-control" type="number" value="" id="example-time-input" />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="example-time-input" class="col-md-3 col-form-label">Total Amount</label>
                            <div class="col-md-9">
                            <input class="form-control" type="number" value="" id="example-time-input" />
                            </div>
                        </div>
                    
                        <div class="mb-3">
                            <button class=" btn rounded-pill px-4 btn-success  font-weight-medium  waves-effect waves-light " type="submit">
                            <i class="ti ti-send fs-5"></i>
                            Submit
                            </button>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
   
    const image = $("#image");
    const rotateLeft = $("#rotateLeft");
    const rotateRight = $("#rotateRight");
    const zoomIn = $("#zoomIn");
    const zoomOut = $("#zoomOut");
    const resetZoom = $("#resetZoom");
    const download = $("#download");

    let rotationAngle = 0;
    let scale = 1;

    // Rotate the image left
    rotateLeft.click(function() {
        rotationAngle -= 90;
        image.css("transform", `rotate(${rotationAngle}deg) scale(${scale})`);
    });

    // Rotate the image right
    rotateRight.click(function() {
        rotationAngle += 90;
        image.css("transform", `rotate(${rotationAngle}deg) scale(${scale})`);
    });

    // Zoom in the image
    zoomIn.click(function() {
        scale += 0.1;
        image.css("transform", `rotate(${rotationAngle}deg) scale(${scale})`);
    });

    // Zoom out the image
    zoomOut.click(function() {
        scale -= 0.1;
        image.css("transform", `rotate(${rotationAngle}deg) scale(${scale})`);
    });

    // Reset zoom and rotation
    resetZoom.click(function() {
        rotationAngle = 0;
        scale = 1;
        image.css("transform", "");
    });

    // Download the image
    download.click(function() {
        const link = document.createElement("a");
        link.href = image.attr("src");
        link.download = "image.jpg";
        link.click();
    });


</script>
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

