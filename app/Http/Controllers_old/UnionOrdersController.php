<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\Products;
use App\Models\ProductVendorStock;
use App\Models\ProductVendorOrders;
use App\Models\Simcard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use File;
use Log;
use PDF;

class UnionOrdersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public $sp_user_id;
    public $sp_user_name;

    public function __construct(Orders $orders,User $user, Request $request, Auth $auth)
    { 
    $this->middleware('auth');
    $this->orders = $orders;

    $this->middleware(function ($request, $next) {

    //$this->sp_user_id = 6;
    //$this->sp_user_name = 'Union 1 Test';
    $this->sp_user_id = Auth::user()->id;
    $this->sp_user_name = Auth::user()->first_name.' '.Auth::user()->last_name;
    $this->menu_name = 'Union Order';
        
    return $next($request);
    });

    $curent_uri = $request->path();
    
    $curent_uri_arr = explode('/',$curent_uri);
    
    $curent_route = end($curent_uri_arr);

    $this->curent_route = $curent_route;

    }

    /**
     * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        
        $user_id = $this->sp_user_id;
     
        $orders = DB::table('orders')
        //->join('products', 'orders.product_id', '=', 'products.id')
        ->join('users', 'users.id', '=', 'orders.union_id')
        ->where('users.id',$user_id)
        ->where('orders.status','!=',6)
        ->select('orders.*', 'users.first_name', 'users.last_name')
        ->orderBy('orders.id', 'DESC')
        ->paginate(10);

        //echo '<pre>';print_r($orders);die();
        
        return view('frontend.union_order_list', [
            'title_main' => 'รายการ และ สถานะคำสั่งซื้อ',
            'title' => 'Purchase Order Status',
            'data' => $orders
        ]);
    
    }

    /**
     * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function UnionOrderHistory()
    {
        
        $user_id = $this->sp_user_id;
     
        $orders = DB::table('orders')
        //->join('products', 'orders.product_id', '=', 'products.id')
        ->join('users', 'users.id', '=', 'orders.union_id')
        ->where('users.id',$user_id)
        ->where('orders.status',6)
        ->select('orders.*', 'users.first_name', 'users.last_name')
        ->orderBy('orders.id', 'DESC')
        ->paginate(10);

        //echo '<pre>';print_r($orders);die();
        
        return view('frontend.union_order_list', [
            'title_main' => 'ประวัติการสั่งซื้อที่สำเร็จแล้ว',
            'title' => 'Purchase Order History',
            'data' => $orders
        ]);
    
    }


    /**
     * View create Union Order the specified resource from storage.
     *
     * @param  \App\products  $products
     * @return \Illuminate\Http\Response
     */
    public function createUnionOrderView(Request $request)
    { 
        // get union detail

        $user_id = $this->sp_user_id; // auth user id 

        $union_detail_arr = User::whereId($user_id)->get();
        $union_detail = $union_detail_arr[0];

        $products = DB::table('products')->select('id', 'price', 'title')->get();

        $simcards = DB::table('simcard')->select('id', 'simcard_provider as provider')->get();

        if($request->has('download')) {

            $data['data'] = array('options' => 'hide', 'union_detail' => $union_detail, 'products' => $products, 'simcards' => $simcards );

        	// pass view file
            $pdf = PDF::loadView('orders.vendor_quotation', $data);
            // download pdf
            return $pdf->download('VendorQuotation.pdf');
        }

        return view('orders.create_union_order', [
            'title' => 'Order',
            'data' => array('options' => 'show', 'union_detail' => $union_detail, 'products' => $products, 'simcards' => $simcards )
        ]);
    }


    /**
     * Create Union Order the specified resource from storage.
     *
     * @param  \App\products  $products
     * @return \Illuminate\Http\Response
     */
    public function createUnionOrder(Request $request)
    { 
        // Validation
        $validate = Validator::make([
            'po_number'               =>     $request->get('po_number'),
            'product_ids'               =>     $request->get('product_ids'),
            'simcard_ids'               =>     $request->get('simcard_ids'),
            'product_quantitys'         =>     $request->get('product_quantitys'),
        ], [
            'po_number'               =>     'required',
            'product_ids'               =>     'required',
            'simcard_ids'               =>     'required',
            'product_quantitys'         =>     'required',
        ]);

        DB::beginTransaction();

        // If Validations Fails
        if($validate->fails()){

            return response()->json([
                'status' => false,
                'message' => $validate->errors()->first()
            ], 200);

        }

        try {

            $user_id = $this->sp_user_id;

            $po_number 	                = 	$request->get('po_number');

            // create order

            $order = Orders::create([
                'po_number'     => $po_number,
                'union_id'      => $user_id,
                'status'        => 0,
                'created_at'    => date('Y-m-d H:i:s'),
            ]);

            $order_id = 'STR-'.sprintf("%06d", $order->id);

            // Update order_id
            Orders::whereId($order->id)->update(['order_id' => $order_id]);

            $product_ids 	                = 	$request->get('product_ids');
            $simcard_ids 	                = 	$request->get('simcard_ids');
            $product_quantitys 	            = 	$request->get('product_quantitys');
            $receiver_names 	            = 	$request->get('receiver_names');
            $receiver_delivery_addresss 	= 	$request->get('receiver_delivery_addresss');

            foreach( $product_ids as $key => $productid ){

                // check product quantity not less than 1

                if( $product_quantitys[$key] < 1 ){

                    DB::rollBack();
                    return response()->json([
                        'status' => false,
                        'message' => 'The quantity must be at least 1.'
                    ], 200);

                }

                // check product is exist or not

                $row_check = Products::where('id', $productid)->count();

                if( $row_check < 1 ){

                    DB::rollBack();
                    return response()->json([
                        'status' => false,
                        'message' => 'The selected product id is invalid'
                    ], 200);

                }

                // check simcard is exist or not

                $row_check = Simcard::where('id', $simcard_ids[$key])->count();

                if( $row_check < 1 ){

                    DB::rollBack();
                    return response()->json([
                        'status' => false,
                        'message' => 'The selected simcard id is invalid'
                    ], 200);

                }

                $vendor_total_stock = 0;

                $get_vendor_total_stock = DB::table('vendor_products')->where('product_id', $productid)->sum('stock');

                $get_product_name = Products::where('id', $productid)->select('title')->get();

                if( $get_vendor_total_stock > 0){

                    $vendor_total_stock = $get_vendor_total_stock;

                } else {

                    DB::rollBack();
                    return response()->json([
                        'status' => false,
                        'message' => 'Stock for this product `'.$get_product_name[0]->title.'` is not available .'
                    ], 200);
                }

                if( $product_quantitys[$key] > $vendor_total_stock ){

                    DB::rollBack();
                    return response()->json([
                        'status' => false,
                        'message' => 'Available stock for this product `'.$get_product_name[0]->title.'` is '.$vendor_total_stock.' .'
                    ], 200);
                }

                $orderItem = OrderItems::create([
                    'order_id'                      =>  $order->id,
                    'product_id'                    =>  $productid,
                    'product_quantity'              =>  $product_quantitys[$key],
                    'simcard_id'                    =>  $simcard_ids[$key],
                    'receiver_name'                 =>  $receiver_names[$key],
                    'receiver_delivery_address'     =>  $receiver_delivery_addresss[$key],
                    'created_at'                    =>  date('Y-m-d H:i:s'),
                    //'password'      => Hash::make($request->first_name.'@'.$request->mobile_number)
                ]);

            }

            DB::commit();
            
            $log_action = 'Add';

            $log_data_values = array(
				'user_id' => $user_id,
				'name' => $this->sp_user_name,
				'record_id' => $order_id,
				'menu' => 'Order created by union',
				'action' => $log_action,
				'curent_route' => $this->curent_route,
				'request_data' => $request->all(),
                'updated_at' => date('Y-m-d H:i:s')
			);

            Log::debug("Sp Log Store", $log_data_values );

			$msg_arr = 'Order created successfully.';
			
            return response()->json([
                'status' => true,
                'order_id' => $order->id,
                //'msg_arr' => $msg_arr,
                'message' => $msg_arr 
            ], 200);

        } catch(\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 200);

        }

    }


    /**
     * Temprory View Union Quotation the specified resource from storage.
     *
     * @param  \App\products  $products
     * @return \Illuminate\Http\Response
     */
    public function viewOrderUnionQuotation($order_id , Request $request)
    { 

        $user_id = $this->sp_user_id;

        $union_detail_arr = User::whereId($user_id)->get();
        $union_detail = $union_detail_arr[0];

        $orders = DB::table('orders')
        //->join('products', 'orders.product_id', '=', 'products.id')
        //->join('users', 'users.id', '=', 'orders.union_id')
        ->where('orders.id',$order_id)
        ->select('orders.*')
        ->get();

        $order_items = DB::table('orders_items')
            ->join('orders', 'orders_items.order_id', '=', 'orders.id')
            ->join('products', 'orders_items.product_id', '=', 'products.id')
            ->join('simcard', 'orders_items.simcard_id', '=', 'simcard.id')
            ->select('orders_items.*', 'products.title', 'products.price', 'simcard.simcard_provider')
            ->where('orders_items.order_id', $order_id)
            ->orderBy('orders_items.id', 'DESC')
            ->get();

        $orders[0]->order_items = $order_items;

        $order_detail = $orders[0];

        //echo '<pre>'; print_r($order_detail);die();

        if($request->has('download')) {

            $data['data'] = array('options' => 'hide', 'union_detail' => $union_detail, 'order_detail' => $order_detail);

        	// pass view file
            $pdf = PDF::loadView('orders.view_union_order', $data);

            //print_r($pdf);die();
            // download pdf
            return $pdf->download('UnionQuotation-'.$order_detail->order_id.'.pdf');
        }

        //echo '<pre>';print_r($order_detail);die();

        return view('orders.view_union_order', [
            'title' => 'Order',
            'data' => array('options' => 'show', 'union_detail' => $union_detail, 'order_detail' => $order_detail ),
            
        ]);
    }

    /**
     * Assign vendor for product resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response 
     */
    public function checkStockByProductId(Request $request)
    {

        // Validation
        $validate = Validator::make([
            'product_id'             =>      $request->get('product_id'),
        ], [
            'product_id'   =>  'required|exists:products,id',
        ]);

        // If Validations Fails
        if($validate->fails()){

            return response()->json([
                'status' => false,
                'message' => $validate->errors()->first()
            ], 200);

        }

        try {

            $product_id = $request->get('product_id');

            $get_vendor_total_stock = DB::table('vendor_products')->where('product_id', $product_id)->sum('stock');

            $get_product_name = Products::where('id', $product_id)->select('title')->get();

            if( $get_vendor_total_stock > 0){

                $vendor_total_stock = $get_vendor_total_stock;

            } else {

                DB::rollBack();
                return response()->json([
                    'status' => true,
                    'message' => '(Available Stock: 0)'
                ], 200);
            }

            if( $vendor_total_stock > 0 ){

                DB::rollBack();
                return response()->json([
                    'status' => true,
                    'message' => '(Available Stock: '.$vendor_total_stock.')'
                ], 200);
            }

        } catch(\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 200);

        }

        

    }

    /**
     * Assign vendor for product resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response 
     */
    public function viewOrderItemList(Request $request)
    {

        // Validation
        $validate = Validator::make([
            'order_id'             =>      $request->get('edit_id'),
        ], [
            'order_id'   =>  'required|exists:orders,id',
        ]);

        // If Validations Fails
        if($validate->fails()){

            return response()->json([
                'status' => false,
                'message' => $validate->errors()->first()
            ], 200);

        }

        try {

            $user_id                =   $this->sp_user_id;
            $order_id 	            = 	$request->get('edit_id');

            $log_action = 'View';

            $msg_arr = '<div class="alert alert-danger ">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close"></a>
                            No record found.
                        </div>';
               
            $order_items = DB::table('orders_items')
            ->join('orders', 'orders_items.order_id', '=', 'orders.id')
            ->join('products', 'orders_items.product_id', '=', 'products.id')
            ->join('simcard', 'orders_items.simcard_id', '=', 'simcard.id')
            ->select('orders_items.*', 'products.title', 'simcard.simcard_provider')
            ->where('orders_items.order_id', $order_id)
            ->where('orders.union_id', $user_id)
            ->orderBy('orders_items.id', 'DESC')
            ->get();
            
            $msg_arr = '
            
            
            <table class="table table-bordered table-striped dataTable no-footer" style="margin-top: 20px;">
            <thead>
                <tr>
                
                    <th class="text-center">No.</th>
                    <th class="text-center">Product</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-center">Simcard</th>
                </tr>
            </thead>
            <tbody>';

            if( count( $order_items ) > 0 ){
                
                $i = 1;

                foreach($order_items as $row){

                    $msg_arr .= '<tr id="row_'.$row->id.'">
                                        
                        <td class="text-center">'.$i.'</td>
                        <td class="text-center row_text_'.$row->id.'">'.$row->title.'</td>
                        <td class="text-center row_text_'.$row->id.'">'.$row->product_quantity.'</td>
                        <td class="text-center dd row_text_'.$row->id.'">'.$row->simcard_provider.'</td>

                        </tr>
                        
                        ';

                    $i++;				
                }
            }

            $msg_arr .= '</tbody></table>';
                
             

            // insert action

            $log_data_values = array(
				'user_id' => $user_id,
				'name' => $this->sp_user_name,
				'record_id' => $order_id,
				'menu' => 'View Order Assign Vendor List',
				'action' => $log_action,
				'curent_route' => $this->curent_route,
				'request_data' => $request->all(),
                'updated_at' => date('Y-m-d H:i:s')
			);

            Log::debug("Sp Log Store", $log_data_values );

			//$msg_arr = 'Product updated successfully.';
			
            return response()->json([
                'status' => true,
                //'msg_arr' => $msg_arr,
                'message' => $msg_arr 
            ], 200);

        } catch(\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 200);

        }

        

    }

    /**
     * Upload Union Order Signed Document resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadUnionOrderSignedDocument(Request $request)
    { 
         
        
        // Validation
        $validate = Validator::make([
            'order_id'                          =>     $request->get('order_id'),
            'Upload_file'                              =>      $request->file('file'),
        ], [
            'order_id'                          =>     'required|exists:orders,id',
            'Upload_file'                              =>     'required|mimes:jpg,jpeg,gif,png,pdf|max:2048',
        ]);

        // If Validations Fails
        if($validate->fails()){

            return response()->json([
                'status' => false,
                'message' => $validate->errors()->first()
            ], 200);

        }

        DB::beginTransaction();

        try {

            $user_id = $this->sp_user_id;

            $order_id 	                    = 	$request->get('order_id');
            $file_prev 	                    = 	$request->get('file_prev');

            $file = $request->file('file');

            //you also need to keep file extension as well
            $name = $file->getClientOriginalName().time().'.'.$file->getClientOriginalExtension();

            // Move file
            $file->move(public_path().'/documents/', $name);
            $file_path = url('/documents/').'/'.$name;
            
            $data_values = array(
                'status'              =>  1,
                'file'              =>  $file_path,
                'updated_at'        =>  date('Y-m-d H:i:s'),
            );

            DB::table('orders')->where('union_id', $user_id)->where('id', $order_id)->update($data_values);
            
            $log_action = 'Edit';

            $log_data_values = array(
				'user_id' => $user_id,
				'name' => $this->sp_user_name,
				'record_id' => $order_id,
				'menu' => 'Upload Union Order Signed Document',
				'action' => $log_action,
				'curent_route' => $this->curent_route,
				'request_data' => $request->all(),
                'updated_at' => date('Y-m-d H:i:s')
			);

            Log::debug("Sp Log Store", $log_data_values );

			$msg_arr = 'Document uploaded successfully.';

            if ( !empty( $file_prev ) ) {
						
                $domain_url = url('');
                
                $item = str_replace($domain_url,'',$file_prev);
            
                $file_del = public_path().'/'.$item;
            
                if (is_file($file_del) && file_exists($file_del)) {
                
                    $st = File::delete($file_del);
                    
                }
            }

            DB::commit();
			
            return response()->json([
                'status' => true,
                //'msg_arr' => $msg_arr,
                'message' => $msg_arr 
            ], 200);

        } catch(\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 200);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    { 

        // Validation
        $validate = Validator::make([
            'delete_id'      =>      $request->get('delete_id'),
        ], [
            'delete_id'      =>      'required',
        ]);

        // If Validations Fails
        if($validate->fails()){

            return response()->json([
                'status' => false,
                'message' => $validate->errors()->first()
            ], 200);

        }

        try {

            $delete_id = $request->get('delete_id');

            // verify user order before delete

            $row_check = Orders::where('id', $delete_id)->where('union_id', $this->sp_user_id)->count();

            if( $row_check > 0 ){

                Orders::find($delete_id)->delete();
                //OrderItems::find($delete_id)->delete();
                DB::table('orders_items')->delete($delete_id);
                DB::table('vendor_product_orders')->delete($delete_id);
                DB::table('vendor_simcard_orders')->delete($delete_id);

                // insert action

                $log_data_values = array(
                    'user_id' => $this->sp_user_id,
                    'name' => $this->sp_user_name,
                    'record_id' => $delete_id,
                    'menu' => $this->menu_name,
                    'action' => 'Delete',
                    'curent_route' => $this->curent_route,
                    'request_data' => $request->all(),
                    'updated_at' => date('Y-m-d H:i:s')
                );

                Log::debug("Sp Log Store", $log_data_values );

            } else {

                return response()->json([
                    'status' => false,
                    'message' => 'Order not found.'
                ], 200);

            }

        } catch(\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 200);

        }

    }

}
