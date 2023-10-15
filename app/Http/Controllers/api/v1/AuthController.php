<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\Otp;
use App\Models\User;
use App\Notifications\AccountActivationCode;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    function register(UserRequest $req, Otp $otp): JsonResponse
    {
        $user = User::create($req->validated());
        $token = $user->createToken('auth-token');
        if ($user) {
            $otp->send($user);
            return response()->json([
                'code' => 101,
                'message' => 'User has been registered, you will receive opt in your email',
                'data' => ['user' => new UserResource($user), 'token' => $token->plainTextToken]
            ]);
        } else {
            return response()->json([
                'code' => 102,
                'message' => 'Registration failed'
            ]);
        }
    }

    function verifyOtp(Request $request) : mixed {
        $request->validate([
            'otp' => 'required|digits:6|numeric'
        ]);
        
        $otp_data = $request->user()->otp;
        
        if(!$otp_data){
            return response()->json([
                'code' => 104,
                'message' => 'Otp has already used'
            ]);
        }

        if(Carbon::parse($otp_data->expiry_date)->isPast()){
            return response()->json([
                'code' => 103,
                'message' => 'Otp has expired'
            ]);
        } else if($otp_data->is_used){
            return response()->json([
                'code' => 104,
                'message' => 'Otp has already used'
            ]);
        } else {
            if ($request->otp == $request->user()->otp->code) {
                $request->user()->update([
                    'email_verified_at' => Carbon::now()->toDateTimeString()
                ]);

                $request->user()->otp->update([
                    'is_used' => 1
                ]);

                return response()->json([
                    'code' => 101,
                    'message' => 'Email has verified successfully'
                ]);
            } else {
                return response()->json([
                    'code' => 102,
                    'message' => "Otp doesn't match"
                ]);
            }
        }
    }

    function resendOtp(Otp $otp) : JsonResponse {
        $otp->send(Auth::user());
        
        return response()->json([
            'code' => 101,
            'message' => 'Otp resend successfully'
        ]);
    }

    function login(Request $req): JsonResponse
    {
        $req->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt(['email' => $req->email, 'password' => $req->password])) {
            $user = Auth::user();
            $token = $user->createToken('auth-token');

            return response()->json([
                'code' => 101,
                'message' => 'User has been logged in',
                'data' => ['user' => new UserResource($user), 'token' => $token->plainTextToken]
            ]);
        } else {
            return response()->json([
                'code' => 102,
                'message' => 'Login failed'
            ], 401);
        }
    }
}
