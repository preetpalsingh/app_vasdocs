<html><head>
    <meta charset="utf-8">
    <title>Quotation</title>
    <link href="{{asset('css/print_bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <!--link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet" type="text/css" /-->
    <!--link href="{{ base_path().'/css/print_bootstrap.min.css' }}" rel="stylesheet" type="text/css" /-->
    <!--script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script-->

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link href="{{asset('css/print_style.css')}}" rel="stylesheet" type="text/css" />

    <style>
    body {
        margin-top: 20px;
        color: #2e323c;
        background: #f5f6fa;
        padding-left: 20px;
        padding-right: 20px;
        padding-top: 40px;
    }

    .invoice-container {
        padding: 1rem;
    }

    .invoice-container .invoice-header .invoice-logo {
        margin: 0.8rem 0 0 0;
        display: inline-block;
        font-size: 1.6rem;
        font-weight: 700;
        color: #2e323c;
    }

    .invoice-container .invoice-header .invoice-logo img {
        max-width: 130px;
    }

    .invoice-container .invoice-header address {
        font-size: 12pt;
        color: #000 !important;
        margin: 0;
		font-weight: 700;
    }

    .invoice-container .invoice-details {
        margin: 45px 0 0 0;
        padding: 1rem;
        line-height: 180%;
        background: transparent;
        color: #000 !important;
        font-size: 16px !important;
    }

    .invoice-container .invoice-details .invoice-num {
        text-align: right;
        font-size: 15px;
    }

    .invoice-container .invoice-body {
        padding: 45px 0 0 0;
    }

    .invoice-container .invoice-footer {
        text-align: center;
        font-size: 0.7rem;
        margin: 5px 0 0 0;
    }

    .invoice-status {
        text-align: center;
        padding: 1rem;
        background: #ffffff;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        margin-bottom: 1rem;
    }

    .invoice-status h2.status {
        margin: 0 0 0.8rem 0;
    }

    .invoice-status h5.status-title {
        margin: 0 0 0.8rem 0;
        color: #9fa8b9;
    }

    .invoice-status p.status-type {
        margin: 0.5rem 0 0 0;
        padding: 0;
        line-height: 150%;
    }

    .invoice-status i {
        font-size: 1.5rem;
        margin: 0 0 1rem 0;
        display: inline-block;
        padding: 1rem;
        background: #f5f6fa;
        -webkit-border-radius: 50px;
        -moz-border-radius: 50px;
        border-radius: 50px;
    }

    .invoice-status .badge {
        text-transform: uppercase;
    }




    .custom-table {
        border: 1px solid #e0e3ec;
    }

    .custom-table thead {
        background: #007ae1;
    }

    .custom-table thead th {
        border: 0;
        color: #000;
        background: #eee !important;
        font-size: 16px;
    }

    .custom-table>tbody tr:hover {
        background: #fafafa;
    }

    .custom-table>tbody tr:nth-of-type(even) {
        background-color: #ffffff;
    }

    .custom-table>tbody td {
        border: 1px solid #fff;
        font-size: 11pt;
        font-weight: 700;
        color: #000 !important;
    }


    .card {
        background: #ffffff;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        border: 0;
        margin-bottom: 1rem;
    }

    .text-success {
        color: #000 !important;
    }

    .text-muted {
        color: #9fa8b9 !important;
    }

    .custom-actions-btns {
        margin: auto;
        display: flex;
        justify-content: flex-end;
    }

    .custom-actions-btns .btn {
        margin: .3rem 0 .3rem .3rem;
    }

    .invoice-inner-container {
        border: 1px solid #000;
    }
	.ns_list_no li {
		list-style: auto;
		padding-left: 7px;
	}
	.ns_list_no li:nth-last-of-type(2){
           list-style: none;list-style: none;
    }
   .ns_list_no li:nth-last-of-type(1){
	   list-style: none;list-style: none;
    }
	.logo_headding {
		font-size: 71px;
		color: #0a6b4e;
	}
	

    .col-md-6 {
        width: 50% !important;
        float: left;
    }

    div#sp_print {
        width: 1130px !important;
        padding-top: 10px;
        
    padding-left: 10px !important;
    padding-right: 10px;
    }

    .row {
    width: 100%;
        display: block;
        float: left;
        
    margin-left: 0px;
    margin-top: 0px;
    }

    .col-md-4 {
        width: 33%;
        float: left;
    }

    .col-12.bg-light.text-white.p-5.pt-2.shadow.mb-5.rounded-bottom {
        
        display: block;
        float: left;
    }

    .card {
        display: block;
        float: left;
    }

    table.table.table-bordered.col-5 {
        width: 41.666667%;
    }

    @media print {

        body {
            background: #f5f6fa;
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
            padding-top: 20px;
        }

        .custom-table thead {
            background-color: #007ae1 !important;
            -webkit-print-color-adjust: exact;
        }

        a#clickbind {
            display: none;
        }

        .sp_btn_container{
            display: none !important;
        }
    }


    </style>


