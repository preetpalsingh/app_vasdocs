<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ExcelImportExport;
use App\Imports\UnionMembershipImport;
use App\Models\UnionMembership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
//use Maatwebsite\Excel\Facades\Excel;

class UnionMembershipController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */

 
     public function __construct( UnionMembership $union_membership, Request $request, ExcelImportExport $excel_import_export )
     { 
        
        $this->middleware('auth');
        $this->middleware('permission:union-membership-list', ['only' => ['index']]);
        $this->middleware('permission:union-membership-add', ['only' => ['store']]);
        $this->middleware('permission:union-membership-update', ['only' => ['update']]);
        $this->middleware('permission:union-membership-delete', ['only' => ['destroy']]);
        $this->union_membership = $union_membership;

        $this->excel_import_export = $excel_import_export;
     }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // testing code if needed to hide options according to permissions starts
        
        $allowed_permission_arr['union-membership-add'] = false;
        $allowed_permission_arr['union-membership-import'] = false;

        $sp_user_role = Auth::user()->roles->pluck('id')->toArray();

        $role = Role::whereId($sp_user_role[0])->with('permissions')->first();

        $allowed_permission = $role->permissions->pluck('id')->toArray();
        
        $permissions = Permission::where('name','union-membership-add')->orwhere('name','union-membership-import')->get();

        foreach ($permissions as $permissionIndex => $permission){

            if( in_array( $permission->id , $allowed_permission )){

                $allowed_permission_arr[$permission->name] = true;
            }
        }

        // testing code if needed to hide options according to permissions ends

        $data = UnionMembership::orderBy('id', 'desc')->paginate(10);
        
        return view('UnionMembership.index', [
            'title' => 'Union Membership',
            'data' => $data,
            'allowed_permissions' => $allowed_permission_arr
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
            'union_name'   => $request->get('union_name'),
            'union_member_id'   => $request->get('union_member_ID'),
            'Name'   => $request->get('name'),
        ], [
            'union_name'    =>  'required|',
            'union_member_id'    =>  'required|min:6',
            'Name'    =>  'required',
            //'Phone' => 'digits:10',
        ]);

        // If Validations Fails
        if($validate->fails()){

            return response()->json([
                'status' => false,
                'message' => $validate->errors()->first()
            ], 200);

            //return redirect()->route('users.index')->with('error', $validate->errors()->first());
        }
		
        try {
			
            $union_name 		= 		$request->get('union_name');
            $union_member_ID 	= 		$request->get('union_member_ID');
            $name 				= 		$request->get('name');
            $surname 			= 		$request->get('surname');
            $address 			= 		$request->get('address');
            $phone_number 		= 		$request->get('phone_number');

			$this->union_membership->union_name = $union_name;
            $this->union_membership->union_member_ID = $union_member_ID;
            $this->union_membership->name = $name;
            $this->union_membership->surname = $surname;
            $this->union_membership->address = $address;
            $this->union_membership->phone_number = $phone_number;
            $this->union_membership->status = 1;
            $this->union_membership->created_at = date('Y-m-d H:i:s');

            $this->union_membership->save();
            
            $msg_arr = 'Record has been added successfully.';
			
            //$this->banner->save();
			
            return response()->json([
                'status' => true,
                //'msg_arr' => $msg_arr,
                'message' => $msg_arr 
                //'message' => 'banner has been created.'
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

        $edit_id = $request->get('edit_id');

        // Validation
        $validate = Validator::make([
            'union_name'   => $request->get('union_name'),
            'union_member_id'   => $request->get('union_member_ID'),
            'Name'   => $request->get('name'),
            //'Phone'   => $request->get('phone_number'),
        ], [
            //'user_id'   =>  'required|exists:users,id',
            'union_name'    =>  'required|',
            'union_member_id'    =>  'required|unique:union_membership,union_member_ID,'.$edit_id.'|min:6',
            'Name'    =>  'required',
            //'Phone' => 'digits:10',
        ]);

        // If Validations Fails
        if($validate->fails()){

            return response()->json([
                'status' => false,
                'message' => $validate->errors()->first()
            ], 200);

            //return redirect()->route('users.index')->with('error', $validate->errors()->first());
        }
		
        try {
            
            $union_name 		= 		$request->get('union_name');
            $union_member_ID 	= 		$request->get('union_member_ID');
            $name 				= 		$request->get('name');
            $surname 			= 		$request->get('surname');
            $address 			= 		$request->get('address');
            $phone_number 		= 		$request->get('phone_number');

            $this->union_membership = UnionMembership::find($edit_id);

			$this->union_membership->union_name = $union_name;
            $this->union_membership->union_member_ID = $union_member_ID;
            $this->union_membership->name = $name;
            $this->union_membership->surname = $surname;
            $this->union_membership->address = $address;
            $this->union_membership->phone_number = $phone_number;
            $this->union_membership->updated_at = date('Y-m-d H:i:s');

            $this->union_membership->save();
            
            $msg_arr = 'Record has been updated successfully.';
			
            //$this->banner->save();
			
            return response()->json([
                'status' => true,
                //'msg_arr' => $msg_arr,
                'message' => $msg_arr 
                //'message' => 'banner has been created.'
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
            'user_id'   =>  'required|exists:union_membership,id',
            'status'    =>  'required|in:0,1',
        ]);

        // If Validations Fails
        if($validate->fails()){
            return redirect()->route('admin.umlist')->with('error', $validate->errors()->first());
        }

        try {
            DB::beginTransaction();

            // Update Status
            UnionMembership::whereId($user_id)->update(['status' => $status]);

            // Commit And Redirect on index with Success Message
            DB::commit();
            return redirect()->route('admin.umlist')->with('success','User Status Updated Successfully!');
        } catch (\Throwable $th) {

            // Rollback & Return Error Message
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Import Users 
     * @param Null
     * @return View File
     */
    public function importUnionMembership()
    {
        return view('UnionMembership.import', [
            'title' => 'Union Membership'
        ]);
    }

    public function uploadUnionMembership(Request $request)
    {
        //Excel::import(new UnionMembershipImport, $request->file);

        // Validations
        $request->validate([
            'file'    => 'required|mimes:csv',
        ]);

        try {

            $csv = $request->file('file');

            $result = $this->excel_import_export->excel_import_users(); 
            
            return redirect()->route('admin.umimport')->with('success', 'User Imported Successfully');

        } catch (\Throwable $th) {
            // Rollback and return with Error
            //DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    { 
        $delete_id = $request->get('delete_id');

        $this->union_membership->find($delete_id)->delete();

        return response()->json(['status' => true], 200);
    }
    //
}
