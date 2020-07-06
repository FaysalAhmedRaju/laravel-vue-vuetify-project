<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;

use Illuminate\Support\Str;
use App\Post;
use App\User;
use Log;
use Response;



class UserController extends Controller
{
//    public function __construct(Post $post)
//    {
//        $this->post = $post;
//    }
    public  function login(Request $request){
        // dd($request);
      //  \Log::info("final testing");
        // \Log::info($request);

//        $user = Auth::user();

        $credentials = $request->only('email', 'password');
       // \Log::info($credentials);
        //echo json_encode($request->all()); exit();
        if(Auth::attempt($credentials)){
            $token = Str::random(80);
            Auth::user()->api_token = $token;
            Auth::user()->save();
            return response()->json(['token'=>$token],200);
        }
        return response()->json(['status'=>'Email or Password is Wrong'],403);

//        $post = new Post();
//        $post->name = $request->name;
//        $post->email = $request->email;
//        if($post->save()){
//            return response()->json(['status'=>'Info Record Created'],200);
//        }else{
//            return response()->json(['status'=>'Record Not Created'],403);
//        }

//        return response()->json([
//            "message" => "student record created"
//        ], 201);

//        if ($data->save()) {
//            return Response::json(array('success' => true), 200);
//        }

//        return Response::json(['error' => 'Truck Info Not Found'], 203);


    }

    public function verify(Request $request){
        return $request->user()->only('name','email');
    }

}
