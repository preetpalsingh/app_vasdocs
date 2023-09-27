@extends('layouts.web-app')

@section('title', $title)

@section('content')
      <section class="frist">
         <div class="container">
            <div class="row">
               <div class="n_three" style="margin-bottom: 25px">
                  <div class="col-md-12">
                     <img class="banner" src="{{asset('images/mainbanner.jpg')}}"></img>
                  </div>
                  <div class="n_two">
                     <div class="container">
                        <div class="col-md-12">
                            <div class="ns_Voluptatum">
                              <p class="pera"></p>
                           </div>
                           <h2 class="sub_headding" style="font-family: 'IBM Plex Sans Thai'; font-weight: 500;">เน็ตรายเดือนราคาพิเศษที่ Strongroot ที่เดียว</h2>
                           <p class="pera">ครั้งแรกของแพ็กเกจรายเดือนสุดคุ้ม ที่ให้คุณเลือกอุปกรณ์ได้ตามไลสไตล์ที่ชอบ ไม่ว่าคุณจะเป็นสาย Social สาย Entertainment สาย Game
สายโทร หรือสายเดินทาง ก็สนุกได้เต็มที่ไม่มีอั้น พร้อมเน็ตเต็มสปีดเยอะจุใจ และใช้งานได้เต็มสปีด  10GB ต่อเดือน</p>
							<br>
							<div style="float: left">
							<img src="{{asset('/images/str-qr.png')}}" width="200">
							</div>
							<div><h2 class="sub_headding" style="font-family: 'IBM Plex Sans Thai'; font-weight: 500;">สอบถามรายละเอียด</h2>
							<p class="para" style="font-size: 20px;">Call Center : 02-2047998<br>
							<a href=" https://line.me/ti/p/%40strongroot"><span style="font-size: 20px; font-family:'IBM Plex Sans Thai';">Line Official @strongroot</span></a></p>
							</div>
						</div>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="in_side">
                     <div class="ns-card headding">
                        <h2 class="one" style="font-family: 'IBM Plex Sans Thai'; font-weight: 500;">Dtac</h2>
                     </div>
                     <div class="ns-card pera">
                        <p class="ap" style="font-size:15px;">รายละเอียด<br>
อินเตอร์เน็ต ไม่ลดสปีด 10Mbps ปริมาณ 100GB (ระยะเวลการใช้งาน 24 เดือน)<br>
<br>
โทรฟรีในเครือข่ายฟรี 24 ชั่วโมง (ครั้งละ 15 นาที) ส่วนเกินจาก 15 นาที คิดอัตรา 0.99 สตางค์/นาที<br>
<br>
SMS ข้อความละ 2 บาท, MMS ข้อความละ 3 บาท<br>
<br>
- ซิมดังกล่าวเป็นชิมระบบเติมเงิน ชำระส่วงหน้า 2 ปี ไม่สามารถเปลี่ยนแปลงหรือแก้ไขได้<br><hr>
                        </p>
                     </div>
                  </div>
               </div>
               <!---------------?-9---------->
               <div class="col-md-4">
                  <div class="in_side">
                     <div class="ns-card headding">
                        <h2 class="two" style="font-family: 'IBM Plex Sans Thai'; font-weight: 500;">AIS</h2>
                     </div>
                     <div class="ns-card pera" style="height: auto;">
                        <p class="ap" style="font-size:15px;">รายละเอียด<br>
