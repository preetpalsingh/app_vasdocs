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
							<p class="font-weight-bold mb-0" style="font-size:25"><font _mstmutation="1" _msttexthash="256386" _msthash="1">QUOTATION</font><br></p>
							<!--p class="font-weight-bold mb-0" _msttexthash="82485" _msthash="2">STR-000001</p-->
                        </div>
                    </div>
					
					

                   

                    <div class="row pt-0 pl-5 pr-5 pb-5">
                        <div class="col-md-6">
                            <p class="font-weight-bold mb-0" _msttexthash="3392194" _msthash="3"><span style="font-size: 20px;" _istranslated="1">Strong Root Co., Ltd.</span><br _istranslated="1">60 Ramkhamhaeng 12 Huamark, Bangkapi<br _istranslated="1">Bangkok 10240 </p>               
                            <p class="mb-0" _msttexthash="199875" _msthash="4">Call (66)2-318-1026</p>
                        </div>

                        <div class="col-md-6 text-right p-l-5">
                          
                        </div>
                    </div>
					
					
					<!-- ----------------- -->
					
					<div class="row px-5">
                        
                           
								<div class="col-md-4">
									<p class="font-weight-bold mb-0" _msttexthash="129233" _msthash="5">Orders to:</p>               
									<address>{{$all_detail->to_detail}}</address>
									<p class="mb-0"><b _msttexthash="203437" _msthash="8">Call (66)2-318-1026</b></p>
								</div>

								
								
								<div class="col-md-4">
									<p class="font-weight-bold mb-0" _msttexthash="439491" _msthash="9">Main shipping address :</p>               
									<address>{{$all_detail->main_shipping_location}}</address>
								</div>

								
								
								<div class="col-md-4">
									<p class="font-weight-bold mb-0" _msttexthash="35581" _msthash="12">P.O.:</p>               
									<p class="mb-0"><b>{{$all_detail->po_number}}</b></p>
									
								</div>

							
                    </div>
					
					
					
					<!-- ----------------- -->

                    <div class="row  pt-5 pb-0 pl-5 pr-5">
                        <div class="col-md-12">
                            <table class="table table-bordered mb-0">
                                <thead class="thead-light border-dark">
                                    <tr>
                                        <th class=" text-uppercase small font-weight-bold">No.</th>
                                        <th class="border-0 text-uppercase small font-weight-bold" >EQUIPMENT</th>
                                        <th class=" text-uppercase small font-weight-bold" >SELECT SIM.</th>
                                        <th class=" text-uppercase small font-weight-bold" >NUMBER OF MACHINES</th>
                                        <th class=" text-uppercase small font-weight-bold text-right">UNIT PRICE</th>
                                        <th class=" text-uppercase small font-weight-bold text-right">COMBINE</th>
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
                                        <td class="col-6" >Total Price</td>
										 <td class="col-3  text-right">{{ number_format($sub_total_price, 2) }}</td>
                                       
                                    </tr>
									<tr class="col-5">
                                        
										 <td class="col-6">VALUE ADDED TAX (7%) </td>
                                        <td class="col-3  text-right" >{{ number_format($total_tax, 2) }}</td>
										
                                    </tr>
									<tr class="col-5">
                                        <td class="col-6" >All prices are inclusive of VAT.</td>
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
                        <li class="border-0 py-0">กรุณาพิมพ์และเซนกำกับ พร้อมประทับตรา (ถ้ามี)</li>
                        <li class="border-0 py-0">หรือ หากใช้ฟอร์มของท่านเอง กรุณาระบบข้อความ ราคา และ <br>รายละเอียดอื่นๆให้ครบถ้วน</li>
                        <li class="border-0 py-0">ส่งเอกสารตัวจริงพร้อมสำเนา 1 ชุด มายังฝ่ายจัดซื้อ</li>
                        <li class="border-0 py-0">บริษัท สตรองรูท จำกัด ที่อยู่ 60 รามคำแหง 12 หัวหมาก, บางกะปิ, กรุงเทพมหานครฯ 10240</li>
                        <li class="border-0 py-0"></li>
                        <li class="border-0 py-0"></li>
                    </ul>
                              
                  </div>
                  
                  
                  <div class="col-md-6 d-flex lex-column align-items-end" style="margin-top: 250px;">
                              
                          <div class="row col-12">
      
                              <div class="col-12 border-top d-flex justify-content-between">	
                                  <p class="" style="width: 50%;float: left;" >Authority signature</p><p style="width: 50%;text-align:right; float: left;" >Seal (if applicable)</p>	
                              
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
         
        
            <a href="{{ route('createUMOQ',['to_detail' => $all_detail->to_detail, 'main_shipping_location' => $all_detail->main_shipping_location, 'po_number' => $all_detail->po_number, 'quantity' => $all_detail->quantity, 'simcard' => $all_detail->simcard, 'product_id' => $all_detail->product_id, 'download'=>'pdf']) }}" class="btn btn-success mb-4 float-right">บันทึก PDF</a>
              
            <button onclick="window.print()" type="button" class="btn btn-primary mb-4 float-right clickbind">PRINT</button>            
  
            <a href="{{ url()->previous() }}" class="btn btn-danger mb-0 float-right">BACK</a>
    
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