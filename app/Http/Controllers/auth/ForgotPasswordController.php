<?php

namespace App\Http\Controllers\auth;

use App\Models\User;
use Illuminate\Support\Str;
use App\Mail\ForgetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    /**
    * THIS IS THE FUNCTION USED WHEN PASSWORD IS FORGOT
    * @method POST
    * @author KEVANGI PATEL
    * @route /forgot-password
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function forgotPassword(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);
        $user  = User::where('email', $request->email)->first();
        $token = Str::random(64);
        
        // Update or insert the password reset token for the user
        DB::table('password_reset_tokens')->updateOrInsert(
        [
            'email' => $request->email,
        ],
        [
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);

        $resetLink = env('VUE_APP_URL'). '/reset-password/'. $token;

        // Send a password reset email to the user
        Mail::to($user->email)->send(new ForgetPassword($resetLink ,$token, $user->first_name));

        return response()->json([
            'token'   => $token
        ], 200);
    }
}
