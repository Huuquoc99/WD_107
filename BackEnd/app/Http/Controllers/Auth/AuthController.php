<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\VerificationCodeMail;

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

    public function sendResetLinkEmail(Request $request)
    {
        // Xác thực email
        $request->validate(['email' => 'required|email']);
        
        // Gửi liên kết đặt lại mật khẩu
        $response = Password::sendResetLink($request->only('email'));
        
        if ($response == Password::RESET_LINK_SENT) {
            // Tạo mã xác minh
            $verificationCode = rand(100000, 999999);
            Cache::put('verification_code_' . $request->email, $verificationCode, 300); // Lưu mã trong cache trong 5 phút
            
            // Gửi mã xác minh qua email
            Mail::to($request->email)->send(new VerificationCodeMail($verificationCode));
            return response()->json(['status' => __($response), 'message' => 'Verification code sent to your email.']);
        }

        return response()->json(['error' => __($response)], 422);
    }

    public function verify(Request $request)
    {
        // Xác thực mã xác minh
        $request->validate([
            'code' => 'required|integer',
            'email' => 'required|email',
        ]);

        // Lấy mã xác minh từ cache
        $expectedCode = Cache::get('verification_code_' . $request->email);

        // Kiểm tra mã xác minh
        if (!$expectedCode || $request->code != $expectedCode) {
            return response()->json(['error' => 'Invalid verification code.'], 422);
        }

        return response()->json(['status' => 'Verification successful.']);
    }

    public function resetPassword(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8', // Xác thực mật khẩu và xác nhận
        ]);

        // Tìm người dùng theo email
        $user = DB::table('users')->where('email', $request->email)->first();

        // Kiểm tra xem người dùng có tồn tại không
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        // Cập nhật mật khẩu mới
        DB::table('users')->where('email', $request->email)->update(['password' => Hash::make($request->password)]);

        return response()->json(['status' => 'Password has been reset successfully.']);
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
    // public function forgotPassword(Request $request)
    // {
    //     $request->validate(['email' => 'required|email']);
    
    //     $user = User::where('email', $request->email)->first();
    //     if (!$user) {
    //         return response()->json(['message' => 'Email not found'], 404);
    //     }
    
       
    //     $verificationCode = Str::random(6); // Ví dụ: 6 ký tự
    //     DB::table('password_resets')->updateOrInsert(
    //         ['email' => $request->email],
    //         [
    //             'verification_code' => $verificationCode,
    //             'created_at' => now(),
    //         ]
    //     );
    
    //     // Gửi mã xác minh qua email
    //     Mail::to($request->email)->send(new VerificationCodeMail($verificationCode));
    
    //     return response()->json(['message' => 'Verification code sent to your email']);
    // }

    // Phương thức đặt lại mật khẩu
   

   
    
    

    public function showResetForm(Request $request, $token = null)
    {
        return response()->json(['token' => $token]);
    }
}
