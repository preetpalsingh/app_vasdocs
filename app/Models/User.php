<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'mobile_number',
        'address',
        'role_id',
        'status',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function sendLoginDetailMail()
    {
        //$password = $this->generateRandomCode();

        // Update user's password
        //$this->password = Hash::make($password);
        //$this->save();

        // Generate a reset token (you can use your own logic for this)
        $token = bin2hex(random_bytes(32));

        // Generate the reset URL
        $resetUrl = URL::route('password.reset', [
            'token' => $token,
            'email' => $this->email,
        ]);
 

        
        DB::table('password_resets')->updateOrInsert(
            ['email' => $this->email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );

        // Send custom password reset email
        $emails = [$this->email]; // Put multiple emails comma-separated if needed
        $subject = 'Invitation - VasDocs.';
        $content = '
        Vasdocs have sent you an  invite to register for their invoice sharing app. Please register using your email as username and set your own password.

Email: '.$this->email.'
Reset Password Link: '.$resetUrl.'

-- 
This e-mail was sent from Vasdocs.';

        Mail::raw($content, function ($message) {
            $message->to($this->email)
                ->subject('Login Detail');
        });
    }

    // get random password

    public function generateRandomCode($length = 6) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomCode = '';
        
        for ($i = 0; $i < $length; $i++) {
            $randomCode .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $randomCode;
    }

    

    public function SendResetPasswordOtpMail()
    {
        
        // Send custom password reset email
        $reset_password_otp = $this->reset_password_otp;
        $content = '
You are receiving this email because we received a password reset request for your account.

Reset Password OTP: '.$reset_password_otp.'

-- 
This e-mail was sent from Vasdocs.';

        Mail::raw($content, function ($message) {
            $message->to($this->email)
                ->subject('Reset Password Notification - VasDocs.');
        });
    }

    public function documents()
    {
        return $this->hasMany(ClientDocuments::class, 'user_id')
        ->where('status', 'Processing');
    }

    

    public function documentsStatus($status)
    {
        return $this->hasMany(ClientDocuments::class, 'user_id')
        ->where('status', $status);
    }


}
