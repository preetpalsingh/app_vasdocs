@include('frontend.common.print_header')

@php

    $user_detail   = $data['user_detail'];
    $union_detail   = $data['union_detail'];
    $order_items   = $data['order_detail']->order_items;

@endphp
      
    <div class="row">
        <div class="col-12">
            <div class="card mb-0">
                <div id="print" class="card-body p-0">
                    <div class="row pt-5 pl-5 pr-5 ">
                        <div class="col-md-6">
							<div stlye="float: left;"><img src="{{asset('/images/str-logo.png')}}" width="100">
							</div>

                        </div>

                        <div class="col-md-6 text-right pt-3">
                           <!--  <p class="font-weight-bold mb-1">Invoice #550</p>
                            <p class="text-muted">Due to: 4 Dec, 2019</p> -->
							<p class="font-weight-bold mb-0" style="font-size:25">ใบสั่งซื้อสินค้า<br></p>
							<p class="font-weight-bold mb-0">{{ $data['order_detail']->order_id }}</p>
                        </div>
                    </div>
					
					

                   

                    <div class="row pt-0 pl-5 pr-5 pb-5">
                        <div class="col-md-6">
                            <p class="font-weight-bold mb-0"><span style="font-size: 20px;">บริษัท สตรองรูท จำกัด</span><br>
							เลขที่ 60 รามคำแหง 12 หัวหมาก บางกะปิ <br>กรุงเทพมหานครฯ 10240 </p>               
                            <p class="mb-0">โทร (66)2-318-1026</p>
                        </div>

                        <div class="col-md-6 text-right p-l-5">
                          
                        </div>
                    </div>
					
					
					<!-- ----------------- -->
					
					<div class="row px-5">
                        
                           
								<div class="col-md-4">
									<p class="font-weight-bold mb-0">ใบสั่งซื้อถึง:</p>               
									<p class="mb-0"><b>{{$user_detail->first_name}}</b></p>
									<p class="mb-0">{{$user_detail->last_name}}</p>
									<p class="mb-0"><address>{{$user_detail->address}}</address></p>
									<p class="mb-0"><b>เบอร์โทร ({{$user_detail->mobile_number}})</b></p>
								</div>

								
								
								<div class="col-md-4">
									<p class="font-weight-bold mb-0">ที่อยู่จัดส่งหลัก :</p> 
                                    <p class="mb-0"><b>เบอร์โทร ({{$union_detail->address}})</b></p>              
									<!--p class="mb-0">ผู้รับสินค้า สหกรณ์ออมทรัพ - Receiver Name</p>
									<p class="mb-0">ที่อยู่จัดส่งหลักสหกรณ์ Address</p-->
								</div>

								
								
								<div class="col-md-4">
									<p class="font-weight-bold mb-0">หมายเลข P.O.:</p>               
									<p class="mb-0"><b>{{$data['order_detail']->po_number}}</b></p>
									<!--p><i>[refer to quotation number]</i></p-->
									
								</div>

							
                    </div>
					
					
					
					<!-- ----------------- -->

                    <div class="row  pt-5 pb-0 pl-5 pr-5">
                        <div class="col-md-12">
                            <table class="table table-bordered mb-0">
                                <thead class="thead-light border-dark">
                                    <tr>
                                        <th class=" text-uppercase small font-weight-bold">รายการ</th>
                                        <th class="border-0 text-uppercase small font-weight-bold">รายการสั่งซื้อ</th>
                                        <th class=" text-uppercase small font-weight-bold">จำนวน</th>
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

                                @endphp

                                @foreach ( $order_items as $row )

                                    @php

                                    $pro_sub_total_price = $user_detail->product_quantity * $row->price;
                                    $sub_total_price = $sub_total_price + $pro_sub_total_price;

                                    @endphp

                                    <tr>
                                        <td>1</td>
                                        <td>{{$row->title}}</td>
                                        <td>{{$user_detail->product_quantity}}</td>
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
                                    </tr>

                                    @endif

                                @endforeach

                                @php

                                $total_tax = $sub_total_price * 0.07;
                                $total_price = $sub_total_price + $total_tax;

                                @endphp

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

                        <li class="border-0 py-0">หากข้อมูลในเอกสารนี้ไม่ถูกต้อง <br>ขอให้แจ้งกลับมายังบริษัทภายใน 7 วัน</li>
                        <li class="border-0 py-0">หากไม่มีการแจ้งกลับจะถือว่าเอกสารนี้สมบูรณ์</li>
						<li class="border-0 py-0">ฝ่ายจัดซื้อ <br>บริษัท สตรองรูท จำกัด ที่อยู่ 60 รามคำแหง 12 หัวหมาก, บางกะปิ, กรุงเทพมหานครฯ 10240</li>
                        <li class="border-0 py-0"></li>
						<li class="border-0 py-0"></li>						
                      </ul>
                              
                  </div>
                  
                  
                  <div class="col-md-6 d-flex lex-column align-items-end" style="margin-top: 250px;">
                              
                          <div class="row col-12">
      
                              <div class="col-12 border-top d-flex justify-content-between">	
                                  <p class="" style="width: 50%;float: left;">ลายเซ็นผู้มีอำนาจ</p><p style="width: 50%;text-align:right; float: left;">ประทับตรา (ถ้ามี)</p>	
                              
                              </div>
                              
                          </div>
                          
                          
                  </div>
              
          </div>
      </div>
  </div>

  @php

  if( $data['options'] == 'show' ){

  @endphp
  
  
  <div class="col-12  bg-light text-white p-5 pt-2 shadow mb-5 rounded-bottom sp_btn_container">
                  
      <div class="row justify-content-end" style="display: flex;">
      
          <div class="col-2 d-flex flex-column ">
           
              <a href="{{ route('admin.vieworderVQ',['download'=>'pdf','order_id' => $data['order_id'], 'vendor_id' => $data['vendor_id'], 'product_id' => $data['product_id'], 'order_item_id' => $data['order_item_id']  ]) }}" class="btn btn-success mb-4 float-right">บันทึก PDF</a>
              
              <button onclick="window.print()" type="button" class="btn btn-primary mb-4 float-right clickbind">PRINT</button>            
    
              <a href="{{ url()->previous() }}" class="btn btn-danger mb-0 float-right">BACK</a>
      
          </div>
          
      </div>
  
  </div>

  @php

  }

  @endphp
                  
                  
                  
                  
                 

</div>




  </div>
		

@include('frontend.common.print_footer')