<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Developer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Log;

class DeveloperController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

     public $sp_user_id;
     public $sp_user_name;
 
     public function __construct(Developer $developer,User $user, Request $request, Auth $auth)
     { 
        $this->middleware('auth');
        $this->developer = $developer;

        $this->middleware(function ($request, $next) {

        $this->sp_user_id = Auth::user()->id;
        $this->sp_user_name = Auth::user()->first_name.' '.Auth::user()->last_name;
        $this->menu_name = 'Developer';
            
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
        
        $data = Developer::orderBy('id', 'desc')->paginate(10);

        return view('backend.developer.index', [
            'title' => 'Task',
            'data' => $data
        ]);
    
    }

    public function search(Request $request)
    {
        
        $query = $request->get('query');
        if( !empty( $query ) ){

            $data = Developer::whereRaw('(title like ?)', ['%' . $query . '%'])->orderBy('date', 'desc')->paginate(10);

        } else {

            $data = Developer::orderBy('date', 'desc')->paginate(10);
        }
        
            return response()->json([
                'view' => view('backend.developer.list',  [
                    'title' => 'Task',
                    'data' => $data
                ])->render(),
                'pagination' => $data->links()->toHtml(),
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

        
        $validate = Validator::make([
            'date'              =>      $request->get('date'),
            'title'             =>      $request->get('title'),
        ], [
            'date'              =>      'required',
            'title'             =>      'required',
        ]);

        // If Validations Fails
        if($validate->fails()){

            return response()->json([
                'status' => false,
                'message' => $validate->errors()->first()
            ], 200);

        }
		
        try {

            $this->client = new Developer();

			$this->client->date             =   $request->get('date');
			$this->client->title            =   $request->get('title');
			$this->client->description      =   $request->get('description');
			$this->client->status           =   $request->get('status');
            $this->client->created_at       =   date('Y-m-d H:i:s');

            $this->client->save();

            $user_id = $this->client->id;

            // insert action

            /* $log_data_values = array(
				'user_id' => $this->sp_user_id,
				'name' => $this->sp_user_name,
				'record_id' => $this->developer->id,
				'menu' => $this->menu_name,
				'action' => 'Add',
				'curent_route' => $this->curent_route,
				'request_data' => $request->all(),
                'updated_at' => date('Y-m-d H:i:s')
			);

            Log::debug("Sp Log Store", $log_data_values ); */
			
            $msg_arr = 'Developer task has been created';
			
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

        //print($request->get('first_name'));die('U');

        // Validation
        $validate = Validator::make([
            'date'              =>      $request->get('date'),
            'title'             =>      $request->get('title'),
            'edit_id'             =>      $request->get('edit_id'),
        ], [
            'date'              =>      'required',
            'title'             =>      'required',
            'edit_id'           =>      'required',
        ]);

        // If Validations Fails
        if($validate->fails()){

            return response()->json([
                'status' => false,
                'message' => $validate->errors()->first()
            ], 200);

        }
		
        try {

            $edit_id           =   $request->get('edit_id');

            $this->client                   =   Developer::find($edit_id);

			$this->client->date             =   $request->get('date');
			$this->client->title            =   $request->get('title');
			$this->client->description      =   $request->get('description');
			$this->client->status           =   $request->get('status');
            $this->client->updated_at       =   date('Y-m-d H:i:s');

            $this->client->save();

            // insert action

            /* $log_data_values = array(
				'user_id' => $this->sp_user_id,
				'name' => $this->sp_user_name,
				'record_id' => $this->developer->id,
				'menu' => $this->menu_name,
				'action' => 'Add',
				'curent_route' => $this->curent_route,
				'request_data' => $request->all(),
                'updated_at' => date('Y-m-d H:i:s')
			);

            Log::debug("Sp Log Store", $log_data_values ); */
			
            $msg_arr = 'Developer task has been updated';
			
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
     * Remove the specified resource from storage.
     *
     * @param  \App\products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    { 

        $delete_id = $request->get('delete_id');

        $this->developer->find($delete_id)->delete();

        // insert action

        /* $log_data_values = array(
            'user_id' => $this->sp_user_id,
            'name' => $this->sp_user_name,
            'record_id' => $delete_id,
            'menu' => $this->menu_name,
            'action' => 'Delete',
            'curent_route' => $this->curent_route,
            'request_data' => $request->all(),
            'updated_at' => date('Y-m-d H:i:s')
        );

        Log::debug("Sp Log Store", $log_data_values ); */
        
        return response()->json(['status' => true], 200);
    }
}