เน็ตไม่อั้นความเร็วสูงสุด 10 Mbps. ปริมาณ 100 GB. ต่อเดือน<br>
<br>
โทรฟรีในเครือข่าย AIS ครั้งละ 30 นาที นาทีที่ 31 เป็นต้นไป จะคิดนาทีละ 1 บ. <br>
โทรนอกเครือข่าย 60 นาทีต่อเดือน ค่าโทรส่วนเกินนาทีละ 1 บาท <br>
ส่งข้อความสั้น (SMS) คิดค่าบริการข้อความละ 2 บาท <br>
<br>
- ซิมดังกล่าวเป็นชิมระบบเติมเงิน ชำระส่วงหน้า 2 ปี ไม่สามารถเปลี่ยนแปลงหรือแก้ไขได้<br><hr>
                        </p>
                     </div>
                  </div>
               </div>
               <!---------------?-9---------->
               <div class="col-md-4">
                  <div class="in_side">
                     <div class="ns-card headding">
                        <h2 class="three" style="font-family: 'IBM Plex Sans Thai'; font-weight: 500;">TRUE MOVE</h2>
                     </div>
                     <div class="ns-card pera">
                        <p class="ap" style="font-size:15px;">รายละเอียด<br>
เล่นเน็ทไม่อั้นไม่ลดสปีด 10 Mbps ปริมาณ 100GBต่อเดือน นาน 12 เดือน<br>
<br>
โทรฟรีในเครือช่าย 24 ชม<br>
SMS ข้อความละ 2 บาท<br>
MMS ข้อความละ 3 บาท<br>
<br>
- ซิมดังกล่าวเป็นชิมระบบเติมเงิน ชำระส่วงหน้า 2 ปี ไม่สามารถเปลี่ยนแปลงหรือแก้ไขได้<br><hr>
                        </p>
                     </div>
                  </div>
               </div>
               <!---------------?-9---------->
            </div>
         </div>
      </section>
      <section class="solution">
         <div class="container">
            <div class="Package ">
               <div class="row">
                  <div class="col-md-12">
                     <img class="banner" src="{{asset('images/phone_banner.jpg')}}"></img>
                  </div>

                  <div class="col-md-8">
			
                     <div class="product_search_box">
                     
                        <h5>Advanced Search</h5>
                     
                        <div class="select_cloum" style="width: 280px;">
                           <select class="form-select sp_device" aria-label="Default select example">
                           <option selected="SAMSUNG">SAMSUNG</option>
                           <option value="APPLE">APPLE</option>
                           </select>
                        </div>
                        
                        <div class="form-outline" style="width: 280px;">
                           <input id="search-focus" type="search" class="form-control sp_device_search" placeholder="Search"> <i class="fas fa-search" aria-hidden="true"></i>
                        </div>

                        <a 
                        data-toggle="tooltip" title="Clear Search" class="btn btn-primary sp_reset_seacrh"  style=""><i class="fas fa-sync-alt" aria-hidden="true"></i></a>
                     
                     </div>
                     
                  </div>
                  
                  <div class="col-md-12 pt-3 ps-4 pe-4"  id="sp_load_more_container" style="position: relative;">
                     <div class="row ">

                        @foreach ($data as $key => $row)

                           @php 

                              $last_order_id = $row->id;

                           @endphp

                            <div class="col-md-4">
                            <div class="ns-card_1">
                                <h2  class="mb-sm-2">{{ $row->title }}</h2>
                                <div class="ns_card_2">
                                    <div class="row">
                                        <div class="col-md-8 ">
                                        <img class="h_s" src="{{ $row->image }}"></img>
                                        </div>
                                        <div class="col-md-4 ns_height">
                                        <label for="lname" class="sp_simcard sp_simcard_{{ $row->id }} " data-id="{{ $row->id }}">AIS</label><br>
                                        <label for="lname" class="sp_simcard sp_simcard_{{ $row->id }} " data-id="{{ $row->id }}">DTAC</label><br>
                                        <label for="lname" class="sp_simcard sp_simcard_{{ $row->id }} " data-id="{{ $row->id }}">TRUE</label><br><span> เลือกค่าย</span>
                                        </div>
                                    </div>
                                    <div class="ns_card_3">
                                        <p class="pera mt-4 p-0">ราคาสินค้ารวม Internet <span class="ps-2">{{ number_format($row->price, 2) }}</span><span class="ps-2">บาท</span></p>
                                        <p class="pera m-0 p-0">Internet Package <span class="ps-2"> 24 </span><span class="ps-2">เดือน</span></p>
                                        <p class="pera m-0 p-0">อัตราการผ่อน <span class="text-danger ps-2" style="font-size:18px; font-weight:600;">{{ number_format(ceil(($row->price)/24), 2) }}</span> <span class="ps-2">บาท</span></p>
                                        <p class="pera m-0 p-0">*ราคาสินค้าไม่รวมดอกเบี้ยเงินกู้</p>
										         <p class="pera m-0 p-0">**สินค้าจัดส่งแบบคละสี</p>
                                    </div>
                                </div>
                                <div class="ns_card_4 mt-3 ">
                                    <a href="{{ $row->link_spec }}" class="btn  number" target="_blank">สเปคเครื่อง</a>


                                    {{-- @if( Cookie::get('sp_strong_root_union_membership_detail')!==null )
                                    
                                       <a  class="btn btn-primary ADD union_membership_quotation_modal" data-id="{{ $row->id }}">ขอใบเสนอราคา</a>

                                     --}}

                                       <a  class="btn btn-primary ADD show_Union_Membership_modal"  data-id="{{ $row->id }}">ขอใบเสนอราคา</a>

                                    {{--

                                    @else

                                       <a  class="btn btn-primary ADD show_Union_Membership_modal"  data-id="{{ $row->id }}">ขอใบเสนอราคา</a>

                                    @endif

                                    --}}

                                    
                                    	
                                </div>
                            </div>
                            </div>
                        
                        @endforeach

                     </div>

                     <div class="col-md-12 text-center">

                        <a class="btn center sp_load_more" > load more</a>

                        <input type="hidden" id="last_order_id" value="{{$last_order_id}}">
                        
                     </div>

                  </div>
               </div>
               <!---------------?-8---------->
               <!--div class="col-md-12 text-center">
                  <a class="btn center" href="#"> load more</a>
               </div>
               <-------------?----------->
            </div>
         </div>
         </div>
      </section>

      <!-- Union Login Modal -->

      <div class="modal fade" id="Union_Login_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
                  
               <form class="sp_Union_Login_form">

                  <div class="modal-header">
                     <h1 class="modal-title fs-5" id="exampleModalLabel">Union Login</h1>
                     <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="color: #fff !important;">×</span></button>
                  </div>

                  <div class="modal-body">

                      
                     <div class="msg_status" ></div>
                        
                     <div class="form-horizontal">

                        <div class="mb-3">
                           <label for="Union-name" class="col-form-label">Email</label>
                           <input type="email" class="form-control" id="email" required>
                        </div>

                        <div class="mb-3">
                           <label for="ID" class="col-form-label">Password</label>
                           <input type="password" class="form-control" id="password" required>
                        </div>
                     
                     </div>

                  </div>

                  <div class="modal-footer">

                     <button type="button" class="btn btn-secondary btn-outline-warning" data-bs-dismiss="modal">Close</button>
                     <button type="submit" class="btn btn-warning">Login</button>

                  </div>

               </form>

            </div>
         </div>
      </div>

      <!-- Union Membership verification Modal -->

      <div class="modal fade" id="Union_Membership_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
                  
               <form class="sp_Union_Membership_form">

                  <div class="modal-header">
                     <h1 class="modal-title fs-5" id="exampleModalLabel">Union Membership Verification</h1>
                     <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="color: #fff !important;">×</span></button>
                  </div>

                  <div class="modal-body">

                      
                     <div class="msg_status" ></div>
                        
                     <div class="form-horizontal">

                        <div class="mb-3">
                           <label for="Union-name" class="col-form-label">Union Name</label>
                           <input type="text" class="form-control" id="union_name" required>
                        </div>

                        <div class="mb-3">
                           <label for="ID" class="col-form-label">User ID</label>
                           <input type="text" class="form-control" id="union_member_id" required>
                        </div>

                        <div class="mb-3">
                           <label for="Union-name" class="col-form-label">Name</label>
                           <input class="form-control" name="to_detail" id="to_detail" required>
                        </div>

                        <div class="mb-3">
                           <label for="ID" class="col-form-label">Address</label>
                           <textarea class="form-control" name="main_shipping_location" id="main_shipping_location" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                           <label for="ID" class="col-form-label">Quantity</label>
                           <input type="number" class="form-control" name="quantity" id="quantity" min="1" required>
                        </div>
                     
                     </div>

                  </div>

                  <div class="modal-footer">

                     <button type="button" class="btn btn-secondary btn-outline-warning" data-bs-dismiss="modal">Close</button>
                     <button type="submit" class="btn btn-warning">Verify</button>
                     <input type="hidden"  name="simcard" id="simcard" />
                     <input type="hidden"  name="product_id" id="product_id" />

                  </div>

               </form>

            </div>
         </div>
      </div>

      @if( Cookie::get('sp_strong_root_union_membership_detail')!==null ) 

      <!-- Union Membership QUOTATION Modal -->

      <div class="modal fade" id="Union_Membership_Quotation_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
                  
               <form action="{{ route('createUMOQ') }}" method="POST" class="sp_Union_Membership_Quotation_form">

                  <div class="modal-header">
                     <h1 class="modal-title fs-5" id="exampleModalLabel">Quotation Detail</h1>
                     <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="color: #fff !important;">×</span></button>
                  </div>

                  <div class="modal-body">

                      
                     <div class="msg_status" ></div>
                        
                     <div class="form-horizontal">

                        <div class="mb-3">
                           <label for="Union-name" class="col-form-label">To</label>
                           <textarea class="form-control" name="to_detail" id="to_detail" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                           <label for="ID" class="col-form-label">Main Shipping Location</label>
                           <textarea class="form-control" name="main_shipping_location" id="main_shipping_location" rows="3" required></textarea>
                        </div>

                        <!--div class="mb-3">
                           <label for="ID" class="col-form-label">P.O. No.</label>
                           <input type="text" class="form-control" name="po_number" id="po_number" required>
                        </div-->

                        <div class="mb-3">
                           <label for="ID" class="col-form-label">Quantity</label>
                           <input type="number" class="form-control" name="quantity" id="quantity" min="1" required>
                        </div>
                     
                     </div>

                  </div>

                  <div class="modal-footer">

                     <button type="button" class="btn btn-secondary btn-outline-warning" data-bs-dismiss="modal">Close</button>
                     <button type="submit" class="btn btn-warning">Verify</button>
                     <input type="hidden"  name="simcard" id="simcard1" />
                     <input type="hidden"  name="product_id" id="product_id1" />
                     {!! csrf_field() !!}

                  </div>

               </form>

            </div>
         </div>
      </div>

      @endif

      @endsection
      
      @push('page_scripts')
   
      <!-- Custom JS -->

      <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
      <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

      <script src="{{ asset('js/frontend_index.js?35') }}"></script>

      <script>

        $(document).ready(function(){

         $("body").tooltip({ selector: '[data-toggle=tooltip]' });
        });


    </script>

    <style>
      .tooltip {
         width: 200px !important;
      } 

      .sp_custom_loader::before {
    height: 1em;
    width: 1em;
    display: block;
    position: absolute;
    top: 50%;
    left: 50%;
    margin-left: -.5em;
    margin-top: -.5em;
    content: "";
    -webkit-animation: spin 1s ease-in-out infinite;
    animation: spin 1s ease-in-out infinite;
    background: url(images/loader.svg) center center;
    background-size: cover;
    line-height: 1;
    text-align: center;
    font-size: 2em;
    color: rgba(0,0,0,.75);
}



@-webkit-keyframes spin{0%{transform:rotate(0deg)}to{transform:rotate(1turn)}}

@keyframes spin{0%{transform:rotate(0deg)}to{transform:rotate(1turn)}}.wc-block-grid{text-align:center}
</style>

      @endpush