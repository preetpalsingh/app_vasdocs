<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::Login;

    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:4',
        ];
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
         $request->validate($this->rules(), $this->validationErrorMessages());

        // Get the user by their email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->route('password.reset')
                ->with('error', 'Password reset failed. User not found.');
        }

        // Reset the user's password
        $user->password = Hash::make($request->password);
        $user->save();

        // Log the user in after a successful password reset (optional)
        //Auth::login($user);
        
        // Redirect to the login page
        return redirect()->route('login')
            ->with('success', 'Your password has been reset! You can now log in.');
    }

}
