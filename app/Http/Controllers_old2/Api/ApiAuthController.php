<?php

/*copyright@2018 Radical Enlighten Co.,ltd.*/
    
namespace App\Http\Controllers\Api;
  
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Task;
use App\TaskHistory;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;

use Jrean\UserVerification\Traits\VerifiesUsers;
use Jrean\UserVerification\Facades\UserVerification;
use App\User;
use Mail;

class ApiAuthController extends ApiController
{
    use RegistersUsers;
    use VerifiesUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(User $user , Request $request)
    { 
	
		/* $request_headers = apache_request_headers();
		
		$curent_uri = $request->path();
		
		$curent_uri_arr = explode('/',$curent_uri);
		  
		$curent_route = end($curent_uri_arr);
		
		$request_data = $request->all();
		//dd($request_data);die();
		if(isset($request_headers['Authorization'])){
			
			$request_data['header']['Authorization'] = $request_headers['Authorization'];
		}
		
		if(isset($request_headers['Accept'])){
			
			$request_data['header']['Accept'] = $request_headers['Accept'];
		}
		
		if(isset($request_headers['Content-Type'])){
			
			$request_data['header']['Content-Type'] = $request_headers['Content-Type'];
		}
		
		$request_data['curent_uri'] = $curent_uri;
		 
		//echo '<pre>';print_r($request_data);die();
		$data = json_encode($request_data);
		$file = $curent_route.'_file.json'; 
		$destinationPath=public_path()."/banner1/";
		//if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
		File::put($destinationPath.$file,$data); */
		//return response()->download($destinationPath.$file);
		
	
        $this->middleware('auth:api', ['except' => ['login','login_with_phone', 'login_winspector', 'login_partner', 'register', 'verifyEmail', 'forgotPassword', 'resetPassword', 'changePassword', 'resendToken', 'test_otp', 'register_with_otp']]);
        $this->user = $user;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
		 
		//if($email == 'admin@gmail.com' || $email == 'wongthep.silapawetin@gmail.com' || $email == 'wongthep@radical-enlighten.com' ){
			
			$credentials = array('email'=>$email, 'password'=>$password);

			if (! $token = auth()->attempt($credentials)) {
				return response()->json(['error' => 'Unauthorized'], 401);
			}
			
		/* } else {
			
			return response()->json(['error' => 'Unauthorized'], 401);
		} */
		
        
		 
        return $this->respondWithToken($token);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login_with_phone(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
		 
		//if($email == 'admin@gmail.com' || $email == 'wongthep.silapawetin@gmail.com' || $email == 'wongthep@radical-enlighten.com' ){
			
			$credentials = array('phone'=>$email, 'password'=>$password);

			if (! $token = auth()->attempt($credentials)) {
				return response()->json(['error' => 'Unauthorized'], 401);
			}
			
		/* } else {
			
			return response()->json(['error' => 'Unauthorized'], 401);
		} */
		
        
		 
        return $this->respondWithToken($token);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login_partner(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
		 
		//if($email == 'admin@gmail.com' || $email == 'wongthep.silapawetin@gmail.com' || $email == 'wongthep@radical-enlighten.com' ){
			
			$credentials = array('email'=>$email, 'password'=>$password);

			if (! $token = auth()->attempt($credentials)) {
				return response()->json(['error' => 'Unauthorized'], 401);
			}
			
		/* } else {
			
			return response()->json(['error' => 'Unauthorized'], 401);
		} */
		
        
		 
        return $this->respondWithTokenPartner($token);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login_winspector(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
		 
		//if($email == 'admin@gmail.com' || $email == 'wongthep.silapawetin@gmail.com' || $email == 'wongthep@radical-enlighten.com' ){
			
			$credentials = array('email'=>$email, 'password'=>$password);

			if (! $token = auth()->attempt($credentials)) {
				return response()->json(['error' => 'Unauthorized'], 401);
			}
			
		/* } else {
			
			return response()->json(['error' => 'Unauthorized'], 401);
		} */
		
        
		 
        return $this->respondWithTokenWinspector($token);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
		/* $request_data = $request->all();
		
		$data = json_encode($request_data);
      $file = 'register_file.json'; 
      $destinationPath=public_path()."/banner1/";
      if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
      File::put($destinationPath.$file,$data);
      return response()->download($destinationPath.$file);
		echo $request->get('name');die();  */
		 
        try {
            $newId = $this->user->orderBy('created_at', 'desc')->first()->id + 1;
            if ($request->get('type') == 'owner') {
                $this->user->auto_id = sprintf("WSTP-%06d", $newId);
            } else {
                $this->user->auto_id = sprintf("WST-%06d", $newId);
            }
            $this->user->name = $request->get('name');
            $this->user->surname = $request->get('surname');
            $this->user->email = $request->get('email');
            $this->user->email_cc = $request->get('email_cc');
            $this->user->phone = $request->get('phone');
            $this->user->gender = $request->get('gender');
            $this->user->age_range = $request->get('age_range');
            $this->user->education = $request->get('education');
            $this->user->salary = $request->get('salary');
            $this->user->no = $request->get('no');
            $this->user->soi = $request->get('soi');
            $this->user->mu = $request->get('mu');
            $this->user->village = $request->get('village');
            $this->user->street = $request->get('street');
            $this->user->district = $request->get('district');
            $this->user->city = $request->get('city');
            $this->user->province = $request->get('province');
            $this->user->postcode = $request->get('postcode');
            $this->user->promtpay = $request->get('promtpay');
            $this->user->office_name = $request->get('office_name');
            $this->user->payment_type = $request->get('payment_type');
            $this->user->bank_name = $request->get('bank_name');
            $this->user->account_name = $request->get('account_name');
            $this->user->bank_account_number = $request->get('bank_account_number');
            $this->user->id_number = $request->get('id_number');
            $this->user->national_id = $request->get('national_id');
            $this->user->phone_no = $request->get('phone_no');
            $this->user->password = \Illuminate\Support\Facades\Hash::make($request->get('password'));

            $this->user->save();

            $this->user->assignRole($request->get('type'));
			
			$verification_token = rand(100000,999999);
			
			//$code = base64_encode($verification_token.'&&&'.$this->user->email);

            $data = array(
                //'code' => rand(100000,999999)
                'code' => $verification_token
            );
            Mail::send('mail', $data, function($message) {
                $message->to($this->user->email, $this->user->name)->subject('Email Verification');
            });

            $this->user->verification_token = $verification_token;
            $this->user->save();

            return $this->respond([
                    'status' => true,
                    'data' => $this->user
                ]);

        } catch (\Exception $e) {
            return $this->respond([
                'status' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register_with_otp(Request $request)
    {
		
		 
        try {
			
			$this->user->email = $request->get('email');
			$otp = $request->get('otp');
			
			if (!filter_var($this->user->email, FILTER_VALIDATE_EMAIL)) {
			  
				return $this->respond([
							'status' => false,
							'message' => 'Invalid email format.'
							//'data' => $user
						]);
			}
			
			$email = $request->get('email');
			list($username, $domain) = explode('@', $email);

			if (!checkdnsrr($domain, 'MX')) {
			  
				return $this->respond([
							'status' => false,
							'message' => 'Mailserver error.'
							//'data' => $user
						]);
			} 
			
			if(!empty($otp)){
				
				$check = DB::select('select * from otp_verification where  email = "'.$this->user->email.'" AND otp = "'.$otp.'" ');
				
				if(count(array_filter($check)) < 1){
				
					return $this->respond([
							'status' => false,
							'message' => 'OTP verification failed.'
							//'data' => $user
						]);
					
				}
				
				$values = array(
					'otp' => ''
				);
				
				DB::table('otp_verification')->where('email', $this->user->email)->update($values);
				
				
				
			} else {
				
				$verification_token = rand(100000,999999);
				
				$check = DB::select('select * from otp_verification where  email = "'.$this->user->email.'"  ');
				
				if(count(array_filter($check)) < 1){
				
					$values = array(
						'email' => $this->user->email,
						'otp' => $verification_token
					);
					
					DB::table('otp_verification')->insert($values);
					
				} else {
					
					$values = array(
						'otp' => $verification_token
					);
					
					DB::table('otp_verification')->where('email', $this->user->email)->update($values);
				}
				
				//$code = base64_encode($verification_token.'&&&'.$this->user->email);

				$data = array(
					'code' => $verification_token
				);
				
				$status = Mail::send('rgistermail', $data, function($message) {
				//$status = Mail::send('mail', $data, function($message) {
					$message->to($this->user->email, 'Singh')->subject('One Time Password');
				});
				 
				if(count(Mail::failures()) > 0){
					
					return $this->respond([
							'status' => false,
							'message' => 'Technical error while sending mail.'
							//'data' => $user
						]);
					
				} else {
					
					return $this->respond([
							'status' => true,
							'otp_status' => true,
							'message' => 'OTP Sent To Email.'
							//'data' => $user
						]);
				}
				
			}
			
            $newId = $this->user->orderBy('created_at', 'desc')->first()->id + 1;
            if ($request->get('type') == 'owner') {
                $this->user->auto_id = sprintf("WSTP-%06d", $newId);
            } else {
                $this->user->auto_id = sprintf("WST-%06d", $newId);
            }
            $this->user->name = $request->get('name');
            $this->user->surname = $request->get('surname');
            $this->user->email = $request->get('email');
            $this->user->email_cc = $request->get('email_cc');
            $this->user->phone = $request->get('phone');
            $this->user->gender = $request->get('gender');
            $this->user->age_range = $request->get('age_range');
            $this->user->education = $request->get('education');
            $this->user->salary = $request->get('salary');
            $this->user->no = $request->get('no');
            $this->user->soi = $request->get('soi');
            $this->user->mu = $request->get('mu');
            $this->user->village = $request->get('village');
            $this->user->street = $request->get('street');
            $this->user->district = $request->get('district');
            $this->user->city = $request->get('city');
            $this->user->province = $request->get('province');
            $this->user->postcode = $request->get('postcode');
            $this->user->promtpay = $request->get('promtpay');
            $this->user->office_name = $request->get('office_name');
            $this->user->payment_type = $request->get('payment_type');
            $this->user->bank_name = $request->get('bank_name');
            $this->user->account_name = $request->get('account_name');
            $this->user->bank_account_number = $request->get('bank_account_number');
            $this->user->id_number = $request->get('id_number');
            $this->user->national_id = $request->get('national_id');
            $this->user->phone_no = $request->get('phone_no');
            $this->user->password = \Illuminate\Support\Facades\Hash::make($request->get('password'));

            $this->user->save();

            $this->user->assignRole($request->get('type'));
			
			$verification_token = rand(100000,999999);
			
			//$code = base64_encode($verification_token.'&&&'.$this->user->email);

            $data = array(
                //'code' => rand(100000,999999)
                'code' => $verification_token
            );
            Mail::send('mail', $data, function($message) {
                $message->to($this->user->email, $this->user->name)->subject('Email Verification');
            });

            $this->user->verification_token = $verification_token;
            $this->user->save();

            return $this->respond([
                    'status' => true,
                    'data' => $this->user
                ]);

        } catch (\Exception $e) {
            return $this->respond([
                'status' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = auth()->user();
        $role = $user->roles()->pluck('name');
        $user->role = $role;
		
		//$task_unread_count = 0;
		
		/* $tasks = auth()->user()->tasks;
		
		foreach($tasks as $task){
			
			if($task->is_read == 0){
				
				$task_unread_count = $task_unread_count + 1;
				
			}
		}
		
		$user->task_unread_count = $task_unread_count; */
		//$user->tasks = $tasks;
		
        return $this->respond($user);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyEmail(Request $request)
    {
        $email = $request->get('email'); 
        $code = $request->get('code');

        $user = $this->user->where('email', $email)->get()->first();

        if ($user->verification_token == $code) {

            $user->verified = true;
            $user->token_status = 0;
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'verified'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'verification code is not correct'
            ]);
        }
    }

    /**
     * Handle a forgot password request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function forgotPassword(Request $request)
    {
        try {

            $email = $request->get('email');

            $this->user = $this->user->where('email', $email)->get()->first();
            if (!empty($this->user)) {
                $data = array(
                    'code' => rand(100000,999999)
                );
                Mail::send('mail', $data, function($message) {
                    $message->to($this->user->email, $this->user->name)->subject('Email Verification');
                });

                $this->user->verification_token = $data['code'];
                $this->user->save();

                return $this->respond([
                        'status' => true,
                        'data' => $this->user
                    ]);
            } else {
                return $this->respond([
                        'status' => false,
                        'message' => "The user don't exist"
                    ]);
            }
        } catch (\Exception $e) {
            return $this->respond([
                'status' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle a reset password request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(Request $request)
    {
        try {

            $email = $request->get('email');
            $code = $request->get('code');
            $password = $request->get('password');

            $user = $this->user->where('email', $email)->get()->first();

            if ($user->verification_token == $code) {

                $user->password = \Illuminate\Support\Facades\Hash::make($password);
                $user->save();

                return response()->json([
                    'status' => true,
                    'message' => 'password changed'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'invalid verification code'
                ]);
            }
        } catch (\Exception $e) {
            return $this->respond([
                'status' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle a reset password request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {
        try {

            $email = $request->get('email');
            $password = $request->get('password');
            $old_password = $request->get('old_password');
			
			$credentials = array('email'=>$email, 'password'=>$old_password);

			if (! $token = auth()->attempt($credentials)) {
				return response()->json([
                    'status' => false,
                    'message' => 'Password not match'
                ]);
			}
 
            $user = $this->user->where('email', $email)->get()->first();

			$user->password = \Illuminate\Support\Facades\Hash::make($password);
			$user->save();

			return response()->json([
				'status' => true,
				'message' => 'password changed'
			]);
            
        } catch (\Exception $e) {
            return $this->respond([
                'status' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle a resend token request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resendToken(Request $request)
    {
        try {

            $email = $request->get('email');

            $this->user = $this->user->where('email', $email)->get()->first();

            if (!empty($this->user)) {

                $token = rand(100000,999999);
                $this->user->verification_token = $token;

                $data = array(
                    'code' => $token
                );
                Mail::send('mail', $data, function($message) {
                    $message->to($this->user->email, $this->user->name)->subject('Email Verification');
                });

                $this->user->save();

                return response()->json([
                    'status' => true,
                    'message' => 'sent token'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'User dont exist'
                ]);
            }
        } catch (\Exception $e) {
            return $this->respond([
                'status' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
		$user_id = Auth::id();
		
		$user_data = DB::select('select email,token_status,ban_status from task_user INNER JOIN users  WHERE id = "'.$user_id.'"  ');
		
		if(count($user_data) > 0){
			
			$email = $user_data[0]->email;
			$token_status = $user_data[0]->token_status;
			$ban_status = $user_data[0]->ban_status;
			
			if($ban_status == 1){
				
				return response()->json(['error' => 'You are banded'], 401);
				
			} else {
				
				return response()->json([
					'access_token' => $token,
					'token_type' => 'bearer',
					'expires_in' => auth()->factory()->getTTL() * 60,
					'user_id'   => Auth::id(),
					'email'   =>  $email,
					'token_status'   =>  $token_status,
				]);
				
			}
			
		} else {
			
			return response()->json(['error' => 'Unauthorized'], 401);
		}
		
        
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithTokenPartner($token)
    {
		$user_id = Auth::id();
		
		$user_data = DB::select('select email,token_status,ban_status from task_user INNER JOIN users  WHERE id = "'.$user_id.'" AND auto_id LIKE "%WSTP-%" AND auto_id NOT LIKE "%WST-%" AND auto_id NOT LIKE "%MOR-%" ');
		
		if(count($user_data) > 0){
			
			$email = $user_data[0]->email;
			$token_status = $user_data[0]->token_status;
			$ban_status = $user_data[0]->ban_status;
			
			if($ban_status == 1){
				
				return response()->json(['error' => 'You are banded'], 401);
				
			} else {
				
				return response()->json([
					'access_token' => $token,
					'token_type' => 'bearer',
					'expires_in' => auth()->factory()->getTTL() * 60,
					'user_id'   => Auth::id(),
					'email'   =>  $email,
					'token_status'   =>  $token_status,
				]);
				
			}
			
		} else {
			
			return response()->json(['error' => 'Unauthorized'], 401);
		}
		
        
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithTokenWinspector($token)
    {
		$user_id = Auth::id();
		
		$user_data = DB::select('select email,token_status,ban_status from task_user INNER JOIN users  WHERE id = "'.$user_id.'" AND auto_id LIKE "%WST-%" AND auto_id NOT LIKE "%WSTP-%" AND auto_id NOT LIKE "%MOR-%" ');
		
		if(count($user_data) > 0){
			
			$email = $user_data[0]->email;
			$token_status = $user_data[0]->token_status;
			$ban_status = $user_data[0]->ban_status;
			
			if($ban_status == 1){
				
				return response()->json(['error' => 'You are banded'], 401);
				
			} else {
				
				return response()->json([
					'access_token' => $token,
					'token_type' => 'bearer',
					'expires_in' => auth()->factory()->getTTL() * 60,
					'user_id'   => Auth::id(),
					'email'   =>  $email,
					'token_status'   =>  $token_status,
				]);
				
			}
			
		} else {
			
			return response()->json(['error' => 'Unauthorized'], 401);
		}
		
        
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function test_otp(Request $request)
    {
		//echo app()->version();die();
		$this->user->email = $request->get('email');
		$otp = $request->get('otp');
		
		if (!filter_var($this->user->email, FILTER_VALIDATE_EMAIL)) {
		  
			return $this->respond([
						'status' => false,
						'status_code' => 500,
						'message' => 'Invalid email format.'
						//'data' => $user
					]);
		}
		
		$email = $request->get('email');
		list($username, $domain) = explode('@', $email);

		if (!checkdnsrr($domain, 'MX')) {
		  
			return $this->respond([
						'status' => false,
						'status_code' => 500,
						'message' => 'Mailserver error.'
						//'data' => $user
					]);
		} 
		
		if(!empty($otp)){
			
			$check = DB::select('select * from otp_verification where  email = "'.$this->user->email.'" AND otp = "'.$otp.'" ');
			
			if(count(array_filter($check)) < 1){
			
				return $this->respond([
						'status' => false,
						'message' => 'OTP verification failed.'
						//'data' => $user
					]);
				
			}
			
			$values = array(
				'otp' => ''
			);
			
			DB::table('otp_verification')->where('email', $this->user->email)->update($values);
			
			
			
		} else {
			
			$verification_token = rand(100000,999999);
			
			$check = DB::select('select * from otp_verification where  email = "'.$this->user->email.'"  ');
			
			if(count(array_filter($check)) < 1){
			
				$values = array(
					'email' => $this->user->email,
					'otp' => $verification_token
				);
				
				DB::table('otp_verification')->insert($values);
				
			} else {
				
				$values = array(
					'otp' => $verification_token
				);
				
				DB::table('otp_verification')->where('email', $this->user->email)->update($values);
			}
			die('ys');
			//$code = base64_encode($verification_token.'&&&'.$this->user->email);

			$data = array(
				'code' => $verification_token
			);
			
			$status = Mail::send('rgistermail', $data, function($message) {
			//$status = Mail::send('mail', $data, function($message) {
				$message->to($this->user->email, 'Singh')->subject('One Time Password');
			});
			 
			if(count(Mail::failures()) > 0){
				
				return $this->respond([
						'status' => false,
						'message' => 'Technical error while sending mail.'
						//'data' => $user
					]);
				
			} else {
				
				return $this->respond([
						'status' => true,
						'otp_status' => true,
						'message' => 'OTP Sent To Email.'
						//'data' => $user
					]);
			}
			
		}
		
		
		//echo "<pre>";print_r($status);die();
		
	}
}
