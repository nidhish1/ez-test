<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ApiAuthController extends Controller
{
    public function register(Request $request) {
        $validatedData = $request->validate([
            'name'=>'required|max:55',
            'email'=>'email|required|unique:users',
            'password'=>'required|confirmed'
        ]);

        $validatedData['password'] = bcrypt( $validatedData['password']);

       $user= User::create($validatedData);
       $accessToken = $user->createToken('authToken')->accessToken;
       return response(['user' => $user , 'access_token' =>$accessToken]);
    }

    public function login(Request $request) {
        $validatedData = $request->validate([
            'email'=>'email|required ',
            'password'=>'required'
        ]);

        if (!auth()->attempt($validatedData)){
            return response (['message'=> 'Invalid Credentials']);
        }


        $accessToken = auth()->user()->createToken('authToken')->accessToken;
        return response(['user' => auth()->user() , 'access_token' =>$accessToken]);
    }
}
