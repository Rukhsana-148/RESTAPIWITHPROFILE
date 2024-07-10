<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Math;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
class ApiController extends Controller
{
    //

    public function register(Request $request){
        try{
            $validateUser = Validator::make($request->all(),[
                'name'=>'required',
                'email'=>'required|email|unique:users,email',
                'password'=>'required',
            ]
            );

            if($validateUser->fails()){
                return response()->json([
                    'status'=>false,
                    'message'=>'validation error',
                    'errors'=>$validateUser->errors()
                ], 401);
            }

            $user = User::create([
               'name'=>$request->name,
               'email'=>$request->email,
               'password'=>$request->password,
            ]);
            return response()->json([
                'status'=>true,
                'message'=>'User created successfully',
                'data'=>$user->all()
            ], 401);
    }catch(Throwable $th){
        return response()->json([
            'status'=>false,
            'message'=>$th->getMessage(),
           
        ], 500);
    };
}

public function login(Request  $request){
    try{
        $validateUser = Validator::make($request->all(),[
       
            'email'=>'required|email',
            'password'=>'required',
        ]
        );

        if($validateUser->fails()){
            return response()->json([
                'status'=>false,
                'message'=>'validation error',
                'errors'=>$validateUser->errors()
            ], 401);
        }
            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status'=>true,
                'message'=>'User logged in successfully',
                'data'=>$user->all()
            ], 401);
        
        if(!Auth::attempt($request->only(['email', 'password']))){
            return response()->json([
                'status'=>false,
                'message'=>'Email and Password not matched',
                
            ], 401);
        }
    }catch(\Throwable $th){
        return response()->json([
            'status'=>false,
            'message'=>$th->getMessage(),
           
        ], 500);
    };
}
}