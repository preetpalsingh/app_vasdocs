<!-- custom redirect -->
@if (!Auth::check())
<script>window.location = "{{ Config::get('app.url') }}/login";</script>I'm connected
@endif


<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <!--i class="fas fa-university"></i-->
			<img src="/admin/img/admin-chrome.png" width="50px">
        </div>
        <div class="sidebar-brand-text mx-3">Strongroot<br>Control Panel</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item ">
        <a class="nav-link" href="{{ route('home') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    

    @hasrole('Admin')

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <!--div class="sidebar-heading">
            Management
        </div-->

        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#taTpDropDown"
                aria-expanded="true" aria-controls="taTpDropDown">
                <i class="fas fa-user-alt"></i>
                <span>User Management <br>บริการจัดการผู้ใช้</</span>
            </a>
            <div id="taTpDropDown" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">User Management |<h6>
                    <a class="collapse-item" href="{{ route('users.index') }}">List รายชื่อผู้ใช้</a>
                    <a class="collapse-item" href="{{ route('users.create') }}">Add New</a>
                    <!--a class="collapse-item" href="{{ route('users.import') }}">Import Data</a-->
                </div>
            </div>
        </li>

       

    @endhasrole

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.productlist') }}" >
        <i class="fas fa-mobile-alt"></i>
            <span>Phone Catalog<br>จัดการรายการสินค้าประเภท<br>เครื่องโทรศัพท์</span>
        </a>
    </li>

    @hasrole('Admin')

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.simcardlist') }}" >
        <i class="fas fa-sim-card"></i>
            <span>Simcard Management <br>จัดการรายการสินค้า<br>ประเภทซิมการ์ด</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.orderlist') }}" >
        <i class="fas fa-calendar"></i>
            <span>Orders Management<br>บริหารคำสั่งซื้อ</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.umlist') }}" >
        <i class="fas fa-users"></i>
            <span>Union Membership<br>จัดการข้อมูลสมาชิกสหกรณ์</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.loglist') }}" >
        <i class="fas fa-flag"></i>
            <span>Log</span>
        </a>
    </li>

    @endhasrole

    @hasrole('Vendor')

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.orderVL') }}" >
        <i class="fas fa-calendar"></i>
            <span>Orders Management <br>รายการคำสั่งซื้อ</span>
        </a>
    </li>

    @endhasrole

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </li>
    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>


</ul>