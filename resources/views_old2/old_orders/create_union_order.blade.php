@include('frontend.common.print_header')

@php

$union_detail = $data['union_detail'];
$products = $data['products'];
$simcards = $data['simcards'];

@endphp

<div class="row sp_create_order_loader" style="position: relative;">
    <div class="col-12">
        <div class="card mb-0">
            <div class="card-body p-0">
                <div class="row pt-5 pl-5 pr-5 ">
                    <div class="col-md-6">
                        <h1 class="logo_headding" style="font-size: 18px;">ออกโดย <br> {{$union_detail->first_name}}</h1>
                    </div>
                    <div class="col-md-6 text-right pt-3">
                        <p class="font-weight-bold mb-0" style="font-size:25">ใบสั่งซื้อสินค้า</p>
                    </div>
                </div>





                <div class="row pt-0 pl-5 pr-5 pb-5">
                    <div class="col-md-6">
                        <p class="font-weight-bold mb-0"><address>ที่อยู่ {{$union_detail->address}}</address>
                        </p>
                        <p class="mb-0">เบอร์โทรศัพท์ {{$union_detail->mobile_number}}</p>
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
                        <p class="font-weight-bold mb-0"><span style="color: #FF0000;">*</span> หมายเลข P.O. : </p>
                        <p class="mb-0"><textarea name="po_number" class="form-control" id="po_number" cols="10" rows="2"></textarea></p>

                    </div>


                </div>



                <!-- ----------------- -->

                <div class="row  pt-5 pb-0 pl-5 pr-5">
                    <div class="col-md-12">
                        <table class="table table-bordered mb-0">
                            <thead class="thead-light border-dark">
                                <tr>
                                    <th class=" text-uppercase small font-weight-bold">ลบออก</th>
                                    <th class="border-0 text-uppercase small font-weight-bold">อุปกรณ์</th>
                                    <th class=" text-uppercase small font-weight-bold">เลือกซิม</th>
                                    <th class=" text-uppercase small font-weight-bold">จำนวนเครื่อง</th>
                                    <th class=" text-uppercase small font-weight-bold">ราคาต่อหน่วย</th>
                                    <th class=" text-uppercase small font-weight-bold text-right">รวม</th>
                                </tr>
                            </thead>
                            <tbody class="sp_product_row">

                                <tr>
                                    <td colspan="6" style="position: relative;padding: 0px;"><i data-toggle="tooltip" data-original-title="Add Item" aria-hidden="true" class="fas fa-plus sp_add_item_modal" style="border: 1px solid;padding: 5px;border-radius: 20px;font-size: 10px;position: absolute;left: -35px;top: 3px;cursor: pointer; color: #3399ff;"></i></td>
                                </tr>

                                <table class="table table-bordered col-5" align="right">

                                    <tbody>


                                        <!-- total -->
                                        <tr class="col-5">
                                            <td class="col-6">ราคารวม</td>
                                            <td class="col-3 text-right order_sub_total">0</td>

                                        </tr>
                                        <tr class="col-5">

                                            <td class="col-6">ภาษีมูลค่าเพิ่ม (7%) </td>
                                            <td class="col-3  text-right order_sales_tax">0</td>

                                        </tr>
                                        <tr class="col-5">
                                            <td class="col-6">ราคารวมภาษีมูลค่าเพิ่ม</td>
                                            <td class="col-3 text-right order_total">0</td>

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
                                <p class="" style="width: 50%;float: left;">ลายเซ็นผู้มีอำนาจ</p>
                                <p style="width: 50%;text-align:right; float: left;">ประทับตรา (ถ้ามี)</p>

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


                    <a class="btn btn-success mb-4 float-right sp_create_order disabled" style="cursor:pointer;">Confirm</a>


                    <!--button onclick="window.print()" type="button" class="btn btn-primary mb-4 float-right clickbind">PRINT</button-->
              
              
					<button onclick="history.back()" type="button" class="btn btn-danger mb-0 float-right">BACK</button>

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

<!-- Add feed Modal -->

<div class="modal fade" id="add_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form class="sp_form">

                <div class="modal-header">
                    <h5 class="modal-title  text-primary" id="deleteModalExample">Add Item</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <input type="hidden" value="0" id="edit_id">
                <input type="hidden" value="0" id="previous_img_val">

                <div class="modal-body">

                    <div class="msg_status"></div>

                    <div class="form-horizontal">

                        <div class="form-group">
                            <div class="col-md-12">
                                <label for="Phone ">Phone</label>
                                <select class="form-control sp_get_stock_by_product_id" id="product_id" required>
                                    <option value="">Select</option>

                                    @foreach ( $products as $product )

                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->title }}</option>

                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <label for="English ">Simcard</label>
                                <select class="form-control" id="simcard_id" required>
                                    <option value="">Select</option>

                                    @foreach ( $simcards as $simcard )

                                    <option value="{{ $simcard->id }}">{{ $simcard->provider }}</option>

                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <label for="English ">Quantity <span class="sp_show_stock text-primary"></span></label>
                                <input type="number" class="form-control" id="product_quantity" min="1" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <label for="English ">Receiver Name</label>
                                <input type="text" class="form-control" id="receiver_name">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <label for="English ">Receiver Delivery Address</label>
                                <textarea type="text" class="form-control" id="receiver_delivery_address"></textarea>
                            </div>
                        </div>

                        <div class="quantity_cont">

                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" id="sp_add_item">Add</button>
                    <input type="hidden" name="order_id" id="order_id" value="0">
                </div>

            </form>

        </div>
    </div>
</div>

@include('frontend.common.print_footer')