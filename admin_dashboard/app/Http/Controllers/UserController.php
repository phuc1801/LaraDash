<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class UserController extends Controller
{
    // Đếm số lượng user
    public function count()
    {
        $total = User::count();
        return response()->json([
            'total_users' => $total
        ]);
    }

    // API: thống kê chi tiết
    public function statistics()
    {
        $totalAdmins = User::where('role_id', 1)->count(); // giả sử role_id = 1 là admin
        $totalUsers  = User::where('role_id', 2)->count(); // giả sử role_id = 2 là user

        $newUsersThisMonth = User::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        return response()->json([
            'total_admins'         => $totalAdmins,
            'total_users'          => $totalUsers,
            'new_users_this_month' => $newUsersThisMonth,
        ]);
    }

    // Danh sách user (phân trang)
    public function index()
    {
        $users = User::paginate(10);
        return response()->json($users);
    }

    // Chi tiết user
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user);
    }

    // Thêm user
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'status'   => $request->status ?? 1,
            'phone'    => $request->phone,
            'avatar'   => $request->avatar,
            'address'  => $request->address,
            'role_id'  => $request->role_id ?? 2,
        ]);

        return response()->json($user, 201);
    }

    // Cập nhật user
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $request->validate([
            'email' => 'email|unique:users,email,' . $id,
        ]);

        $data = $request->all();
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return response()->json($user);
    }

    // Xoá user
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
