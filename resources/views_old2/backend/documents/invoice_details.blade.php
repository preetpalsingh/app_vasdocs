@extends('layouts.sidebar_app')

@section('title', $title . ' List')

@section('content')

<style>

/* Rest of your existing CSS styles */

</style>
    
    <div class="container-fluid">
        <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
            <div class="col-9">
                <h4 class="fw-semibold mb-8"><!-- {{$title}}s -->Invoice Details</h4>
                <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-muted " href="./index.html">Dashboard</a></li>
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
                <input type="text" class="form-control product-search ps-5" id="input-search" placeholder="Search Clients...">
                <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                </form>
            </div>
            <div class="col-md-8 col-xl-9 text-end d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                <div class="action-btn show-btn" style="display: none">
                <a href="javascript:void(0)" class="delete-multiple btn-light-danger btn me-2 text-danger d-flex align-items-center font-medium">
                    <i class="ti ti-trash text-danger me-1 fs-5"></i> Delete All Row 
                </a>
                </div>
                <!--a href="javascript:void(0)" id="btn-add-contact" class="btn btn-info d-flex align-items-center">
                <i class="ti ti-users text-white me-1 fs-5"></i> Add Client 
                </a-->
            </div>
            </div>
        </div>
        
        <div class="card card-body">
            <div class="table-responsive">
            <table class="table search-table align-middle text-nowrap">
                <thead class="header-item">
                <tr><th>
                    <div class="n-chk align-self-center text-center">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input primary" id="contact-check-all">
                        <label class="form-check-label" for="contact-check-all"></label>
                        <span class="new-control-indicator"></span>
                    </div>
                    </div>
                                <th>Supplier</th>
								<th>Invoice no</th>
								<th>Invoice Date</th>
								<th>Status</th>
								<th>Sub Total</th>
								<th>Tax</th>
								<th>Total Amount</th>
                                <th>Action</th>
								
                        </thead>
                <tbody>
                
                
                <tr class="search-items">
                    <td>
                    <div class="n-chk align-self-center text-center">
                        <div class="form-check">
                        <input type="checkbox" class="form-check-input contact-chkbox primary" id="checkbox8">
                        <label class="form-check-label" for="checkbox8"></label>
                        </div>
                    </div>
                    </td>
                    <td>
                    <div class="d-flex align-items-center">
                        <div class="ms-0">
                        <div class="user-meta-info">
                            <h6 class="user-name mb-0" data-name="Penelope Baker"> Fateh Singh </h6>
                            <!-- <span class="user-work fs-3" data-occupation="Web Developer">Web Developer</span> -->
                        </div>
                        </div>
                    </div>
                    </td>
                     <td>
                    <span class="usr-email-addr" data-email="baker@mail.com">#651472</span>
                    </td>
                    <td>
                    <span class="usr-location" data-location="Edinburgh, UK">23-02-1998</span>
                    </td>
                    <td>
                    <span class="usr-location" data-location="Edinburgh, UK"><span class="mb-1 badge bg-warning">Processing</span></span>
                    </td>
                    <td>
                    <span class="usr-location" data-location="Edinburgh, UK">$542.20</span>
                    </td>
                    <td>
                    <span class="usr-ph-no" data-phone="+91 (405) 483- 4512">$300.30</span>
                    </td>
                    <td>
                    <span class="usr-ph-no" data-phone="+91 (405) 483- 4512">$842.50</span>
                    </td>
                    <td>
                    <div class="action-btn">
                       
                        <a href="{{ route('admin.documentsView') }}" class="text-info  ms-2">
                        <!-- <i class="ti ti-trash fs-5"></i> -->
                        <i class="ti ti-eye fs-5"></i>
                        </a>
                    </div>
                    </td>
                    <td>
                    <div class="action-btn">
                       
                        <a href="javascript:void(0)" class="text-danger  delete ms-2">
                        <i class="ti ti-trash fs-5"></i>
                        </a>
                    </div>
                    </td>
                    
                </tr>
                <!-- Tr END -->
                <tr class="search-items">
                    <td>
                    <div class="n-chk align-self-center text-center">
                        <div class="form-check">
                        <input type="checkbox" class="form-check-input contact-chkbox primary" id="checkbox8">
                        <label class="form-check-label" for="checkbox8"></label>
                        </div>
                    </div>
                    </td>
                    <td>
                    <div class="d-flex align-items-center">
                        <div class="ms-0">
                        <div class="user-meta-info">
                            <h6 class="user-name mb-0" data-name="Penelope Baker"> Fateh Singh </h6>
                            <!-- <span class="user-work fs-3" data-occupation="Web Developer">Web Developer</span> -->
                        </div>
                        </div>
                    </div>
                    </td>
                     <td>
                    <span class="usr-email-addr" data-email="baker@mail.com">#651472</span>
                    </td>
                    <td>
                    <span class="usr-location" data-location="Edinburgh, UK">23-02-1998</span>
                    </td>
                    <td>
                    <span class="usr-location" data-location="Edinburgh, UK"><span class="mb-1 badge bg-secondary">Review</span></span>
                    </td>
                    <td>
                    <span class="usr-location" data-location="Edinburgh, UK">$542.20</span>
                    </td>
                    <td>
                    <span class="usr-ph-no" data-phone="+91 (405) 483- 4512">$300.30</span>
                    </td>
                    <td>
                    <span class="usr-ph-no" data-phone="+91 (405) 483- 4512">$842.50</span>
                    </td>
                    <td>
                    <div class="action-btn">
                       
                        <a href="javascript:void(0)" class="text-info edit ms-2">
                        <!-- <i class="ti ti-trash fs-5"></i> -->
                        <i class="ti ti-eye fs-5"></i>
                        </a>
                    </div>
                    </td>
                    <td>
                    <div class="action-btn">
                       
                        <a href="javascript:void(0)" class="text-danger  delete ms-2">
                        <i class="ti ti-trash fs-5"></i>
                        </a>
                    </div>
                    </td>
                    
                </tr>
                <!-- Tr END -->
                <tr class="search-items">
                    <td>
                    <div class="n-chk align-self-center text-center">
                        <div class="form-check">
                        <input type="checkbox" class="form-check-input contact-chkbox primary" id="checkbox8">
                        <label class="form-check-label" for="checkbox8"></label>
                        </div>
                    </div>
                    </td>
                    <td>
                    <div class="d-flex align-items-center">
                        <div class="ms-0">
                        <div class="user-meta-info">
                            <h6 class="user-name mb-0" data-name="Penelope Baker"> Fateh Singh </h6>
                            <!-- <span class="user-work fs-3" data-occupation="Web Developer">Web Developer</span> -->
                        </div>
                        </div>
                    </div>
                    </td>
                     <td>
                    <span class="usr-email-addr" data-email="baker@mail.com">#651472</span>
                    </td>
                    <td>
                    <span class="usr-location" data-location="Edinburgh, UK">23-02-1998</span>
                    </td>
                    <td>
                    <span class="usr-location" data-location="Edinburgh, UK"><span class="mb-1 badge bg-success">Ready</span></span>
                    </td>
                    <td>
                    <span class="usr-location" data-location="Edinburgh, UK">$542.20</span>
                    </td>
                    <td>
                    <span class="usr-ph-no" data-phone="+91 (405) 483- 4512">$300.30</span>
                    </td>
                    <td>
                    <span class="usr-ph-no" data-phone="+91 (405) 483- 4512">$842.50</span>
                    </td>
                    <td>
                    <div class="action-btn">
                       
                        <a href="javascript:void(0)" class="text-info edit ms-2">
                        <!-- <i class="ti ti-trash fs-5"></i> -->
                        <i class="ti ti-eye fs-5"></i>
                        </a>
                    </div>
                    </td>
                    <td>
                    <div class="action-btn">
                       
                        <a href="javascript:void(0)" class="text-danger  delete ms-2">
                        <i class="ti ti-trash fs-5"></i>
                        </a>
                    </div>
                    </td>
                    
                </tr>
                <!-- Tr END -->
                <tr class="search-items">
                    <td>
                    <div class="n-chk align-self-center text-center">
                        <div class="form-check">
                        <input type="checkbox" class="form-check-input contact-chkbox primary" id="checkbox8">
                        <label class="form-check-label" for="checkbox8"></label>
                        </div>
                    </div>
                    </td>
                    <td>
                    <div class="d-flex align-items-center">
                        <div class="ms-0">
                        <div class="user-meta-info">
                            <h6 class="user-name mb-0" data-name="Penelope Baker"> Fateh Singh </h6>
                            <!-- <span class="user-work fs-3" data-occupation="Web Developer">Web Developer</span> -->
                        </div>
                        </div>
                    </div>
                    </td>
                     <td>
                    <span class="usr-email-addr" data-email="baker@mail.com">#651472</span>
                    </td>
                    <td>
                    <span class="usr-location" data-location="Edinburgh, UK">23-02-1998</span>
                    </td>
                    <td>
                    <span class="usr-location" data-location="Edinburgh, UK"><span class="mb-1 badge bg-primary">Archive</span></span>
                    </td>
                    <td>
                    <span class="usr-location" data-location="Edinburgh, UK">$542.20</span>
                    </td>
                    <td>
                    <span class="usr-ph-no" data-phone="+91 (405) 483- 4512">$300.30</span>
                    </td>
                    <td>
                    <span class="usr-ph-no" data-phone="+91 (405) 483- 4512">$842.50</span>
                    </td>
                    <td>
                    <div class="action-btn">
                       
                        <a href="javascript:void(0)" class="text-info edit ms-2">
                        <!-- <i class="ti ti-trash fs-5"></i> -->
                        <i class="ti ti-eye fs-5"></i>
                        </a>
                    </div>
                    </td>
                    <td>
                    <div class="action-btn">
                       
                        <a href="javascript:void(0)" class="text-danger  delete ms-2">
                        <i class="ti ti-trash fs-5"></i>
                        </a>
                    </div>
                    </td>
                    
                </tr>
                <!-- Tr END -->
                

                </tbody>
            </table>
            </div>
        </div>
        </div>
    </div>

        <!-- --------------------- start Contact ---------------- -->
        
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
                <!-- </div> -->
            </div>
            </div>
        </div>

<script>
   

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

