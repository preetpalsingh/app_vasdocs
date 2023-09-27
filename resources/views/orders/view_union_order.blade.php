
@extends('layouts.web-app')
 
{{-- @section('title', $title) --}}

@section('content')

@include('frontend.common.print_header_with_header_footer')

  <div class="container" id="sp_print" style="margin-top: 30px;">

    @php

        $union_detail   = $data['union_detail'];
        $order_items   = $data['order_detail']->order_items;

    @endphp
      
    <div class="row sp_create_order_loader" style="position: relative;">
        <div class="col-12">
            <div class="card mb-0">
                <div class="card-body p-0">
                    <div class="row pt-5 pl-5 pr-5 ">
                        <div class="col-md-6">
                           <h1 class="logo_headding" style="font-size: 18px;">ออกโดย <br>{{$union_detail->first_name}} {{$union_detail->last_name}}</h1>
                        </div>

                        <div class="col-md-6 text-right pt-3">
                           <!--  <p class="font-weight-bold mb-1">Invoice #550</p>
                            <p class="text-muted">Due to: 4 Dec, 2019</p> -->
							
							<p class="font-weight-bold mb-0" style="font-size:25px;">ใบสั่งซื้อสินค้า</p>
                        </div>
                    </div>
					
					

                   

                    <div class="row pt-0 pl-5 pr-5 pb-5">
                        <div class="col-6">
                            <p class="font-weight-bold mb-0"><address>{{$union_detail->address}}</address></p>               
                            <p class="mb-0">โทร {{$union_detail->mobile_number}}</p>
                        </div>

                        <div class="col-md-6 text-right p-l-5">
                          
                        </div>
                    </div>
					
					
					<!-- ----------------- -->
					
					<div class="row px-5">
                        
                           
								<div class="col-md-4">
                                    <p class="font-weight-bold mb-0">ใบสั่งซื้อถึง:</p>               
									<p class="mb-0">บริษัท สตรองรูท จำกัด </p>
									<p class="mb-0">60 รามคำแหง 12 หัวหมาก บางกะปิ</p>
									<p class="mb-0">กรุงเทพมหานครฯ 10240</p>
									<p class="mb-0"><b>โทร (66)2-318-1026</b></p>
								</div>

								
								
								<div class="col-md-4">
									<p class="font-weight-bold mb-0">ที่อยุ่จัดส่งหลัก:</p>
									<p class="mb-0">{{$union_detail->first_name}}</p>
									<p class="mb-0">
									<address>ที่อยู่ {{$union_detail->address}}</address>
									</p>
								</div>

								
								
								<div class="col-md-4">
									<p class="font-weight-bold mb-0">P.O. NUMBER:</p>               
									<p class="mb-0"><b>{{$data['order_detail']->po_number}}</b></p>
									<p class="font-weight-bold mb-0">วันที่ :{{substr($data['order_detail']->created_at,0,10)}}</p>
									
								</div>

							
                    </div>
					
					
					
					<!-- ----------------- -->

                    <div class="row  pt-5 pb-0 pl-5 pr-5">
                        <div class="col-md-12">
                            <table class="table table-bordered mb-0">
                                <thead class="thead-light border-dark">
                                    <tr>
                                        <th class=" text-uppercase small font-weight-bold">รายการที่ (No.)</th>
                                        <th class="text-uppercase small font-weight-bold">อุปกรณ์</th>
                                        <th class=" text-uppercase small font-weight-bold">SIMCARD 24 เดือน</th>
                                        <th class=" text-uppercase small font-weight-bold">จำนวนเครื่อง</th>
                                        <th class=" text-uppercase small font-weight-bold">ราคาต่อหน่วย</th>
                                        <th class=" text-uppercase small font-weight-bold text-right">รวม</th>
                                    </tr>
									
									

                                </thead>
                                <tbody class="sp_product_row">

                                    @php

                                    $total_price = 0;

                                    $sub_total_price = 0;

                                    $total_tax = 0;

                                    $pro_sub_total_price = 0;

                                    $key = 0;

                                    @endphp

                                    @foreach ( $order_items as $row )

                                        @php

                                        $pro_sub_total_price = $row->product_quantity * $row->price;
                                        $sub_total_price = $sub_total_price + $pro_sub_total_price;

                                        $key = $key + 1;

                                        @endphp

                                        <tr>
                                            <td>{{ $key }}</td>
                                            <td>{{$row->title}}</td>
                                            <td>{{$row->simcard_provider}}</td>
                                            <td>{{$row->product_quantity}}</td>
                                            <td class="">{{ number_format($row->price, 2) }}</td>
                                            <td class="text-right">{{ number_format($pro_sub_total_price, 2) }}</td>
                                        </tr>

                                        @if ( !empty( $row->receiver_name ) || !empty( $row->receiver_delivery_address ) )

                                        <tr>
                                            <td></td>
                                            <td class="">Alternative </td>
                                            <td class="">{{$row->receiver_name}}, {{$row->receiver_delivery_address}} </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>

                                        @endif

                                    @endforeach

                                    @php

                                    $total_tax = $sub_total_price * 0.07;
                                    $total_price = $sub_total_price + $total_tax;

                                    @endphp

                                    <!--tr>
                                        <td>1</td>
                                        <td>Samsung Galaxy A03s</td>
										<td>Ram 16GB</td>
                                        <td>2</td>
                                        <td class="">$[4.00]</td>
                                        <td class="text-right">$[4.00]</td>
                                    </tr>
                                    <tr>
                                        <td></td>
										<td class="">Alternative </td>
                                        <td class="">Union 1 Test 9999999 </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr-->
									
									
									
							<table class="table table-bordered col-5" align="right">
                               
							    <tbody >
                                    
									
									<!-- total -->
									 <tr class="col-5">
                                        <td class="col-6">ราคารวม</td>
										 <td class="col-3 text-right order_sub_total">{{ number_format($sub_total_price, 2) }}</td>
                                       
                                    </tr>
									<tr class="col-5">
                                        
										 <td class="col-6">ภาษีมูลค่าเพิ่ม (7%) </td>
                                        <td class="col-3  text-right order_sales_tax">{{ number_format($total_tax, 2) }}</td>
										
                                    </tr>
									<tr class="col-5">
                                        <td class="col-6">ราคารวมภาษีมูลค่าเพิ่ม</td>
                                        <td class="col-3 text-right order_total">{{ number_format($total_price, 2) }}</td>
                                       
                                    </tr>
									<!-- total -->
									
                                </tbody>
                            </table>
									
									
									
							  </tbody>
							 </table>
							
					 
					 </div>
                </div>
				
				
				
                <div class="row pt-0 pb-5 pl-5 pr-5 mb-5 ml-5">
				  
                  <div class="col-md-6">
                              
                              
                      <ul class="list-group col-12 ns_list_no">
                        <li class="border-0 py-0">กรุณาพิมพ์และเซ็นกำกับ พร้อมประทับตรา (ถ้ามี)</li>
                        <li class="border-0 py-0">หรือ หากใช้ฟอร์มของท่านเอง กรุณาระบบข้อความ ราคา และรายละเอียดอื่นๆให้ครบถ้วน</li>
						<li class="border-0 py-0">ส่งเอกสารตัวจริงพร้อมสำเนา 1 ชุด มายังฝ่ายจัดซื้อ</li>
						<li class="border-0 py-0">บริษัท สตรองรูท จำกัด ที่อยู่ 60 รามคำแหง 12 หัวหมาก, บางกะปิ, กรุงเทพมหานครฯ 10240</li>
                        <li class="border-0 py-0"></li>
						<li class="border-0 py-0"></li>
                      </ul>
                              
                  </div>
                  
                  
                  <div class="col-md-6 d-flex lex-column align-items-end" style="margin-top: 250px;">
                              
                          <div class="col-12">
      
                              <div class="col-12 border-top d-flex justify-content-between">	
                                  <p class="" style="width: 50%;float: left;border-top: 1px solid #dee2e6 !important;">ลายเซ็นผู้มีอำนาจ</p><p style="width: 50%;text-align:right; float: left;border-top: 1px solid #dee2e6 !important;">ประทับตรา (ถ้ามี)</p>	
                              
                              </div>
                              
                          </div>
                          
                          
                  </div>
              
          </div>
      </div>
  </div>

  @php

  if( $data['options'] == 'show' ){

  @endphp
  
  
  <div class=" bg-light text-white p-5 pt-2 shadow rounded-bottom sp_btn_container">
                  
      <div class="row justify-content-end" style="display: flex;padding-top: 50px;">
      
          <div class="col-2 d-flex flex-column ">
           
          
            <a href="{{ route('union.vieworderUQF',['order_id' => $data['order_detail']->id,'download'=>'pdf']) }}" class="btn btn-success mb-4 float-right">บันทึก PDF</a>
              
            <button onclick="window.print()" type="button" class="btn btn-primary mb-4 float-right clickbind">สั่งพิมพ์ /PRINT</button>            
			@if ( Auth::check() && Auth::user()->hasRole('Union') )
            <button type="button" onclick="location.href='https://www.strongroot.co.th/';"  class="btn btn-danger mb-0 float-right">กลับหน้าหลัก</button>
            @else
			<button type="button" onclick="window.close();"  class="btn btn-danger mb-0 float-right">ปิดหน้า</button>				
            @endif  
              <!--button type="button" class="btn btn-danger mb-0 float-right">BACK</button-->
      
          </div>
          
      </div>

      <div class="row justify-content-end" style="display: flex;">
      
          <div class="col-12 d-flex flex-column ">
           
              <div class="msg_status"></div>
              
          </div>
          
      </div>
  
  </div>

  @php

  }

  @endphp
                  
                  
                  
                  
                  
  
  <!--div class="text-light mt-5 mb-5 text-center small">by : <a class="text-light" target="_blank" href="http://totoprayogo.com">totoprayogo.com</a></div-->

</div>




  </div>
  </div>
  </div>

  @include('frontend.common.print_footer')

@endsection