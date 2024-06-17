<?php

namespace App\Http\Controllers\auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /**
    * THIS IS THE FUNCTION USED TO REGISTER USER
    * @method POST
    * @author KEVANGI PATEL
    * @route /logout
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */

    public function register(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        event(new Registered($user));

        Auth::login($user);
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => 'Welcome to the website!!',
            'token'   => $token
        ], 200);
    }
}
