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
    // public function login()
    // {
    //     try {
    //         request()->validate([
    //             "email" => "required|email",
    //             "password" => "required",
    //         ]);
    
    //         $user = User::where("email", request("email"))->first();
    
    //         if(!$user || !Hash::check(request("password"), $user->password)){
    //             throw ValidationException::withMessages([
    //                 "email" => ["The provided credentials are incorrect"],
    //             ]);
    //         }
    //         $token = $user->createToken($user->id)->plainTextToken;
    
    //         // return response()->json([
    //         //     "token" => $token
    //         // ]);

    //         // Phân quyền người dùng
    //         if ($user->type == 1) {
    //             // Nếu là admin
    //             return response()->json([
    //                 "token" => $token,
    //                 "role" => "admin",
    //                 "redirect" => "/admin/dashboard"
    //             ], Response::HTTP_OK);
    //         } else {
    //             // Nếu là user thông thường
    //             return response()->json([
    //                 "token" => $token,
    //                 "role" => "client",
    //                 "redirect" => "/client/home" 
    //             ], Response::HTTP_OK);
    //         }
    //         //

    //     } catch (\Throwable $th) {
    //         if($th instanceof ValidationException){
    //             return response()->json([
    //                 "errors" => $th->errors()
    //             ], Response::HTTP_BAD_REQUEST); 
    //         }

    //         return response()->json([
    //             "errors" => $th->getMessage()
    //         ], Response::HTTP_UNAUTHORIZED);
    //     }
    // }

    public function login()
    {
        try {
            request()->validate([
                "email" => "required|email",
                "password" => "required",
            ]);
    
            $user = User::where("email", request("email"))->first();
    
            if ($user && Hash::check(request("password"), $user->password)) {
                $token = $user->createToken('auth_token')->plainTextToken;
    
                $response = [
                    'token' => $token,
                    'user' => [
                        'id' => $user->id,
                        'email' => $user->email,
                        'type' => $user->type,
                    ],
    
                    'redirect' => $user->type == '1' ? 'admin/dashboard' : 'home',
                ];
    
                
    
                return response()->json($response);
            }
    
            return response()->json(['message' => 'Unauthorized'], 401);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Server error'], 500);
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

    public function verifyCode(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'code' => 'required|string|min:1',
    ]);

    $resetRecord = DB::table('password_resets')
        ->where('email', $request->email)
        ->where('verification_code', $request->code)
        ->first();

    if (!$resetRecord) {
        return response()->json(['message' => 'Invalid verification code'], 400);
    }

    return response()->json(['message' => 'Code verified successfully']);
}


    // Quên mật khẩu
    // public function forgotPassword(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'email' => 'required|email',
    //         ]);
    
    //         // Gửi đường link thay đổi mật khẩu qua email
    //         $status = Password::sendResetLink(
    //             $request->only('email')
    //         );
    
    //         if ($status === Password::RESET_LINK_SENT) {
    //             return response()->json(['message' => __($status)]);
    //         }
    
    //         throw ValidationException::withMessages([
    //             'email' => [trans($status)],
    //         ]);
    //     } catch (\Throwable $th) {
    //         return response()->json([
    //             "errors" => $th->getMessage()
    //         ], 500);
    //     }
    // }

    // public function resetPassword(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'email' => 'required|email',
    //             'token' => 'required',
    //             'password' => 'required|min:8|confirmed',
    //         ]);
    
    //         // Đặt lại mật khẩu
    //         $status = Password::reset(
    //             $request->only('email', 'password', 'password_confirmation', 'token'),
    //             function ($user, $password) {
    //                 $user->forceFill([
    //                     'password' => Hash::make($password)
    //                 ])->save();
    //             }
    //         );
    
    //         if ($status == Password::PASSWORD_RESET) {
    //             return response()->json(['message' => __($status)]);
    //         }
    
    //         throw ValidationException::withMessages([
    //             'email' => [trans($status)],
    //         ]);
    //     } catch (\Throwable $th) {
    //         return response()->json([
    //             "errors" => $th->getMessage()
    //         ], 500);
    //     };

        
    // }

    // Phương thức gửi link đặt lại mật khẩu
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);
    
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'Email not found'], 404);
        }
    
       
        $verificationCode = Str::random(6); // Ví dụ: 6 ký tự
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'verification_code' => $verificationCode,
                'created_at' => now(),
            ]
        );
    
        // Gửi mã xác minh qua email
        Mail::to($request->email)->send(new VerificationCodeMail($verificationCode));
    
        return response()->json(['message' => 'Verification code sent to your email']);
    }

    // Phương thức đặt lại mật khẩu
   

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8|max:20|confirmed',
            'code' => 'required|string|min:1',
        ]);
    
        $resetRecord = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('verification_code', $request->code)
            ->first();
    
        if (!$resetRecord) {
            return response()->json(['message' => 'Invalid verification code'], 400);
        }
    
        // Đặt lại mật khẩu
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->password = Hash::make($request->password); // Mã hóa mật khẩu
            $user->save();
    
            // Xóa bản ghi mã xác minh sau khi đặt lại mật khẩu thành công
            DB::table('password_resets')->where('email', $request->email)->delete();
    
            return response()->json(['message' => 'Password reset successfully']);
        }
    
        return response()->json(['message' => 'User not found'], 404);
    }
    
    

    public function showResetForm(Request $request, $token = null)
    {
        return response()->json(['token' => $token]);
    }
}
