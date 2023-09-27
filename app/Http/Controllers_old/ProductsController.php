<?php


namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Products;
use App\Models\ProductVendorStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use File;
use Log;

class ProductsController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public $sp_user_id;
    public $sp_user_name;

    public function __construct(Products $products,User $user, Request $request, Auth $auth)
    { 
        $this->middleware('auth');
        $this->middleware('permission:product-list', ['only' => ['index']]);
        $this->middleware('permission:product-add', ['only' => ['store']]);
        $this->middleware('permission:product-update', ['only' => ['update']]);
        $this->middleware('permission:product-update-stock-by-vendor', ['only' => ['updateSalePriceAndStock']]);
        $this->middleware('permission:product-view-stock-by-vendor', ['only' => ['viewSalePriceAndStock']]);
        $this->middleware('permission:product-delete', ['only' => ['delete']]);
        $this->products = $products;

        $this->middleware(function ($request, $next) {

            $this->sp_user_id = Auth::user()->id;
            $this->sp_user_name = Auth::user()->first_name.' '.Auth::user()->last_name;
            
            return $next($request);
        });

        $curent_uri = $request->path();
		
        $curent_uri_arr = explode('/',$curent_uri);
        
        $curent_route = end($curent_uri_arr);

        $this->curent_route = $curent_route;

		//$this->user = $user;
       // die('ddert');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        if( Auth::user()->hasRole('Admin') ){

            $products = Products::orderBy('id', 'desc')->paginate(10);

        } else {

            $user_id = $this->sp_user_id;

            $products = Products::leftJoin('vendor_products', function($q) use ($user_id)
            {
                $q->on('products.id', 'vendor_products.product_id')
                    ->where('vendor_products.user_id', $user_id);
            })
            ->select('products.*' ,'vendor_products.sale_price','vendor_products.stock')
            ->orderBy('id', 'DESC')
            ->paginate(10);        

        }

        return view('products.index', [
            'title' => 'Phone',
            'data' => $products
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation
        $validate = Validator::make([
            'title'             =>      $request->get('title'),
            'price'             =>      $request->get('price'),
            'link_spec'         =>      $request->get('link_spec'),
            //'file'             =>      $request->get('image'),
        ], [
            'title'             =>      'required',
            'price'             =>      'required',
            'link_spec'         =>      'required',
            //'file'             =>      'required|mimes:jpeg,png,jpg,gif',
        ]);

        // If Validations Fails
        if($validate->fails()){

            return response()->json([
                'status' => false,
                'message' => $validate->errors()->first()
            ], 200);

        }
		
        try {

            $link_spec = $request->get('link_spec');
			
			if(!empty($link_spec)){
			
				if (!filter_var($link_spec, FILTER_VALIDATE_URL)) {
					
					return response()->json([
								'status' => false,
								'message' => 'Please enter valid url !'
							], 200);
				}
				
			}
			
            // Convert base64 to image object and save
            $image1 = $request->get('image');
			
			$imgName = '';
			
            if ($image1 != '') {
                //$imgName = $this->storeImage($image1, 'banner1');
				
				if (preg_match('/^data:image\/(\w+);base64,/', $image1, $type)) {
					$data = substr($image1, strpos($image1, ',') + 1);
					$type = strtolower($type[1]); // jpg, png, gif

					if (!in_array($type, [ 'jpg', 'jpeg', 'gif', 'png', 'webp' ])) {
						return response()->json([
							'status' => false,
							'message' => 'invalid image type'
						], 200);
					}

					$data = base64_decode($data);

					if ($data === false) {
						
						return response()->json([
							'status' => false,
							'message' => 'base64_decode failed'
						], 200);
						
					}
					
					$imgName = time().'_img.'.$type;
					
					file_put_contents(public_path().'/products/'.$imgName, $data);
					
					$imgName = url('/products/').'/'.$imgName;
					
				} else {
					
					return response()->json([
							'status' => false,
							'message' => 'Only png,jpeg,jpg,png,webp format allowed !'
						], 200);
					
				}
				
                //$this->products->image = url('/products/').'/'.$imgName;
            }

            $title 		        =   $request->get('title');
            $price 	            = 	$request->get('price');
            $link_spec 		    = 	$request->get('link_spec');
            $period 			= 	$request->get('period');

			$this->products->title                      =   $title;
			$this->products->price                      =   $price;
			$this->products->link_spec                  =   $link_spec;
			$this->products->image                      =   $imgName;
            $this->products->created_at                 =   date('Y-m-d H:i:s');

            $this->products->save();

            // insert action

            $log_data_values = array(
				'user_id' => $this->sp_user_id,
				'name' => $this->sp_user_name,
				'record_id' => $this->products->id,
				'menu' => 'Product',
				'action' => 'Add',
				'curent_route' => $this->curent_route,
				'request_data' => $request->all(),
                'updated_at' => date('Y-m-d H:i:s')
			);

            Log::debug("Sp Log Store", $log_data_values );
			
            $msg_arr = 'Product has been created';
			
            //$this->products->save();
			
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
     * Update resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Validation
        $validate = Validator::make([
            'title'             =>      $request->get('title'),
            'price'             =>      $request->get('price'),
            'link_spec'         =>      $request->get('link_spec'),
            //'image'             =>      $request->get('image'),
        ], [
            'title'             =>      'required',
            'price'             =>      'required',
            'link_spec'         =>      'required',
            //'image'             =>      'required|image|mimes:jpg|gif|png',
        ]);

        // If Validations Fails
        if($validate->fails()){

            return response()->json([
                'status' => false,
                'message' => $validate->errors()->first()
            ], 200);

        }

        try {

            $link_spec = $request->get('link_spec');
			
			if(!empty($link_spec)){
			
				if (!filter_var($link_spec, FILTER_VALIDATE_URL)) {
					
					return response()->json([
								'status' => false,
								'message' => 'Please enter valid url !'
							], 200);
				}
				
			}
			
			$edit_id            =   $request->get('edit_id');
            $previous_img_val 	=   $request->get('previous_img_val');
			
            // Convert base64 to image object and save
            $image1 = $request->get('image');
			
			$imgName = '';
			
            if ($image1 != '') {
				
				if (preg_match('/^data:image\/(\w+);base64,/', $image1, $type)) {
					$data = substr($image1, strpos($image1, ',') + 1);
					$type = strtolower($type[1]); // jpg, png, gif

					if (!in_array($type, [ 'jpg', 'jpeg', 'gif', 'png', 'webp' ])) {
						return response()->json([
							'status' => false,
							'message' => 'invalid image type'
						], 200);
					}

					$data = base64_decode($data);

					if ($data === false) {
						
						return response()->json([
							'status' => false,
							'message' => 'base64_decode failed'
						], 200);
						
					}
					
					$imgName = time().'_img.'.$type;
					
					file_put_contents(public_path().'/products/'.$imgName, $data);
					
					$imgName = url('/products/').'/'.$imgName;
					
				} else {
					
					return response()->json([
							'status' => false,
							'message' => 'Only png,jpeg,jpg,png,webp format allowed !'
						], 200);
					
				}

            }
			
			if($edit_id > 0){
				
				if ($image1 == '') {
					
					$imgName = $previous_img_val;
				}
				
			}

            $title 		        =   $request->get('title');
            $price 	            = 	$request->get('price');
            $link_spec 		    = 	$request->get('link_spec');

            $this->products     =   Products::find($edit_id);

			$this->products->title                      =   $title;
			$this->products->price                      =   $price;
			$this->products->link_spec                  =   $link_spec;
			$this->products->image                      =   $imgName;
            $this->products->updated_at                 =   date('Y-m-d H:i:s');

            $this->products->save();

            // insert action

            $log_data_values = array(
				'user_id' => $this->sp_user_id,
				'name' => $this->sp_user_name,
				'record_id' => $edit_id,
				'menu' => 'Product',
				'action' => 'Edit',
				'curent_route' => $this->curent_route,
				'request_data' => $request->all(),
                'updated_at' => date('Y-m-d H:i:s')
			);

            Log::debug("Sp Log Store", $log_data_values );
			
			//dd($values);die('yes55');
			
			if($edit_id > 0){
				
				$msg_arr = 'Product updated successfully';
				
				// delete previous image
				
				if($edit_id > 0){
				
					if ($image1 != '') {
						
						$domain_url = url('');
						
						$item = str_replace($domain_url,'',$previous_img_val);
					
						$file_del = public_path().'/'.$item;
					
						if (is_file($file_del) && file_exists($file_del)) {
						
							$st = File::delete($file_del);
							
						}
					}
				
				}
				
			} 
			
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
     * Update Sale price and Stock resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateSalePriceAndStock(Request $request)
    { 

        // Validation
        $validate = Validator::make([
            'product_id'             =>      $request->get('edit_id'),
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

            $user_id = $this->sp_user_id;

            $sale_price 		        =   $request->get('sale_price');
            $stock 	                    = 	$request->get('stock');
            $product_id 	            = 	$request->get('edit_id');

            $log_action = 'Add';
				
			// Check product stock exist for vendor
		
            $row_check = ProductVendorStock::where('user_id', $user_id)->where('product_id', $product_id)->count();
				
            if( $row_check > 0 ) {
            
                $vendor_stock_values = array(
                    'sale_price'    =>  $sale_price,
                    'stock'         =>  $stock,
                    'updated_at'    =>  date('Y-m-d H:i:s'),
                );
                
                DB::table('vendor_products')->where('user_id', $user_id)->where('product_id', $product_id)->update($vendor_stock_values);

                $log_action = 'Edit';
                    
            } else {
                
                $vendor_stock_values = array(
                    'user_id'       =>  $user_id,
                    'product_id'    =>  $product_id,
                    'sale_price'    =>  $sale_price,
                    'stock'         =>  $stock,
                    'created_at'    =>  date('Y-m-d H:i:s'),
                );
                
                DB::table('vendor_products')->insert($vendor_stock_values);
            }	

            // insert action

            $log_data_values = array(
				'user_id' => $user_id,
				'name' => $this->sp_user_name,
				'record_id' => $product_id,
				'menu' => 'Update Product Vendor Stock',
				'action' => $log_action,
				'curent_route' => $this->curent_route,
				'request_data' => $request->all(),
                'updated_at' => date('Y-m-d H:i:s')
			);

            Log::debug("Sp Log Store", $log_data_values );

			$msg_arr = 'Product updated successfully.';
			
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
     * View vendor Sale price and Stock for product resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response 
     */
    public function viewSalePriceAndStock(Request $request)
    {


        // Validation
        $validate = Validator::make([
            'product_id'             =>      $request->get('edit_id'),
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

            $user_id                    =   $this->sp_user_id;
            $product_id 	            = 	$request->get('edit_id');

            $log_action = 'View';

            $msg_arr = '<div class="alert alert-danger ">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close"></a>
                            No record found.
                        </div>';
                      
            $vendors = DB::select('SELECT t1.* , t2.first_name , t2.last_name FROM `vendor_products` t1 INNER JOIN `users` t2 ON t1.user_id = t2.id AND t1.product_id = '.$product_id.'');

            
            if(count(array_filter($vendors)) > 0){
                
                $msg_arr = '<table class="table table-bordered table-striped dataTable no-footer" >
                                <thead>
                                    <tr>
                                    
                                        <th class="text-center">No.</th>
                                        <th class="text-center">Vendor</th>
                                        <th class="text-center">Sale Price</th>
                                        <th class="text-center">Stock</th>
                                    </tr>
                                </thead>
                                <tbody>';
                                
                $i = 1;
                
                foreach($vendors as $row){
                
                    $msg_arr .= '<tr id="row_'.$row->id.'">
                                        
                        <td class="text-center">'.$i.'</td>
                        <td class="text-center row_text_'.$row->id.'">'.$row->first_name.' '.$row->last_name.'</td>
                        <td class="text-center row_sale_price_'.$row->id.'">'.number_format($row->sale_price, 2).'</td>
                        <td class="text-center row_stock_'.$row->id.'">'.$row->stock.'</td></tr>';

                    $i++;				
                }
            
                $msg_arr .= '</tbody></table>';
                
            } 

            // insert action

            $log_data_values = array(
				'user_id' => $user_id,
				'name' => $this->sp_user_name,
				'record_id' => $product_id,
				'menu' => 'View Product Vendor Stock',
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
     * Remove the specified resource from storage.
     *
     * @param  \App\products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    { 

        $delete_id = $request->get('delete_id');

        $delete_data = DB::table('products')->where('id', $delete_id)->get();

        $previous_img_val = $delete_data[0]->image;

        if ( ! empty( $previous_img_val ) ) {
						
            $domain_url = url('');
            
            $item = str_replace($domain_url,'',$previous_img_val);
        
            $file_del = public_path().$item;
        
            if (is_file($file_del) && file_exists($file_del)) {
            
                $st = File::delete($file_del);
                
            }
        }
       
        $this->products->find($delete_id)->delete();

        // insert action

        $log_data_values = array(
            'user_id' => $this->sp_user_id,
            'name' => $this->sp_user_name,
            'record_id' => $delete_id,
            'menu' => 'Product',
            'action' => 'Delete',
            'curent_route' => $this->curent_route,
            'request_data' => $request->all(),
            'updated_at' => date('Y-m-d H:i:s')
        );

        Log::debug("Sp Log Store", $log_data_values );
        
        return response()->json(['status' => true], 200);
    }

	
}
