<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UnionMembership;
use App\Models\Products;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cookie;
use Validator;
use Log;
use PDF;
use Auth;

class WebHomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct( Request $request)
    {
        //$this->middleware('auth');

        $curent_uri = $request->path();
    
        $curent_uri_arr = explode('/',$curent_uri);
        
        $curent_route = end($curent_uri_arr);

        $this->curent_route = $curent_route;
    }

    /**
     * Show the Main Page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $products = Products::orderBy('id', 'desc')->paginate(15);

        return view('frontend.index', [
            'title' => 'Strongroot Webportal',
            'data' => $products
        ]);
    }

    /**
     * Show Load more product.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function productLoadMore(Request $request)
    {

        // Validation
        $validate = Validator::make([
            'last_order_id'                =>     $request->get('last_order_id'),
        ], [
            'last_order_id'                =>     'required|integer|min:1',
        ]);

        // If Validations Fails
        if($validate->fails()){

            return response()->json([
                'status' => false,
                'message' => $validate->errors()->first()
            ], 200);

        }

        try {
 
            $last_order_id = $request->get('last_order_id');

            $msg_arr = '';
               
            $products = Products::where('id', '<' , $last_order_id)->orderBy('id', 'desc')->paginate(15);

            $last_order_id = 0;
            
            if( count( $products ) > 0 ){
                
                $i = 1;

                foreach($products as $row){

                    $last_order_id = $row->id;

                    $msg_arr .= '<div class="col-md-4">
                    <div class="ns-card_1">
                        <h2 class="mb-sm-2">'.$row->title.'</h2>
                        <div class="ns_card_2">
                            <div class="row">
                                <div class="col-md-8 ">
                                <img class="h_s" src="'.$row->image.'">
                                </div>
                                <div class="col-md-4 ns_height">
                                <label for="lname" class="sp_simcard sp_simcard_'.$row->id.' " data-id="'.$row->id.'">AIS</label><br>
                                <label for="lname" class="sp_simcard sp_simcard_'.$row->id.' " data-id="'.$row->id.'">DTAC</label><br>
                                <label for="lname" class="sp_simcard sp_simcard_'.$row->id.' " data-id="'.$row->id.'">TRUE</label><br><span> เลือกค่าย</span>
                                </div>
                            </div>
                            <div class="ns_card_3">
                                <p class="pera mt-4 p-0">ราคาสินค้ารวม Internet <span class="ps-2">'.number_format($row->price, 2).'</span><span class="ps-2">บาท</span></p>
                                <p class="pera m-0 p-0">Internet Package <span class="ps-2"> 24 </span><span class="ps-2">เดือน</span></p>
                                <p class="pera m-0 p-0">อัตราการผ่อน <span class="text-danger ps-2" style="font-size:18px; font-weight:600;">'.number_format(ceil(($row->price)/24), 2).'</span> <span class="ps-2">บาท</span></p>
                                <p class="pera m-0 p-0">*ราคาสินค้าไม่รวมดอกเบี้ยเงินกู้</p>
                                         <p class="pera m-0 p-0">**สินค้าจัดส่งแบบคละสี</p>
                            </div>
                        </div>
                        <div class="ns_card_4 mt-3 ">
                            <a href="'.$row->link_spec.'" class="btn  number" target="_blank">สเปคเครื่อง</a>

                            <a  class="btn btn-primary ADD show_Union_Membership_modal"  data-id="'.$row->id.'">ขอใบเสนอราคา</a>
                                
                        </div>
                    </div>
                    </div>
                        
                        ';

                    $i++;				
                }

            } 
                
             
            return response()->json([
                'status' => true,
                'last_order_id' => $last_order_id,
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
     * verify Union Membership resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function verifyUnionMembership(Request $request)
    { 

        // Validation
        $validate = Validator::make([
            'union_name'                =>     $request->get('union_name'),
            'union_member_id'           =>     $request->get('union_member_id'),
            'name'           =>     $request->get('to_detail'),
            'address'           =>     $request->get('main_shipping_location'),
            'quantity'           =>     $request->get('quantity'),
            'simcard'           =>     $request->get('simcard'),
            'product_id'           =>     $request->get('product_id'),
        ], [
            'union_name'                =>     'required',
            'union_member_id'           =>     'required|min:6',
            'name'                =>     'required',
            'address'                =>     'required',
            'quantity'                =>     'required|integer|min:1',
            'simcard'                =>     'required',
            'product_id'                =>     'required',
        ]);

        // If Validations Fails
        if($validate->fails()){

            return response()->json([
                'status' => false,
                'message' => $validate->errors()->first()
            ], 200);

        }

        try {

            $union_name 	    = 	$request->get('union_name');
            $union_member_id 	= 	$request->get('union_member_id');
            $to_detail 	= 	$request->get('to_detail');
            $main_shipping_location 	= 	$request->get('main_shipping_location');
            $quantity 	= 	$request->get('quantity');
            $simcard 	= 	$request->get('simcard');
            $product_id 	= 	$request->get('product_id');

            // get detail and set cookies

            $get_union_member_row = UnionMembership::where('union_name', $union_name)->where('union_member_ID', $union_member_id)->get();

            if( count( $get_union_member_row ) > 0){

                $union_member_detail = $get_union_member_row[0];

                $time   =  0;

                $cookie = Cookie::queue('sp_strong_root_union_membership_detail', $union_member_detail, $time);
                Cookie::queue('to_detail', $to_detail, $time);
                Cookie::queue('main_shipping_location', $main_shipping_location, $time);
                Cookie::queue('quantity', $quantity, $time);
                Cookie::queue('simcard', $simcard, $time);
                Cookie::queue('product_id', $product_id, $time);

                $user_id = $union_member_detail->id;
                $sp_user_name = $union_member_detail->name.' '.$union_member_detail->surname;

            } else {

                return response()->json([
                    'status' => false,
                    'message' => 'Detail not found.'
                ], 200);
            }
            
            $log_action = 'Verify';

            $log_data_values = array(
				'user_id' => $user_id,
				'name' => $sp_user_name,
				'record_id' => '',
				'menu' => 'Login verification from Union Member',
				'action' => $log_action,
				'curent_route' => $this->curent_route,
				'request_data' => $request->all(),
                'updated_at' => date('Y-m-d H:i:s')
			);

            Log::debug("Sp Log Store", $log_data_values );

			$msg_arr = 'Verified successfully.';
			
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
     * View create Union Order the specified resource from storage.
     *
     * @param  \App\products  $products
     * @return \Illuminate\Http\Response
     */
    public function createUnionMembershipOrderQuotation(Request $request)
    { 

        if( Cookie::get('sp_strong_root_union_membership_detail')===null ){

            return redirect()->back(); 
        }

        // get union detail

        $union_member_detail = json_decode(Cookie::get('sp_strong_root_union_membership_detail'));

        // $to_detail                  = $request->get('to_detail');
        // $main_shipping_location     = $request->get('main_shipping_location');
        // $po_number                  = $request->get('po_number');
        // $quantity                   = $request->get('quantity');
        // $simcard                    = $request->get('simcard');
        // $product_id                 = $request->get('product_id');

        $to_detail                  = Cookie::get('to_detail');
        $main_shipping_location     = Cookie::get('main_shipping_location');
        $po_number                  = Cookie::get('po_number');
        $quantity                   = Cookie::get('quantity');
        $simcard                    = Cookie::get('simcard');
        $product_id                 = Cookie::get('product_id');

        if( $quantity < 1 ){

            return redirect()->back(); 
        }

        $product_detail = Products::where('id',$product_id)->get();

        $all_detail = array(

            'to_detail'                 => $to_detail,
            'main_shipping_location'    => $main_shipping_location,
            'po_number'                 => $po_number,
            'quantity'                  => $quantity,
            'simcard'                   => $simcard,
            'product_id'                => $product_id,
            'product_detail'            => $product_detail,

        );

        $all_detail = (object) $all_detail;

        if($request->has('download')) {

            $data['data'] = array('options' => 'hide', 'all_detail' => $all_detail );

        	// pass view file
            $pdf = PDF::loadView('orders.create_union_membership_order_quotation', $data);
            // download pdf
            return $pdf->download('VendorQuotation.pdf');
        }

        //print_r($union_member_detail->union_name);die();

        // $user_id = $union_member_detail->id;
        // $sp_user_name = $union_member_detail->name.' '.$union_member_detail->surname;
   
        // $log_action = 'Create';

        // $log_data_values = array(
        //     'user_id' => $user_id,
        //     'name' => $sp_user_name,
        //     'record_id' => '',
        //     'menu' => 'Create Quotation from Union Member',
        //     'action' => $log_action,
        //     'curent_route' => $this->curent_route,
        //     'request_data' => $request->all(),
        //     'updated_at' => date('Y-m-d H:i:s')
        // );

        // Log::debug("Sp Log Store", $log_data_values );

        return view('orders.create_union_membership_order_quotation', [
            'title' => 'Order',
            'data' => array('options' => 'show', 'all_detail' => $all_detail )
        ]);
    }

    /**
     * Get union name list resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function unionNameListAutocompleteSearch(Request $request)
    { 
         
        $array=array();

        $name = $request->get('query');

        $users = DB::table('users')
        ->where('role_id', 4)
        ->where(function($query) use ($name){
            $query->where('first_name', 'LIKE', '%'. $name. '%');
            $query->orWhere('last_name', 'LIKE', '%'. $name. '%');
        })
        ->selectRaw('CONCAT(first_name, " ", last_name) as fullName')->paginate(50);

        foreach( $users as $user){
            array_push($array, $user->fullName);
        }

        array_unique($array);

        return response()->json($array);
    }

    /**
     * Get device list resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deviceListAutocompleteSearch(Request $request)
    { 
         
        $array=array();

        $name = $request->get('query');
        $device = $request->get('device');
        //DB::enableQueryLog();
        $products = DB::table('products')
        ->where('title', 'LIKE', '%'. $device. '%')
        ->where(function($query) use ($name){
            //$query->where('title', 'LIKE', '%'. $device. '%');
            $query->orWhere('title', 'LIKE', '%'. $name. '%');
        })
        ->selectRaw('title,id')->paginate(50);
        //dd(DB::getQueryLog());

        foreach( $products as $pro){

            $arr_val = array(
                'id'=> $pro->id,
                'label'=> $pro->title,
                'value'=> $pro->title,
            );

            array_push($array, $arr_val);

            //array_push($array, $user->title);
        }

        if( count( $array ) > 0 ){

            //array_unique($array);

        }

        

        return response()->json($array);
    }

    /**
     * Get products with search.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getProductWithSeacrh(Request $request)
    {

        // Validation
        $validate = Validator::make([
            'seacrh'                =>     $request->get('seacrh'),
            //'device'                =>     $request->get('device'),
        ], [
            'seacrh'                =>     'required',
            //'device'                =>     'required',
        ]);

        // If Validations Fails
        if($validate->fails()){

            return response()->json([
                'status' => false,
                'message' => $validate->errors()->first()
            ], 200);

        }

        try {
 
            $seacrh = $request->get('seacrh');
            //$device = $request->get('device');

            $msg_arr = '';
               
            $products = Products::where('id' , $seacrh)->get();

            $last_order_id = 0;
            
            if( count( $products ) > 0 ){
                
                $i = 1;

                foreach($products as $row){

                    $last_order_id = $row->id;

                    $msg_arr .= '<div class="col-md-4">
                    <div class="ns-card_1">
                        <h2 class="mb-sm-2">'.$row->title.'</h2>
                        <div class="ns_card_2">
                            <div class="row">
                                <div class="col-md-8 ">
                                <img class="h_s" src="'.$row->image.'">
                                </div>
                                <div class="col-md-4 ns_height">
                                <label for="lname" class="sp_simcard sp_simcard_'.$row->id.' " data-id="'.$row->id.'">AIS</label><br>
                                <label for="lname" class="sp_simcard sp_simcard_'.$row->id.' " data-id="'.$row->id.'">DTAC</label><br>
                                <label for="lname" class="sp_simcard sp_simcard_'.$row->id.' " data-id="'.$row->id.'">TRUE</label><br><span> เลือกค่าย</span>
                                </div>
                            </div>
                            <div class="ns_card_3">
                                <p class="pera mt-4 p-0">ราคาสินค้ารวม Internet <span class="ps-2">'.number_format($row->price, 2).'</span><span class="ps-2">บาท</span></p>
                                <p class="pera m-0 p-0">Internet Package <span class="ps-2"> 24 </span><span class="ps-2">เดือน</span></p>
                                <p class="pera m-0 p-0">อัตราการผ่อน <span class="text-danger ps-2" style="font-size:18px; font-weight:600;">'.number_format(ceil(($row->price)/24), 2).'</span> <span class="ps-2">บาท</span></p>
                                <p class="pera m-0 p-0">*ราคาสินค้าไม่รวมดอกเบี้ยเงินกู้</p>
                                         <p class="pera m-0 p-0">**สินค้าจัดส่งแบบคละสี</p>
                            </div>
                        </div>
                        <div class="ns_card_4 mt-3 ">
                            <a href="'.$row->link_spec.'" class="btn  number" target="_blank">สเปคเครื่อง</a>

                            <a  class="btn btn-primary ADD show_Union_Membership_modal"  data-id="'.$row->id.'">ขอใบเสนอราคา</a>
                                
                        </div>
                    </div>
                    </div>
                        
                        ';

                    $i++;				
                }

            } 
                
             
            return response()->json([
                'status' => true,
                'last_order_id' => $last_order_id,
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
     * Reset search.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function resetProductSeacrh(Request $request)
    {
        
        $msg_arr = '';

        try {
 
            $products = Products::orderBy('id', 'desc')->paginate(15);

            $last_order_id = 0;
            
            if( count( $products ) > 0 ){
                
                $i = 1;

                foreach($products as $row){

                    $last_order_id = $row->id;

                    $msg_arr .= '<div class="col-md-4">
                    <div class="ns-card_1">
                        <h2 class="mb-sm-2">'.$row->title.'</h2>
                        <div class="ns_card_2">
                            <div class="row">
                                <div class="col-md-8 ">
                                <img class="h_s" src="'.$row->image.'">
                                </div>
                                <div class="col-md-4 ns_height">
                                <label for="lname" class="sp_simcard sp_simcard_'.$row->id.' " data-id="'.$row->id.'">AIS</label><br>
                                <label for="lname" class="sp_simcard sp_simcard_'.$row->id.' " data-id="'.$row->id.'">DTAC</label><br>
                                <label for="lname" class="sp_simcard sp_simcard_'.$row->id.' " data-id="'.$row->id.'">TRUE</label><br><span> เลือกค่าย</span>
                                </div>
                            </div>
                            <div class="ns_card_3">
                                <p class="pera mt-4 p-0">ราคาสินค้ารวม Internet <span class="ps-2">'.number_format($row->price, 2).'</span><span class="ps-2">บาท</span></p>
                                <p class="pera m-0 p-0">Internet Package <span class="ps-2"> 24 </span><span class="ps-2">เดือน</span></p>
                                <p class="pera m-0 p-0">อัตราการผ่อน <span class="text-danger ps-2" style="font-size:18px; font-weight:600;">'.number_format(ceil(($row->price)/24), 2).'</span> <span class="ps-2">บาท</span></p>
                                <p class="pera m-0 p-0">*ราคาสินค้าไม่รวมดอกเบี้ยเงินกู้</p>
                                         <p class="pera m-0 p-0">**สินค้าจัดส่งแบบคละสี</p>
                            </div>
                        </div>
                        <div class="ns_card_4 mt-3 ">
                            <a href="'.$row->link_spec.'" class="btn  number" target="_blank">สเปคเครื่อง</a>

                            <a  class="btn btn-primary ADD show_Union_Membership_modal"  data-id="'.$row->id.'">ขอใบเสนอราคา</a>
                                
                        </div>
                    </div>
                    </div>
                        
                        ';

                    $i++;				
                }

            } 
             
            return response()->json([
                'status' => true,
                'last_order_id' => $last_order_id,
                'message' => $msg_arr 
            ], 200);

        } catch(\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 200);

        }
    }

    public function UnionLogin(Request $request)
    {

        // Validation
        $validate = Validator::make([
            'email'                 =>     $request->get('email'),
            'password'              =>     $request->get('password')
        ], [
            'email'                 =>     'required',
            'password'              =>     'required'
        ]);

        // If Validations Fails
        if($validate->fails()){

            return response()->json([
                'status' => false,
                'message' => $validate->errors()->first()
            ], 200);

        }

        try {

            $user = User::where('email', $request->get('email'))->first();

            if ($user && $user->status == 0) {
    
                return response()->json([
                    'status' => false,
                    'message' => 'Your Account is inactive, please contact Admin.'
                ], 200);
    
            }  else {
    
                $credentials = ['email' => $request->get('email'), 'password' => $request->get('password'),  'role_id' => 4];
    
                if (  !Auth::attempt($credentials, $request->has('remember'))) {
                    return response()->json([
                        'status' => false,
                        'message' => 'These credentials do not match our records.'
                    ], 200);
                } 
    
            }
            
            $log_action = 'Login';

            $log_data_values = array(
				'user_id' => $user->id,
				'name' => $user->first_name.' '.$user->last_name,
				'record_id' => $user->id,
				'menu' => 'Union Login',
				'action' => $log_action,
				'curent_route' => $this->curent_route,
				'request_data' => $request->all(),
                'updated_at' => date('Y-m-d H:i:s')
			);

            Log::debug("Sp Log Store", $log_data_values );

			$msg_arr = 'Login successfully.';
			
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

        

        //return redirect()->intended(URL::route('dashboard'));

    }

    // union logout

    public function UnionLogout(Request $request) {
        Auth::logout();
        return redirect('/');
    }
}
