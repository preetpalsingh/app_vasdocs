
      <nav class="navbar navbar-expand-lg bg-light" style="font-family: 'IBM Plex Sans Thai';">
         <div class="container">
            <div class="col-md-3 mobile_btn">
               <a class="navbar-brand" href="">
               <img class="logo" src="{{asset('images/Strongroot-Webportal.png')}}"></img>
               </a>
               <span class="icon" style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776;</span>
            </div>
            <div class="col-md-6 justify-content-md-strat collapse navbar-collapse show sidenav" id="mySidenav">
               <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
               <ul class="navbar-nav  mb-2 mb-lg-0" >
                  <li class="nav-item">
                     <a class="nav-link" aria-current="page" href="https://www.strongroot.co.th/">หน้าหลัก</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link px-md-4" href=""> สหกรณ์ </a>
                     <ul class="dropdown-menu">

                     @if ( Auth::check() && Auth::user()->hasRole('Union') )

                        <li>
                           <a href="{{ route('union.createUOV') }}" class="nav-link " data-attr="Crate order">สั่งซื้อสินค้า{{Auth::user()->role}}</a> 
                        </li>
                        <li>                 
                           <a href="{{ route('union.unionOL') }}" class="nav-link " href="#">สถานะคำสั่งซื้อ</a>
                        </li>
                        <li>                 
                           <a href="{{ route('union.unionOH') }}" class="nav-link" href="#">ประวัติการสั่งซื้อ</a>
                        </li>
                        

                     @else
                        <li>
                           <a class="nav-link show_Union_Login_modal" data-attr="Crate order">สั่งซื้อสินค้า</a> 
                        </li>
                        <li>                 
                           <a class="nav-link show_Union_Login_modal " href="#">สถานะคำสั่งซื้อ</a>
                        </li>
                        <li>                 
                           <a class="nav-link show_Union_Login_modal" href="#">ประวัติการสั่งซื้อ</a>
                        </li>

                     @endif
                        <li>                 
                           <a class="nav-link" href="price-table.html">แผนสินเชื่อ</a>
                        </li>
             
                        
                     </ul>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link px-md-4" href="">ข้อมูลโครงการ </a>
                     <ul class="dropdown-menu">
                        <li class="nav-item">
                           <a class="nav-link" href="STR_team.html">งานปิดตัวโครงการฯ</a>
                        </li>
                        <!--li class="nav-item">
                           <a class="nav-link" href="#">ทีมงาน</a>
                        </li-->
                     </ul>
                  </li>

                  @if ( Auth::check() && Auth::user()->hasRole('Union') )

                  <li class="nav-item">
                     <a class="nav-link px-md-4" href="{{ route('unionlogout') }}">Logout</a>
                  </li>

                  @else

                  <li class="nav-item">
                     <a class="nav-link px-md-4" href="{{ Config::get('app.url') }}/login">ทีมงาน Login</a>
                  </li>

                  @endif

               </ul>
            </div>
         </div>
      </nav>
      <!-- end header inner -->
      