</head>

<body cz-shortcut-listen="true">

  <div class="container" id="sp_print">

@php

    $user_detail   = $data['union_detail'];
    $order_items   = $data['order_detail']->order_items;

@endphp
  
<div class="row sp_create_order_loader" style="position: relative;">
    <div class="col-12">
        <div class="card mb-0">
            <div class="card-body p-0">
                <div class="row pt-5 pl-5 pr-5 ">
                    <div class="col-md-6">
                       <h1 class="logo_headding" style="font-size: 18px;">ออกโดย <br>{{$user_detail->first_name}} {{$user_detail->last_name}}</h1>
                    </div>

                    <div class="col-md-6 text-right pt-3">
                       <!--  <p class="font-weight-bold mb-1">Invoice #550</p>
                        <p class="text-muted">Due to: 4 Dec, 2019</p> -->
                        
                        <p class="font-weight-bold mb-0" style="font-size:25">ใบสั่งซื้อสินค้า</p>
                    </div>
                </div>
                
                

               

                <div class="row pt-0 pl-5 pr-5 pb-5">
                    <div class="col-6">
                        <p class="font-weight-bold mb-0"><address>{{$user_detail->address}}</address></p>               
                        <p class="mb-0">{{$user_detail->mobile_number}}</p>
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
                                <p class="mb-0">Purchase Department</p>
                                <p class="mb-0"><address>สหกรณ์ออมทรัพย์ครูกรมสามัญศึกษา จำกัด
319 พิษณุโลก แขวง ดุสิต เขตดุสิต กรุงเทพมหานคร 10300
สหกรณ์ออมทรัพย์ครูกรมสามัญศึกษา จำกัด</address></p>
                            </div>

                            
                            
                            <div class="col-md-4">
                                <p class="font-weight-bold mb-0">P.O. NUMBER:</p>               
                                <p class="mb-0"><b>{{$data['order_detail']->po_number}}</b></p>
                                
                            </div>

                        
                </div>
                
                
                
                <!-- ----------------- -->

                <div class="row  pt-5 pb-0 pl-5 pr-5">
                    <div class="col-md-12">
                        <table class="table table-bordered mb-0">
                            <thead class="thead-light border-dark">
                                <tr>
                                    <th class=" text-uppercase small font-weight-bold">รายการที่ (No.)</th>
                                    <th class="border-0 text-uppercase small font-weight-bold">อุปกรณ์</th>
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

                                    @endphp

                                    <tr>
                                        <td>{{ $key + 1 }}</td>
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
                    <li class="border-0 py-0">กรุณาพิมพ์และเซนกำกับ พร้อมประทับตรา (ถ้ามี)</li>
                    <li class="border-0 py-0">หรือ หากใช้ฟอร์มของท่านเอง กรุณาระบบข้อความ ราคา และรายละเอียดอื่นๆให้ครบถ้วน</li>
                    <li class="border-0 py-0">ส่งเอกสารตัวจริงพร้อมสำเนา 1 ชุด มายังฝ่ายจัดซื้อ</li>
                    <li class="border-0 py-0">บริษัท สตรองรูท จำกัด ที่อยู่ 60 รามคำแหง 12 หัวหมาก, บางกะปิ, กรุงเทพมหานครฯ 10240</li>
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


<div class=" bg-light text-white p-5 pt-2 shadow mb-5 rounded-bottom sp_btn_container">
              
  <div class="row justify-content-end" style="display: flex;padding-top: 50px;">
  
      <div class="col-2 d-flex flex-column ">
       
      
        <a href="{{ route('union.vieworderUQF',['order_id' => $data['order_detail']->id,'download'=>'pdf']) }}" class="btn btn-success mb-4 float-right">บันทึก PDF</a>
          
        <button onclick="window.print()" type="button" class="btn btn-primary mb-4 float-right clickbind">PRINT</button>            

        <button type="button" onclick="window.close();"  class="btn btn-danger mb-0 float-right">ปิดหน้า</button>
          
          
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

    

</body>
</html>