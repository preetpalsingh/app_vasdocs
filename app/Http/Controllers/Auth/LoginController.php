<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
// OR
// use App\Models\User;  
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // protected function credentials(Request $request)
    // {
    //     return [
    //         'email' => request()->email,
    //         'password' => request()->password,
    //         'status' => 1
    //     ];
    // }

    public function authenticated(Request $request, $user)
    {
        

        if (! $user->status) {
            Auth::logout();
            
            return redirect()->route('login')->with('error', 'Your Account is inactive, please contact Admin.');
        }
        
        if ( Auth::user()->hasRole('Admin') ){

            //return redirect(RouteServiceProvider::HOME);exit();
            return redirect(route('dashboard'));exit();

        } else if ( Auth::user()->role_id == 3 ){
            
            return redirect(route('home', ['status' => 'all']));exit();
            
        } else if ( Auth::user()->role_id == 4 ){

            return redirect(route('dashboard'));exit();
            
        } else if ( Auth::user()->role_id == 5 ){

            return redirect(route('admin.developerList'));exit();
            
        }  /* else if ( Auth::user()->hasRole('Simcard Vendor') ){

            return redirect(route('admin.orderSVL'));exit();
            
        } */

        if ( !Auth::user()->hasRole('Admin') ){
            return redirect()->route('login')->with('error', 'These credentials do not match our records.');
        }

        
    }
}
