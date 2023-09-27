<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Simcard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Log;

class StaffController extends Controller
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
        $this->menu_name = 'Staff';
            
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
        
        $data = User::where('role_id', 3)->orderBy('id', 'desc')->paginate(10);

        return view('backend.staff.index', [
            'title' => 'Staff',
            'data' => $data
        ]);
    
    }

    public function search(Request $request)
    {
        
        $query = $request->get('query');
        if( !empty( $query ) ){

            $data = User::whereRaw('(first_name like ? or email like ? or mobile_number like ?)', ['%' . $query . '%', '%' . $query . '%', '%' . $query . '%'])->where('role_id', 4)->orderBy('id', 'desc')->paginate(10);

        } else {

            $data = User::where('role_id', 4)->orderBy('id', 'desc')->paginate(10);
        }
        

        //if ($request->ajax()) {
            return response()->json([
                'view' => view('backend.staff.list',  [
                    'title' => 'Staff',
                    'data' => $data
                ])->render(),
                'pagination' => $data->links()->toHtml(),
            ]);
        /* }
        //die('yes');
        return view('backend.staff.list',  [
            'title' => 'Staff',
            'data' => $data
        ]);  */
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //print($request->get('first_name'));die('A');
        // Validation
        $validate = Validator::make([
            'Name'              =>      $request->get('first_name'),
            'email'             =>      $request->get('email'),
            //'mobile_number'     =>      $request->get('mobile_number'),
        ], [
            'Name'              =>      'required',
            'Email'             => 'required|unique:users,email',
            //'mobile_number'     =>      'required',
        ]);

        // If Validations Fails
        if($validate->fails()){

            return response()->json([
                'status' => false,
                'message' => $validate->errors()->first()
            ], 200);

        }
		
        try {

            $this->client = new User();

			$this->client->first_name           =   $request->get('first_name');
			$this->client->email                =   $request->get('email');
			$this->client->mobile_number        =   $request->get('mobile_number');
			$this->client->role_id              =   4;
            $this->client->created_at           =   date('Y-m-d H:i:s');

            $this->client->save();

            $user_id = $this->client->id;
            
            // Retrieve the user by ID
            $user = User::find($user_id);

            // Call the method to send login details
            $user->sendLoginDetailMail();

            // insert action

            /* $log_data_values = array(
				'user_id' => $this->sp_user_id,
				'name' => $this->sp_user_name,
				'record_id' => $this->simcard->id,
				'menu' => $this->menu_name,
				'action' => 'Add',
				'curent_route' => $this->curent_route,
				'request_data' => $request->all(),
                'updated_at' => date('Y-m-d H:i:s')
			);

            Log::debug("Sp Log Store", $log_data_values ); */
			
            $msg_arr = 'Staff has been created';
			
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
            'Name'              =>      $request->get('first_name'),
            'email'             =>      $request->get('email'),
            //'mobile_number'     =>      $request->get('mobile_number'),
            'edit_id'           =>      $request->get('edit_id'),
        ], [
            'Name'              =>      'required',
            'Email'             => 'required',
            //'mobile_number'     =>      'required',
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

            $this->client                       =   User::find($edit_id);

			$this->client->first_name           =   $request->get('first_name');
			$this->client->email                =   $request->get('email');
			$this->client->mobile_number        =   $request->get('mobile_number');
            $this->client->updated_at           =   date('Y-m-d H:i:s');

            $this->client->save();

            // insert action

            /* $log_data_values = array(
				'user_id' => $this->sp_user_id,
				'name' => $this->sp_user_name,
				'record_id' => $this->simcard->id,
				'menu' => $this->menu_name,
				'action' => 'Add',
				'curent_route' => $this->curent_route,
				'request_data' => $request->all(),
                'updated_at' => date('Y-m-d H:i:s')
			);

            Log::debug("Sp Log Store", $log_data_values ); */
			
            $msg_arr = 'Staff has been updated';
			
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
     * Update Status Of User
     * @param Integer $status
     * @return List Page With Success
     * @author Shani Singh
     */
    public function updateStatus($user_id, $status)
    {
        // Validation
        $validate = Validator::make([
            'user_id'   => $user_id,
            'status'    => $status
        ], [
            'user_id'   =>  'required|exists:users,id',
            'status'    =>  'required|in:0,1',
        ]);

        // If Validations Fails
        if($validate->fails()){
            return redirect()->route('admin.staffList')->with('error', $validate->errors()->first());
        }

        try {
            DB::beginTransaction();

            // Update Status
            User::whereId($user_id)->update(['status' => $status]);

            // Commit And Redirect on index with Success Message
            DB::commit();
            return redirect()->route('admin.staffList')->with('success','User Status Updated Successfully!');
        } catch (\Throwable $th) {

            // Rollback & Return Error Message
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
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
