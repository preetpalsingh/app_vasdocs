<aside class="left-sidebar">
        <!-- Sidebar scroll-->
        <div>
          <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="./index.html" class="text-nowrap logo-img">
              <img src="{{asset('dashboard-assets/images/logos/chathaandco-logo.png')}}" class="dark-logo" width="180" alt="" />
              <img src="{{asset('dashboard-assets/images/logos/light-logo.svg')}}" class="light-logo"  width="180" alt="" />
            </a>
            <div class="close-btn d-lg-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
              <i class="ti ti-x fs-8"></i>
            </div>
          </div>
          <!-- Sidebar navigation-->
          <nav class="sidebar-nav scroll-sidebar" data-simplebar>
            <ul id="sidebarnav">
              <!-- ============================= -->
              <!-- Home -->
              <!-- ============================= -->
              <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">Dashboard</span>
              </li>
              <!-- =================== -->
              <!-- Dashboard -->
              <!-- =================== -->
              
              <li class="sidebar-item">
                 <a class="sidebar-link" href="{{ route('admin.clientList') }}" aria-expanded="false"> 
                 <!-- <a class="sidebar-link" href="http://localhost/laravel/chathaandco/public/admin/client-list" aria-expanded="false">  -->
                  <span>
                    <i class="ti ti-users"></i>
                  </span>
                  <span class="hide-menu">My Clients</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                  <span class="d-flex">
                    <i class="ti ti-currency-dollar"></i>
                  </span>
                  <span class="hide-menu">Invoice Details</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="{{ route('admin.invoice_details') }}" class="sidebar-link">
                      <div class="round-16 d-flex align-items-center justify-content-center">
                        <i class="ti ti-circle"></i>
                      </div>
                      <span class="hide-menu">All</span>
                    </a>
                  </li>
                  
                  <li class="sidebar-item">
                    <a href="{{ route('admin.invoice_details') }}" class="sidebar-link">
                      <div class="round-16 d-flex align-items-center justify-content-center">
                        <i class="ti ti-circle"></i>
                      </div>
                      <span class="hide-menu">In Processing</span>
                    </a>
                  </li>
                  
                  <li class="sidebar-item">
                    <a href="{{ route('admin.invoice_details') }}" class="sidebar-link">
                      <div class="round-16 d-flex align-items-center justify-content-center">
                        <i class="ti ti-circle"></i>
                      </div>
                      <span class="hide-menu">To Review</span>
                    </a>
                  </li>
                  
                  <li class="sidebar-item">
                    <a href="{{ route('admin.invoice_details') }}" class="sidebar-link">
                      <div class="round-16 d-flex align-items-center justify-content-center">
                        <i class="ti ti-circle"></i>
                      </div>
                      <span class="hide-menu">Ready</span>
                    </a>
                  </li>
                  
                  <li class="sidebar-item">
                    <a href="{{ route('admin.invoice_details') }}" class="sidebar-link">
                      <div class="round-16 d-flex align-items-center justify-content-center">
                        <i class="ti ti-circle"></i>
                      </div>
                      <span class="hide-menu">Archive</span>
                    </a>
                  </li>

                </ul>
              </li>
          </nav>
          <div class="fixed-profile p-3 bg-light-secondary rounded sidebar-ad mt-3">
            <div class="hstack gap-3">
              <div class="john-img">
                <img src="{{asset('dashboard-assets/images/profile/user-1.jpg')}}" class="rounded-circle" width="40" height="40" alt="">
              </div>
              <div class="john-title">
                <h6 class="mb-0 fs-4 fw-semibold">Mathew</h6>
                <span class="fs-2 text-dark">Designer</span>
              </div>
              <button class="border-0 bg-transparent text-primary ms-auto" tabindex="0" type="button" aria-label="logout" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="logout">
                <i class="ti ti-power fs-6"></i>
              </button>
            </div>
          </div>  
          <!-- End Sidebar navigation -->
        </div>
        <!-- End Sidebar scroll-->
      </aside>