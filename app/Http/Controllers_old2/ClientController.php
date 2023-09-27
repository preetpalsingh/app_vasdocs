<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Simcard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Log;

class ClientController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

     public $sp_user_id;
     public $sp_user_name;
 
     public function __construct(Simcard $simcard,User $user, Request $request, Auth $auth)
     { 
        $this->middleware('auth');
        $this->simcard = $simcard;

        $this->middleware(function ($request, $next) {

        $this->sp_user_id = Auth::user()->id;
        $this->sp_user_name = Auth::user()->first_name.' '.Auth::user()->last_name;
        $this->menu_name = 'Simcard';
            
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
        
    $simcards = Simcard::orderBy('id', 'desc')->paginate(10);

    return view('backend.clients.index', [
        'title' => 'Client',
        'data' => $simcards
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
            'simcard_provider'      =>      $request->get('simcard_provider'),
            'monthly_price'         =>      $request->get('monthly_price'),
            'period'                =>      $request->get('period'),
        ], [
            'simcard_provider'      =>      'required',
            'monthly_price'         =>      'required',
            'period'                =>      'required',
        ]);

        // If Validations Fails
        if($validate->fails()){

            return response()->json([
                'status' => false,
                'message' => $validate->errors()->first()
            ], 200);

        }
		
        try {
			
            $simcard_provider 		            =   $request->get('simcard_provider');
            $monthly_price 	                    = 	$request->get('monthly_price');
            $period 			                = 	$request->get('period');
            $description 			            = 	$request->get('description');

			$this->simcard->simcard_provider   =   $simcard_provider;
			$this->simcard->monthly_price      =   $monthly_price;
			$this->simcard->period             =   $period;
			$this->simcard->description        =   $description;
            $this->simcard->created_at         =   date('Y-m-d H:i:s');

            $this->simcard->save();

            // insert action

            $log_data_values = array(
				'user_id' => $this->sp_user_id,
				'name' => $this->sp_user_name,
				'record_id' => $this->simcard->id,
				'menu' => $this->menu_name,
				'action' => 'Add',
				'curent_route' => $this->curent_route,
				'request_data' => $request->all(),
                'updated_at' => date('Y-m-d H:i:s')
			);

            Log::debug("Sp Log Store", $log_data_values );
			
            $msg_arr = 'Simcard has been created';
			
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
            'simcard_provider'      =>      $request->get('simcard_provider'),
            'monthly_price'         =>      $request->get('monthly_price'),
            'period'                =>      $request->get('period'),
        ], [
            'simcard_provider'      =>      'required',
            'monthly_price'         =>      'required',
            'period'                =>      'required',
        ]);

        // If Validations Fails
        if($validate->fails()){

            return response()->json([
                'status' => false,
                'message' => $validate->errors()->first()
            ], 200);

        }

        try {

			$edit_id                            =   $request->get('edit_id');

            $simcard_provider 		            =   $request->get('simcard_provider');
            $monthly_price 	                    = 	$request->get('monthly_price');
            $period 			                = 	$request->get('period');
            $description 			            = 	$request->get('description');

            $this->simcard                      =   Simcard::find($edit_id);

			$this->simcard->simcard_provider    =   $simcard_provider;
			$this->simcard->monthly_price       =   $monthly_price;
			$this->simcard->period              =   $period;
			$this->simcard->description         =   $description;
            $this->simcard->updated_at          =   date('Y-m-d H:i:s');

            $this->simcard->save();

            // insert action

            $log_data_values = array(
				'user_id' => $this->sp_user_id,
				'name' => $this->sp_user_name,
				'record_id' => $edit_id,
				'menu' => $this->menu_name,
				'action' => 'Edit',
				'curent_route' => $this->curent_route,
				'request_data' => $request->all(),
                'updated_at' => date('Y-m-d H:i:s')
			);

            Log::debug("Sp Log Store", $log_data_values );
			
			//dd($values);die('yes55');
			
			if($edit_id > 0){
				
				$msg_arr = 'Simcard updated successfully';
				
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
     * Remove the specified resource from storage.
     *
     * @param  \App\products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    { 

        $delete_id = $request->get('delete_id');

        $this->simcard->find($delete_id)->delete();

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
