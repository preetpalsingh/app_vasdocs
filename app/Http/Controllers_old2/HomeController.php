<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:home', ['only' => ['index']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $curent_date = date('Y-m-d');

        $last_date = date('Y-m-d', strtotime('-7 days'));

        $dashboard_data = DB::select("SELECT  
		
		count(case when (t1.status = '0' OR t1.status = '1' ) AND date(created_at) BETWEEN '".$curent_date."' AND '".$last_date."'  then 1 else null end) AS total_new_orders,

		count(case when t1.status = '0' AND date(created_at) BETWEEN '".$curent_date."' AND '".$last_date."' then 1 else null end) AS total_new_orders_draft,

		count(case when t1.status = '3' then 1 else null end) AS total_wait_for_delivery,

		count(case when t1.status = '1' then 1 else null end) AS total_po_recieved,
        
        count(case when t1.status = '6' then 1 else null end) AS total_finish_orders
        
        FROM orders as t1");

        // get last 24 hours activity

        $log_count = DB::table('my_lovely_log')->whereRaw('`created_at` > (NOW() - INTERVAL 24 HOUR)')->count();

        $dashboard_data = $dashboard_data[0];

        $dashboard_data->log_count = $log_count;

        //print_r($dashboard_data);

        return view('home', [
            'title' => '',
            'data' => $dashboard_data
        ]);
    }

    /**
     * User Profile
     * @param Nill
     * @return View Profile
     * @author Shani Singh
     */
    public function getProfile()
    {
        return view('profile');
    }

    /**
     * Update Profile
     * @param $profileData
     * @return Boolean With Success Message
     * @author Shani Singh
     */
    public function updateProfile(Request $request)
    {
        #Validations
        $request->validate([
            'first_name'    => 'required',
            'last_name'     => 'required',
            'mobile_number' => 'required|numeric|digits:10',
        ]);

        try {
            DB::beginTransaction();
            
            #Update Profile Data
            User::whereId(auth()->user()->id)->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'mobile_number' => $request->mobile_number,
                'address' => $request->address,
            ]);

            #Commit Transaction
            DB::commit();

            #Return To Profile page with success
            return back()->with('success', 'Profile Updated Successfully.');
            
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Change Password
     * @param Old Password, New Password, Confirm New Password
     * @return Boolean With Success Message
     * @author Shani Singh
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        try {
            DB::beginTransaction();

            #Update Password
            User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
            
            #Commit Transaction
            DB::commit();

            #Return To Profile page with success
            return back()->with('success', 'Password Changed Successfully.');
            
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }
}
