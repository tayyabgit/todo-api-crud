<?php

namespace App\Models;

use App\Mail\VerifyEmailOtp;
use App\Notifications\AccountActivationCode;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class Otp extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'code', 'is_used', 'expiry_date'];

    function send($user) : void {
        $otp = random_int(100000, 999999);
        $expiry_time = Config::get('app.otp_expire_time');
        $this->create([
            'user_id' => $user->id,
            'code' => $otp,
            'expiry_date' => Carbon::now()->addMinutes(env('OTP_EXPIRES_TIME'))
        ]);
        // $user->notify(new AccountActivationCode($otp));
        Mail::to($user->email)->send(new VerifyEmailOtp($otp, $expiry_time));
    }
}
