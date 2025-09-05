<?php

namespace App\Http\Controllers;

use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
   
    // API đăng ký
    public function register(Request $request)
    {
        // Validate dữ liệu gửi lên
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation errors',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Tạo user mới
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password), // Hash mật khẩu
            'phone'    => $request->phone,
            'address'  => $request->address,
            'status'   => 'active', // mặc định active
            'role_id'  => 1,        // mặc định user thường
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'User registered successfully!',
            'user'    => $user
        ], 201);
    }

    // API Login
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            if($request->expectsJson()){
                return response()->json(['status'=>false,'message'=>'Sai email hoặc mật khẩu!'],401);
            }
            return back()->with('error','Sai email hoặc mật khẩu!');
        }

        if ($user->status !== 'active') {
            if($request->expectsJson()){
                return response()->json(['status'=>false,'message'=>'Tài khoản chưa được kích hoạt!'],403);
            }
            return back()->with('error','Tài khoản chưa được kích hoạt!');
        }

        // Nếu là request web, lưu session
        if(!$request->expectsJson()){
            session(['user' => $user]);
            return redirect('/dashboard');
        }

        return response()->json([
            'status'  => true,
            'message' => 'Login thành công!',
            'user'    => $user
        ], 200);
    }


}
