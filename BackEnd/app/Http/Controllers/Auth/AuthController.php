<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    // Đăng kí
    public function register()
    {
        try{
            $data = request()->validate([
                "name" => "required",
                "email" => "required|email",
                "password" => "required|min:8|max:20|confirmed",
            ]);

            $user = User::create($data);
            $token = $user->createToken($user->id)->plainTextToken;

            return response()->json([
                "token" => $token
            ]);
        }catch(\Throwable $th){
            if($th instanceof ValidationException){
                return response()->json([
                    "errors" => $th->errors()
                ], Response::HTTP_BAD_REQUEST);
            }

            return response()->json([
                "errors" => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    } 

    // Đăng nhập
    public function login()
    {
        try {
            request()->validate([
                "email" => "required|email",
                "password" => "required",
            ]);
    
            $user = User::where("email", request("email"))->first();
    
            if(!$user || !Hash::check(request("password"), $user->password)){
                throw ValidationException::withMessages([
                    "email" => ["The provided credentials are incorrect"],
                ]);
            }
            $token = $user->createToken($user->id)->plainTextToken;
    
            return response()->json([
                "token" => $token
            ]);
        } catch (\Throwable $th) {
            if($th instanceof ValidationException){
                return response()->json([
                    "errors" => $th->errors()
                ], Response::HTTP_BAD_REQUEST); 
            }

            return response()->json([
                "errors" => $th->getMessage()
            ], Response::HTTP_UNAUTHORIZED);
        }
    }
}
