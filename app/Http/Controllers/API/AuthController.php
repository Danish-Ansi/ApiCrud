<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function signup(Request $request){
        $validateUser = Validator::make([
            'name'=>'required',
            'email'=>'required|email|unique:users,email',
            'password' => 'require',
        ]);

        if($validateUser->fails()){
            return response()->json([
                'status' => false,
                'message' => 'validation Error',
                'errors' => $validateUser->errors()->all()
            ],401);
        }
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>$request->password,
        ]);

        
        return response()->json([
            'status' => true,
            'message' => 'User Create Successfully',
            'user' => $user
        ],200);
    }
    public function login(Request $request){
        return response()->json([
            'status' => false,
            'message' => 'Authentication Error',
            'errors' => $validateUser->errors()->all()
        ],401);
        
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $authUser = Auth::user();
            return response()->json([
                'status' => true,
                'message' => 'User Loging Successfully',
                'token' => $authUser->createToken("API Token")->plainTextToken,
                'token_type' => 'bearer'
            ],200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Email & Password does not matched'
            ],404);

        }
    }
    public function logout(Request $request){
        $user = $request->user();
        $user->token()->delete();
        
        return response()->json([
            'status' => true,
            'message' => 'User Logout Successfully',
            'user' => $user
        ],200);
    }
}
