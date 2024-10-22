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

    // Đăng xuất
    public function logout()
    {
        try{
            request()->user()->currentAccessToken()->delete();

            return response()->json([
                "message" => "Logout success"
            ]);

        }catch(\Throwable $th){
            return response()->json([
                "errors" => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Quên mật khẩu
    public function forgotPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
            ]);
    
            // Gửi đường link thay đổi mật khẩu qua email
            $status = Password::sendResetLink(
                $request->only('email')
            );
    
            if ($status === Password::RESET_LINK_SENT) {
                return response()->json(['message' => __($status)]);
            }
    
            throw ValidationException::withMessages([
                'email' => [trans($status)],
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "errors" => $th->getMessage()
            ], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'token' => 'required',
                'password' => 'required|min:8|confirmed',
            ]);
    
            // Đặt lại mật khẩu
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ])->save();
                }
            );
    
            if ($status == Password::PASSWORD_RESET) {
                return response()->json(['message' => __($status)]);
            }
    
            throw ValidationException::withMessages([
                'email' => [trans($status)],
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "errors" => $th->getMessage()
            ], 500);
        };

        
    }

    public function showResetForm(Request $request, $token = null)
    {
        return response()->json(['token' => $token]);
    }
}
