@include('frontend.common.print_header')

@php

$all_detail = $data['all_detail'];

@endphp

<div class="row sp_create_order_loader">
        <div class="col-12">
            <div class="card mb-0">
                <div class="card-body p-0">
                    <div class="row pt-5 pl-5 pr-5 ">
                        <div class="col-md-6">
							<div stlye="float: left;"><img src="{{asset('/images/str-logo.png')}}" width="100">
							</div>

                        </div>

                        <div class="col-md-6 text-right pt-3">
                           <!--  <p class="font-weight-bold mb-1">Invoice #550</p>
                            <p class="text-muted">Due to: 4 Dec, 2019</p> -->
							<p class="font-weight-bold mb-0" style="font-size:25">ใบเสนอราคา<br></p>
							<p class="font-weight-bold mb-0" style="font-size:18">วันที่ : @php echo date('Y-m-d'); @endphp <br></p>
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
									<p class="font-weight-bold mb-0" _msttexthash="129233" _msthash="5">ใบเสนอราคาถึง :</p>               
									<address>{{$all_detail->to_detail}}</address>
									<p class="mb-0"></p>
								</div>

								
								
								<div class="col-md-4">
									<p class="font-weight-bold mb-0">ที่อยู่จัดส่ง </p>              
									<address>{{$all_detail->main_shipping_location}}</address>
								</div>

								
							

							
                    </div>
					
					
					
					<!-- ----------------- -->

                    <div class="row  pt-5 pb-0 pl-5 pr-5">
                        <div class="col-md-12">
                            <table class="table table-bordered mb-0">
                                <thead class="thead-light border-dark">
                                    <tr>
                                        <th class=" text-uppercase small font-weight-bold">No.</th>
                                        <th class="border-0 text-uppercase small font-weight-bold" >รายการสั่งซื้อ</th>
                                        <th class=" text-uppercase small font-weight-bold" >ซิมโทรศัพท์</th>
                                        <th class=" text-uppercase small font-weight-bold" >จำนวนเครื่อง</th>
                                        <th class=" text-uppercase small font-weight-bold text-right">ราคาต่อหน่วย</th>
                                        <th class=" text-uppercase small font-weight-bold text-right">รวม</th>
                                    </tr>
									
                                </thead>
                                <tbody>

                                    @php

                                    $total_price = 0;

                                    $sub_total_price = 0;

                                    $total_tax = 0;

                                    $pro_sub_total_price = 0;

                                    @endphp

                                    @foreach ( $all_detail->product_detail as $row )

                                        @php

                                        $pro_sub_total_price = $all_detail->quantity * $row->price;
                                        $sub_total_price = $sub_total_price + $pro_sub_total_price;

                                        @endphp

                                    <tr>
                                        <td >1</td>
                                        <td >{{$row->title}}</td>
                                        <td >{{$all_detail->simcard}}</td>
                                        <td class="" >{{$all_detail->quantity}}</td>
                                        <td class="text-right" >{{ number_format($row->price, 2) }}</td>
                                        <td class="text-right" >{{ number_format($pro_sub_total_price, 2) }}</td>
                                    </tr>
                                    
                                    @endforeach

                                    @php

                                    $total_tax = $sub_total_price * 0.07;
                                    $total_price = $sub_total_price + $total_tax;

                                    @endphp
									
							    </tbody>
                            </table>
                        
                            <table class="table table-bordered col-5" align="right">
                               
							    <tbody>
                                    
									
									<!-- total -->
									 <tr class="col-5">
                                        <td class="col-6" >ราคารวม</td>
										 <td class="col-3  text-right">{{ number_format($sub_total_price, 2) }}</td>
                                       
                                    </tr>
									<tr class="col-5">
                                        
										 <td class="col-6">ภาษีมูลค่าเพิ่ม (7%)  </td>
                                        <td class="col-3  text-right" >{{ number_format($total_tax, 2) }}</td>
										
                                    </tr>
									<tr class="col-5">
                                        <td class="col-6" >ราคารวมภาษีมูลค่าเพิ่ม</td>
                                        <td class="col-3 text-right" >{{ number_format($total_price, 2) }}</td>
                                       
                                    </tr>
									<!-- total -->
									
                                </tbody>
                            </table>
									
					 </div>
                </div>
				
				
				
                <div class="row pt-0 pb-5 pl-5 pr-5 mb-5 ml-5">
				  
                  <div class="col-md-6">
                              
                              
                    <ul class="list-group col-12 ns_list_no">
                        <li class="border-0 py-0"><br><br><br><b>กรุณานำเอกสารนี้ <br> ทำการสั่งซื้อสินค้ากับสหกรณ์ที่ท่านเป็นสมาชิกอยู่</b></li>
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
         
        
            <a href="{{ route('createUMOQ',['to_detail' => $all_detail->to_detail, 'main_shipping_location' => $all_detail->main_shipping_location, 'po_number' => $all_detail->po_number, 'quantity' => $all_detail->quantity, 'simcard' => $all_detail->simcard, 'product_id' => $all_detail->product_id, 'download'=>'pdf']) }}" class="btn btn-success mb-4 float-right">บันทึกเป็น PDF</a>
              
            <button onclick="window.print()" type="button" class="btn btn-primary mb-4 float-right clickbind">พิมพ์ [Print]</button>            
  
            <a href="{{ url()->previous() }}" class="btn btn-danger mb-0 float-right">กลับหน้าหลัก</a>
    
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

                    
                  
                  
                  
                 

</div>




  </div>

@include('frontend.common.print_footer')