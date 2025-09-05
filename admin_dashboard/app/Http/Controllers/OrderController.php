<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Lấy danh sách orders
    public function index()
    {
        $orders = Order::with(['user', 'shippingAddress'])->get();

        return response()->json([
            'status' => true,
            'orders' => $orders
        ]);
    }

    // Xem chi tiết order
    public function show($id)
    {
        $order = Order::with(['user', 'shippingAddress'])->find($id);

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'order' => $order
        ]);
    }

    public function count()
    {
        $totalOrders = Order::count(); // đếm tất cả đơn hàng

        return response()->json([
            'status' => true,
            'total_orders' => $totalOrders
        ]);
    }

}
