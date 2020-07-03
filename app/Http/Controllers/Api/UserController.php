<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use Auth;


class UserController extends Controller
{
    public function login(Request $request){
        $username = $request->email;
        $password = bcrypt($request->password);
        $user = User::where('email',$username)->where('password',$password)->first();
        $token = Hash::make($request->password);
        $user->api_token = $token;
        $user->save();
        return $token;


    }
}
