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
use Log;
use PDF;

class OrdersController extends Controller
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

    $this->sp_user_id = Auth::user()->id;
    $this->sp_user_name = Auth::user()->first_name.' '.Auth::user()->last_name;
    $this->menu_name = 'Order';
        
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
    public function index(Request $request)
    {
        
        $union_name = $request->get('union_name');
        $status = $request->get('status');

        $date_range = $request->get('daterange');
        
        if( $status != '' || !empty($union_name) || !empty($date_range) ){

            $orders = DB::table('orders')
            ->join('users', 'users.id', '=', 'orders.union_id')
            ->where(function($query) use ($status , $date_range , $union_name)
            {
                
                if( $status != '' ){

                    $query->where('orders.status', $status);
                }

                if(!empty($date_range)){

                    $date_range = explode(' - ',$date_range);

                    if( count( array_filter($date_range) ) > 0 ){

                        $start_date = date_format(date_create($date_range[0]),'Y-m-d');
                        $last_date = date_format(date_create($date_range[1]),'Y-m-d');
                    }

                    $query->whereBetween('orders.created_at', [$start_date, $last_date]);
                }

                if(!empty($union_name)){

                    $query->where('orders.union_id', $union_name);
                }
            })
            ->select('orders.*', 'users.first_name', 'users.last_name')
            ->orderBy('orders.id', 'DESC')
            ->paginate(10);

            $orders->appends($request->all())->links(); // add search parameters to paginations

        } else {

            $orders = DB::table('orders')
            ->join('users', 'users.id', '=', 'orders.union_id')
            ->select('orders.*', 'users.first_name', 'users.last_name')
            ->orderBy('orders.id', 'DESC')
            ->paginate(10);
            
        }

        return view('orders.index', [
            'title' => 'Order',
            'data' => $orders
        ]);
    
    }

    

    /**
     * Update Order Status resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateOrderStatus(Request $request)
    { 

        // Validation
        $validate = Validator::make([
            'status'           =>     $request->get('status'),
            'order_id'         =>     $request->get('edit_id'),
        ], [
            'status'           =>     'required',
            'order_id'         =>     'required|exists:orders,id',
        ]);

        // If Validations Fails
        if($validate->fails()){

            return response()->json([
                'status' => false,
                'message' => $validate->errors()->first()
            ], 200);

        }

        try {

            $user_id = $this->sp_user_id;

            $status 	    = 	$request->get('status');
            $order_id 	    = 	$request->get('edit_id');

            $data_values = array(
                'status'        =>  $status,
                'updated_at'    =>  date('Y-m-d H:i:s'),
            );
            
            DB::table('orders')->where('id', $order_id)->update($data_values);
            
            $log_action = 'Edit';

            $log_data_values = array(
				'user_id' => $user_id,
				'name' => $this->sp_user_name,
				'record_id' => $order_id,
				'menu' => 'Update Order Status',
				'action' => $log_action,
				'curent_route' => $this->curent_route,
				'request_data' => $request->all(),
                'updated_at' => date('Y-m-d H:i:s')
			);

            Log::debug("Sp Log Store", $log_data_values );

			$msg_arr = 'Status updated successfully.';
			
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
     * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function orderVendorList()
    {
        $user_id = $this->sp_user_id;

        //$orders = ProductVendorOrders::join('orders', function($q) use ($user_id)
        $orders = DB::table('vendor_product_orders')->join('orders', function($q) use ($user_id)
        {
            $q->on('orders.id', 'vendor_product_orders.order_id')
                ->where('vendor_product_orders.user_id', $user_id);
        })
        ->join('orders_items', 'orders_items.id', '=', 'vendor_product_orders.order_item_id')
        ->join('products', 'orders_items.product_id', '=', 'products.id')
        ->select('orders.*', 'products.title', 'orders_items.product_quantity as order_qty')
        ->orderBy('vendor_product_orders.id', 'DESC')
        ->paginate(10); 
        
        //echo '<pre>';print_r($orders);die();

        //echo '<pre>';print_r($orders);die();

        return view('orders.order_vendor_list', [
            'title' => 'Order',
            'data' => $orders
        ]);
    
    }

    /**
     * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function orderSimcardVendorList()
    {
        $user_id = $this->sp_user_id;

        //$orders = ProductVendorOrders::join('orders', function($q) use ($user_id)
        $orders = DB::table('vendor_simcard_orders')->join('orders', function($q) use ($user_id)
        {
            $q->on('orders.id', 'vendor_simcard_orders.order_id')
                ->where('vendor_simcard_orders.user_id', $user_id);
        })
        ->join('orders_items', 'orders_items.id', '=', 'vendor_simcard_orders.order_item_id')
        ->join('simcard', 'orders_items.simcard_id', '=', 'simcard.id')
        ->select('orders.*', 'simcard.simcard_provider as title', 'orders_items.product_quantity as order_qty')
        ->orderBy('vendor_simcard_orders.id', 'DESC')
        ->paginate(10); 
        
        //echo '<pre>';print_r($orders);die();

        //echo '<pre>';print_r($orders);die();

        return view('orders.order_simcard_vendor_list', [
            'title' => 'Order',
            'data' => $orders
        ]);
    
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
                    <th class="text-center">Options</th>
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

                        <td class="text-center">

                        <i data-toggle="tooltip" data-original-title="Order Assign To Phone Vendor" class="fas fa-user-plus  text-primary assign_to_phone_vendor" data-id="'.$row->id.'" data-vendor_type="Vendor" data-order-id="'.$row->order_id.'" style="margin-right: 15px;cursor: pointer;"></i>
                        
                        <i data-toggle="tooltip" data-original-title="Order Assign To Simcard Vendor" class="fas fa-user-tag  text-primary assign_to_phone_vendor" data-id="'.$row->id.'" data-vendor_type="Simcard Vendor" data-order-id="'.$row->order_id.'" style="margin-right: 15px;cursor: pointer;"></i>
                        
                        <!--i data-toggle="tooltip" data-original-title="Update Quantity" aria-hidden="true" class="fa fa-check text-success update_assign_vendor_qty" data-id="'.$row->id.'" data-order-id="'.$row->order_id.'" style="margin-right: 15px;cursor: pointer;"></i> 
                        
                        <i data-toggle="tooltip" data-original-title="Remove Vendor" aria-hidden="true" class="fas fa-trash text-danger  order-assign-vendor-delete-trigger" id="'.$row->id.'" data-order-id="'.$row->order_id.'" style="cursor: pointer;"></i-->

                        </td></tr>
                        
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
     * Assign vendor for product resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response 
     */
    public function viewOrderAssignVendorList(Request $request)
    {

        // Validation
        $validate = Validator::make([
            'order_item_id'             =>      $request->get('edit_id'),
            'order_id'                  =>      $request->get('order_id'),
            'vendor_type'               =>      $request->get('vendor_type'),
        ], [
            'order_item_id'             =>  'required|exists:orders_items,id',
            'order_id'                  =>  'required|exists:orders,id',
            'vendor_type'               =>  'required',
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
            $order_item_id 	        = 	$request->get('edit_id');
            $order_id 	            = 	$request->get('order_id');
            $vendor_type 	        = 	$request->get('vendor_type');

            // get product id

            $product_id_row = OrderItems::where('id', $order_item_id)->get();

            $product_id = $product_id_row[0]->product_id;

            $log_action = 'View';

            $msg_arr = '<div class="alert alert-danger ">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close"></a>
                            No record found.
                        </div>';
            
            // change order table according to vendor type

            //$vendor_type = 'Vendor';
            $sp_order_table = 'vendor_product_orders';
            $sp_order_quotation_url = 'vieworderVQ';

            //$vendor_type = 'Simcard Vendor';

            if($vendor_type == 'Simcard Vendor'){

                // get simcard id

                $product_id_row = OrderItems::where('id', $order_item_id)->get();

                $product_id = $product_id_row[0]->simcard_id;

                $sp_order_table = 'vendor_simcard_orders';
                $sp_order_quotation_url = 'vieworderSimVQ';

            }
                      
            //$vendors = DB::select('SELECT t1.* , t2.first_name , t2.last_name FROM `vendor_product_orders` t1 INNER JOIN `users` t2 ON t1.user_id = t2.id AND t1.order_id = '.$order_id.' AND t1.order_item_id = '.$order_item_id.' ');

            $vendors = DB::select('SELECT t1.* , t2.first_name , t2.last_name FROM `'.$sp_order_table.'` t1 INNER JOIN `users` t2 ON t1.user_id = t2.id AND t1.order_id = '.$order_id.' AND t1.order_item_id = '.$order_item_id.' ');

            // get total Quantity

            $total_qty_row = OrderItems::where('id', $order_item_id)->get();

            $product_quantity = $total_qty_row[0]->product_quantity;

            // get phone vendor list

            //$vendor_list = User::role('Vendor')->where('status',1)->get();
            $vendor_list = User::role($vendor_type)->where('status',1)->get();

            $vendor_options = '';

            foreach( $vendor_list as $vendor){

                $vendor_options .= '<option value="'.$vendor->id.'" >'.$vendor->first_name.' '.$vendor->last_name.'</option>';
            }
            
            $msg_arr = '
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-7">

                            <select class="form-control" id="phone_vendor_id" required="">
                                <option>Select Vendor</option>
                                '.$vendor_options.'
                            </select>
                        </div>
                        <div class="col-md-5">

                            <a data-toggle="tooltip" data-original-title="Click here to add vendor" id="" class="btn btn-sm btn-primary sp_add_phone_vendor" data-order-id="'.$order_id.'" data-order-item-id="'.$order_item_id.'" data-vendor_type="'.$vendor_type.'"><i aria-hidden="true" class="fas fa-plus"></i> Add Vendor </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 ">
                    <p class="text-right"><span class="badge badge-primary">Total Quantity : '.$product_quantity.'</span></p>
                </div>
            </div>
            
            <table class="table table-bordered table-striped dataTable no-footer" style="margin-top: 20px;">
            <thead>
                <tr>
                
                    <th class="text-center">No.</th>
                    <th class="text-center">Vendor</th>
                    <th class="text-center" style="width: 50px;">Quantity</th>
                    <th class="text-center">Options</th>
                </tr>
            </thead>
            <tbody>';

            if(count(array_filter($vendors)) > 0){
                
                $i = 1;

                foreach($vendors as $row){

                    $vendor_stock = '';

                    if($vendor_type == 'Vendor'){

                        $vendor_stock = '<span class="sp_show_stock text-primary"> (Available Stock: 0)</span>';

                        $get_vendor_stock_row = ProductVendorStock::where('user_id', $row->user_id)->where('product_id', $product_id)->get();

                        if( count( $get_vendor_stock_row ) > 0){

                            $vendor_stock = '<span class="sp_show_stock text-primary"> (Available Stock: '.$get_vendor_stock_row[0]->stock.')</span>';

                        }

                    }

                    $msg_arr .= '<tr id="row_'.$row->id.'">
                                        
                        <td class="text-center">'.$i.'</td>
                        <td class="text-center row_text_'.$row->id.'">'.$row->first_name.' '.$row->last_name.$vendor_stock.'</td>
                        <td class="text-center row_product_quantity_'.$row->id.'"><input type="number" class="form-control vendor_order_qty vendor_order_qty_'.$row->id.'" value="'.$row->product_quantity.'" ></td>
                        <td class="text-center row_options_'.$row->id.'">
                        
                        <i data-toggle="tooltip" data-original-title="Update Quantity" aria-hidden="true" class="fa fa-check text-success update_assign_vendor_qty" data-id="'.$row->id.'" data-order-id="'.$row->order_id.'" data-order-item-id="'.$row->order_item_id.'" data-vendor_type="'.$vendor_type.'" style="margin-right: 15px;cursor: pointer;"></i> 

                        <a href="'.route('admin.'.$sp_order_quotation_url, ['order_id' => $order_id, 'vendor_id' => $row->user_id, 'product_id' => $product_id, 'order_item_id' => $row->order_item_id] ) .'" target="_blank"><i data-toggle="tooltip" data-original-title="View Vendor Quotation" aria-hidden="true" class="fas fa-print text-warning" id="'.$row->id.'" data-order-id="'.$row->order_id.'" style="margin-right: 15px;cursor: pointer;"></i></a>
                        
                        <i data-toggle="tooltip" data-original-title="Remove Vendor" aria-hidden="true" class="fas fa-trash text-danger  order-assign-vendor-delete-trigger" id="'.$row->id.'" data-order-id="'.$row->order_id.'" data-order-item-id="'.$row->order_item_id.'" data-vendor_type="'.$vendor_type.'" style="cursor: pointer;"></i>
                        
                        <input type="hidden" class="form-control vendor_order_user_id vendor_order_user_id_'.$row->id.'" value="'.$row->user_id.'" >

                        </td></tr>
                        
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
     * Update Order Assign Vendor Quantity resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function orderAssignToVendor(Request $request)
    { 

        // Validation
        $validate = Validator::make([
            'assign_user_id'                    =>     $request->get('assign_user_id'),
            'order_id'                          =>     $request->get('order_id'),
            'order_item_id'                     =>     $request->get('order_item_id'),
            'vendor_type'                       =>      $request->get('vendor_type'),
        ], [
            'assign_user_id'                    =>     'required|exists:users,id',
            'order_id'                          =>     'required|exists:orders,id',
            'order_item_id'                     =>     'required|exists:orders_items,id',
            'vendor_type'                       =>     'required',
        ]);

        // If Validations Fails
        if($validate->fails()){

            return response()->json([
                'status' => false,
                'message' => $validate->errors()->first()
            ], 200);

        }

        try {

            $user_id = $this->sp_user_id;

            $order_id 	                    = 	$request->get('order_id');
            $order_item_id 	                    = 	$request->get('order_item_id');
            $quantity 	                    = 	0;
            $assign_user_id 	            = 	$request->get('assign_user_id');
            $vendor_type 	                = 	$request->get('vendor_type');

            // change order table according to vendor type

            //$vendor_type = 'Vendor';
            $sp_order_table = 'vendor_product_orders';

            //$vendor_type = 'Simcard Vendor';

            if($vendor_type == 'Simcard Vendor'){

                $sp_order_table = 'vendor_simcard_orders';

            }

            // check total Quantity

            $row_check = DB::table($sp_order_table)->where('user_id', $assign_user_id)->where('order_id', $order_id)->where('order_item_id', $order_item_id)->count();

            //$row_check = DB::table('vendor_product_orders')->where('user_id', $assign_user_id)->where('order_id', $order_id)->where('order_item_id', $order_item_id)->count();

            //$row_check = ProductVendorOrders::where('user_id', $assign_user_id)->where('order_id', $order_id)->where('order_item_id', $order_item_id)->count();

            if( $row_check > 0 ){

                return response()->json([
                    'status' => false,
                    'message' => 'Vendor already exist'
                ], 200);
            }
            
            $data_values = array(
                'user_id'           =>  $assign_user_id,
                'order_id'          =>  $order_id,
                'order_item_id'     =>  $order_item_id,
                'product_quantity'  =>  $quantity,
                'status'            =>  1,
                'created_at'        =>  date('Y-m-d H:i:s'),
            );
            
            DB::table($sp_order_table)->insert($data_values);

            $log_action = 'Add';
              
            // insert action

            $log_data_values = array(
				'user_id' => $user_id,
				'name' => $this->sp_user_name,
				'record_id' => $order_id,
				'menu' => 'Order Assign To Vendor',
				'action' => $log_action,
				'curent_route' => $this->curent_route,
				'request_data' => $request->all(),
                'updated_at' => date('Y-m-d H:i:s')
			);

            Log::debug("Sp Log Store", $log_data_values );

			$msg_arr = 'Vendor assigned successfully';
			
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
     * Update Order Assign Vendor Quantity resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateOrderAssignVendorQuantity(Request $request)
    { 
        
            
        // change order table according to vendor type

        $vendor_type     =      $request->get('vendor_type');

        //$vendor_type = 'Vendor';
        $sp_order_table = 'vendor_product_orders';

        //$vendor_type = 'Simcard Vendor';

        if($vendor_type == 'Simcard Vendor'){

            $sp_order_table = 'vendor_simcard_orders';

        }

        // Validation
        $validate = Validator::make([
            'vendor_product_order_id'           =>     $request->get('row_edit_id'),
            'assign_user_id'                    =>     $request->get('assign_user_id'),
            'quantity'                          =>     $request->get('quantity'),
            'vendor_order_total_qty'            =>     $request->get('vendor_order_total_qty'),
            'order_id'                          =>     $request->get('order_id'),
            'order_item_id'                     =>     $request->get('order_item_id'),
            'vendor_type'                       =>      $request->get('vendor_type'),
        ], [
            'vendor_product_order_id'           =>     'required|exists:'.$sp_order_table.',id',
            'assign_user_id'                    =>     'required|exists:users,id',
            'quantity'                          =>     'required|integer|min:1',
            'vendor_order_total_qty'            =>     'required',
            'order_id'                          =>     'required|exists:orders,id',
            'order_item_id'                     =>     'required|exists:orders_items,id',
            'vendor_type'                       =>     'required',
        ]);

        // If Validations Fails
        if($validate->fails()){

            return response()->json([
                'status' => false,
                'message' => $validate->errors()->first()
            ], 200);

        }

        try {

            $user_id = $this->sp_user_id;

            $vendor_product_order_id 	    = 	$request->get('row_edit_id');
            $order_id 	                    = 	$request->get('order_id');
            $order_item_id 	                = 	$request->get('order_item_id');
            $quantity 	                    = 	$request->get('quantity');
            $assign_user_id 	            = 	$request->get('assign_user_id');
            $vendor_order_total_qty 	    = 	$request->get('vendor_order_total_qty');

            // check total Quantity

            $total_qty_row = OrderItems::where('id', $order_item_id)->get();;

            $product_quantity = $total_qty_row[0]->product_quantity;
            $product_id = $total_qty_row[0]->product_id;

            if( $vendor_order_total_qty > $product_quantity){

                return response()->json([
                    'status' => false,
                    'message' => 'Please check order total quantity.'
                ], 200);
            }

            if($vendor_type != 'Simcard Vendor'){

                // only check vendor stock for only phones

                $vendor_stock = 0;

                $get_vendor_stock_row = ProductVendorStock::where('user_id', $assign_user_id)->where('product_id', $product_id)->get();

                if( count( $get_vendor_stock_row ) > 0){

                    $vendor_stock = $get_vendor_stock_row[0]->stock;

                }

                if( $quantity > $vendor_stock){

                    return response()->json([
                        'status' => false,
                        'message' => 'Vendor have limited stock.'
                    ], 200);
                }

            }

            
            $data_values = array(
                'product_quantity'  =>  $quantity,
                'updated_at'        =>  date('Y-m-d H:i:s'),
            );
            
            DB::table($sp_order_table)->where('user_id', $assign_user_id)->where('id', $vendor_product_order_id)->update($data_values);
            
            $log_action = 'Edit';

            $log_data_values = array(
				'user_id' => $user_id,
				'name' => $this->sp_user_name,
				'record_id' => $vendor_product_order_id,
				'menu' => 'Update Order Assign Vendor Quantity',
				'action' => $log_action,
				'curent_route' => $this->curent_route,
				'request_data' => $request->all(),
                'updated_at' => date('Y-m-d H:i:s')
			);

            Log::debug("Sp Log Store", $log_data_values );

			$msg_arr = 'Quantity updated successfully.';
			
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
     * Remove assigned vendor the specified resource from storage.
     *
     * @param  \App\products  $products
     * @return \Illuminate\Http\Response
     */
    public function deleteOrderAssignToVendor(Request $request)
    { 
        // change order table according to vendor type

        $vendor_type     =      $request->get('vendor_type');

        //$vendor_type = 'Vendor';
        $sp_order_table = 'vendor_product_orders';

        //$vendor_type = 'Simcard Vendor';

        if($vendor_type == 'Simcard Vendor'){

            $sp_order_table = 'vendor_simcard_orders';

        }

        // Validation
        $validate = Validator::make([
            'delete_id'                         =>     $request->get('delete_id'),
            'vendor_type'                       =>      $request->get('vendor_type'),
        ], [
            'delete_id'                         =>     'required|exists:'.$sp_order_table.',id',
            'vendor_type'                       =>     'required',
        ]);

        // If Validations Fails
        if($validate->fails()){

            return response()->json([
                'status' => false,
                'message' => $validate->errors()->first()
            ], 200);

        }

        $delete_id = $request->get('delete_id');

        DB::table($sp_order_table)->where('id',$delete_id)->delete();

        //ProductVendorOrders::find($delete_id)->delete();

        // insert action

        $log_data_values = array(
            'user_id' => $this->sp_user_id,
            'name' => $this->sp_user_name,
            'record_id' => $delete_id,
            'menu' => 'Delete Assign To Vendor',
            'action' => 'Delete',
            'curent_route' => $this->curent_route,
            'request_data' => $request->all(),
            'updated_at' => date('Y-m-d H:i:s')
        );

        Log::debug("Sp Log Store", $log_data_values );
        
        return response()->json(['status' => true], 200);
    }


    /**
     * View Union Quotation the specified resource from storage.
     *
     * @param  \App\products  $products
     * @return \Illuminate\Http\Response
     */
    public function viewOrderUnionQuotation($order_id , Request $request)
    { 

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

        

        $union_detail_arr = User::whereId($orders[0]->union_id)->get();
        $union_detail = $union_detail_arr[0];

        $order_detail = $orders[0];

        if($request->has('download')) {

            $data['data'] = array('options' => 'hide', 'union_detail' => $union_detail, 'order_detail' => $order_detail);

        	// pass view file
            $pdf = PDF::loadView('orders.union_quotation', $data);
            // download pdf
            return $pdf->download('UnionQuotation.pdf');
        }

        //echo '<pre>';print_r($order_detail);die();

        return view('orders.union_quotation', [
            'title' => 'Order',
            'data' => array('options' => 'show', 'union_detail' => $union_detail, 'order_detail' => $order_detail),
            
        ]);
    }


    /**
     * View Vendor Quotation the specified resource from storage.
     *
     * @param  \App\products  $products
     * @return \Illuminate\Http\Response
     */
    public function viewOrderVendorQuotation($order_id, $vendor_id, $product_id, $order_item_id, Request $request)
    {

        $orders = DB::table('orders')
        ->where('orders.id',$order_id)
        ->select('orders.*')
        ->get();

        $order_items = DB::table('orders_items')
            ->join('orders', 'orders_items.order_id', '=', 'orders.id')
            ->join('products', 'orders_items.product_id', '=', 'products.id')
            ->join('simcard', 'orders_items.simcard_id', '=', 'simcard.id')
            ->select('orders_items.*', 'products.title', 'products.price', 'simcard.simcard_provider')
            ->where('orders_items.order_id', $order_id)
            ->where('orders_items.product_id', $product_id)
            ->orderBy('orders_items.id', 'DESC')
            ->get();

        $orders[0]->order_items = $order_items;

        $union_detail_arr = User::whereId($orders[0]->union_id)->get();
        $union_detail = $union_detail_arr[0];

        $sp_order_table = 'vendor_product_orders';

        $user_detail_arr = DB::select('SELECT t1.* , t2.first_name , t2.last_name , t2.address , t2.mobile_number FROM `'.$sp_order_table.'` t1 INNER JOIN `users` t2 ON t1.user_id = t2.id AND t1.order_id = '.$order_id.' AND t1.order_item_id = '.$order_item_id.' AND t1.user_id = '.$vendor_id.' ');

        //$user_detail_arr = User::whereId($vendor_id)->get();
        $user_detail = $user_detail_arr[0];

        $order_detail = $orders[0];
        
        if($request->has('download')) {

            $data['data'] = array(
                'options' => 'hide',
                'user_detail' => $user_detail,
                'union_detail' => $union_detail,
                'order_detail' => $order_detail,
                'order_id' => $order_id,
                'vendor_id' => $vendor_id,
                'product_id' => $product_id,
                'order_item_id' => $order_item_id,
            );

        	// pass view file
            $pdf = PDF::loadView('orders.vendor_quotation', $data);
            // download pdf
            return $pdf->download('VendorQuotation.pdf');
        }

        return view('orders.vendor_quotation', [
            'title' => 'Order',
            'data' => array(
                'options' => 'show',
                'user_detail' => $user_detail,
                'union_detail' => $union_detail,
                'order_detail' => $order_detail,
                'order_id' => $order_id,
                'vendor_id' => $vendor_id,
                'product_id' => $product_id,
                'order_item_id' => $order_item_id,
            )
        ]);
    }


    /**
     * View Vendor Quotation the specified resource from storage.
     *
     * @param  \App\products  $products
     * @return \Illuminate\Http\Response
     */
    public function viewOrderSimcardVendorQuotation($order_id, $vendor_id, $simcard_id, $order_item_id, Request $request)
    {

        $orders = DB::table('orders')
        ->where('orders.id',$order_id)
        ->select('orders.*')
        ->get();

        $order_items = DB::table('orders_items')
            ->join('orders', 'orders_items.order_id', '=', 'orders.id')
            ->join('simcard', 'orders_items.simcard_id', '=', 'simcard.id')
            ->select('orders_items.*', 'simcard.simcard_provider as title', 'simcard.monthly_price as price')
            ->where('orders_items.order_id', $order_id)
            ->where('orders_items.simcard_id', $simcard_id)
            ->orderBy('orders_items.id', 'DESC')
            ->get();

        $orders[0]->order_items = $order_items;

        $union_detail_arr = User::whereId($orders[0]->union_id)->get();
        $union_detail = $union_detail_arr[0];

        $sp_order_table = 'vendor_simcard_orders';

        $user_detail_arr = DB::select('SELECT t1.* , t2.first_name , t2.last_name , t2.address , t2.mobile_number FROM `'.$sp_order_table.'` t1 INNER JOIN `users` t2 ON t1.user_id = t2.id AND t1.order_id = '.$order_id.' AND t1.order_item_id = '.$order_item_id.' AND t1.user_id = '.$vendor_id.' ');

        //$user_detail_arr = User::whereId($vendor_id)->get();
        $user_detail = $user_detail_arr[0];

        $order_detail = $orders[0];
        
        if($request->has('download')) {

            $data['data'] = array(
                'options' => 'hide',
                'user_detail' => $user_detail,
                'union_detail' => $union_detail,
                'order_detail' => $order_detail,
                'order_id' => $order_id,
                'vendor_id' => $vendor_id,
                'product_id' => $simcard_id,
                'order_item_id' => $order_item_id,
            );

        	// pass view file
            $pdf = PDF::loadView('orders.simcard_vendor_quotation', $data);
            // download pdf
            return $pdf->download('VendorQuotation.pdf');
        }

        return view('orders.simcard_vendor_quotation', [
            'title' => 'Order',
            'data' => array(
                'options' => 'show',
                'user_detail' => $user_detail,
                'union_detail' => $union_detail,
                'order_detail' => $order_detail,
                'order_id' => $order_id,
                'vendor_id' => $vendor_id,
                'product_id' => $simcard_id,
                'order_item_id' => $order_item_id,
            )
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    { 

        $delete_id = $request->get('delete_id');

        Orders::find($delete_id)->delete();	
        DB::table('orders_items')->delete($delete_id);
        //OrderItems::find($delete_id)->delete();
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
        
        return response()->json(['status' => true], 200);
    }
    

}
