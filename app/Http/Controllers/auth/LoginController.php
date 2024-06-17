<?php

namespace App\Http\Controllers\auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
    * THIS IS THE FUNCTION USED TO LOGIN USER
    * @method POST
    * @author KEVANGI PATEL
    * @route /
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function login(Request $request){
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken($request->input('email'))->plainTextToken;

            return response()->json([
                'message' => 'Login Successfully',
                'token'   => $token
            ], 201);
        }
        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }

    /**
    * THIS IS THE FUNCTION USED TO LOGIN USER
    * @method GET
    * @author KEVANGI PATEL
    * @route /logout
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function logout(){
        if (Auth::check()) {
            $user = Auth::user();
            $user->tokens()->delete();

            return response()->json([
                'message' => 'You have been logged out.',
            ],200);
        }
        return response()->json([
            'message' => 'You are already logged out!!'
        ], 401);
    }
}
