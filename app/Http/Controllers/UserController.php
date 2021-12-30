<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    //

    public function register(Request $request){
        $attributes = $request->validate([
            'name'=>['required','string'],
            'email'=>['required','string',Rule::unique('users','email')],
            'password'=>['required','string','confirmed']
        ]);


        $user = User::create($attributes);
        $token=$user->createToken('myapptoken')->plainTextToken;

        return new Response([
            'user'=>$user,
            'token'=>$token
        ],201);
    }
    public function login(Request $request){
        $attributes = $request->validate([
            'email'=>['required','string'],
            'password'=>['required','string']
        ]);

        $user = User::where('email',$attributes['email'])->first();

        if (!$user || !Hash::check($attributes['password'],$user->password)){
            return \response([
                'message'=>'wrong credentials'
            ],401);
        }

        $token=$user->createToken('myapptoken')->plainTextToken;

        return new Response([
            'user'=>$user,
            'token'=>$token
        ],201);
    }
    public function logout(){
        auth()->user()->tokens()->delete();

        return [
            'message'=>'logged out'
        ];
    }
}
