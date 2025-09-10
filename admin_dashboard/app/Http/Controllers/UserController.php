<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function count()
    {
        $total = User::count();
        return response()->json([
            'total_users' => $total
        ]);
    }
}
