<?php

namespace App\Models;

use App\Notifications\AccountActivationCode;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'code', 'is_used', 'expiry_date'];

    function send($user) : void {
        $otp = random_int(100000, 999999);
        $this->create([
            'user_id' => $user->id,
            'code' => $otp,
            'expiry_date' => Carbon::now()->addMinutes(env('OTP_EXPIRES_TIME'))
        ]);
        $user->notify(new AccountActivationCode($otp));
    }
}
