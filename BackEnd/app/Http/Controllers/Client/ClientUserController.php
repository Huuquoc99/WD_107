<?php

namespace App\Http\Controllers\Client;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ClientUserController extends Controller
{
    // Thay đổi thông tin người dùng
    // public function updateUserInfo(Request $request, string $id)
    // {
    //     $validatedData = $request->validate([
    //         "name" => "required|max:255",
    //         "email" => "required|email|max:255|unique:users,email," . $id, 
    //         "phone" => "required|max:255",
    //         "address" => "required|max:255",
    //         // "avatar" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048" 
    //     ]);

    //     if ($request->isMethod("PUT")) {
    //         $param = $validatedData; 
    //         $user = User::findOrFail($id);

    //         if ($request->hasFile("avatar")) {
    //             if ($user->avatar && Storage::disk("public")->exists($user->avatar)) 
    //             {
    //                 Storage::disk("public")->delete($user->avatar);
    //             }
                
    //             $filepath = $request->file("avatar")->store("uploads/users", "public");
    //             $param["avatar"] = $filepath;
    //         } else {
    //             $param["avatar"] = $user->avatar; 
    //         }
            
    //         unset($param["password"]); 

    //         $updated = $user->update($param); 

    //         if ($updated) {
    //             return response()->json(['message' => 'User updated info successfully']);
    //         } else {
    //             return response()->json(['message' => 'Failed to update user info'], 500);
    //         }
    //     }

    //     return response()->json(['message' => 'Invalid request method'], 405);
    // }



    // Thay đổi từng trường thông tin của người dùng
    public function updateUserInfo(Request $request, string $id)
    {
        $validatedData = $request->validate([
            "name" => "nullable|max:255", 
            "email" => "nullable|email|max:255|unique:users,email," . $id, 
            "phone" => "nullable|max:255",
            "address" => "nullable|max:255",
            "avatar" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048" 
        ]);
    
        if ($request->isMethod("PUT")) {
            $user = User::findOrFail($id);
            $param = []; 
    
            if (isset($validatedData['name'])) {
                $param['name'] = $validatedData['name'];
            }
    
            if (isset($validatedData['email'])) {
                $param['email'] = $validatedData['email'];
            }
    
            if (isset($validatedData['phone'])) {
                $param['phone'] = $validatedData['phone'];
            }
    
            if (isset($validatedData['address'])) {
                $param['address'] = $validatedData['address'];
            }
    
            if ($request->hasFile("avatar")) {
                if ($user->avatar && Storage::disk("public")->exists($user->avatar)) {
                    Storage::disk("public")->delete($user->avatar);
                }
    
                $filepath = $request->file("avatar")->store("uploads/users", "public");
                $param["avatar"] = $filepath; 
            } else {
                $param["avatar"] = $user->avatar; 
            }
    
            $updated = $user->update($param); 
    
            if ($updated) {
                return response()->json(['message' => 'User updated info successfully']);
            } else {
                return response()->json(['message' => 'Failed to update user info'], 500);
            }
        }
    
        return response()->json(['message' => 'Invalid request method'], 405);
    }
    
}
