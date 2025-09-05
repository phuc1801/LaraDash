<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderItem;

class OrderItemController extends Controller
{
    // Lấy danh sách tất cả order items
    public function index()
    {
        $items = OrderItem::with('order.user', 'product')->get();
        return response()->json([
            'status' => true,
            'data' => $items
        ]);
    }

    // Tạo order item mới
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
        ]);

        $item = OrderItem::create($request->all());

        return response()->json([
            'status' => true,
            'data' => $item
        ], 201);
    }

    // Xem chi tiết order item
    public function show($id)
    {
        $item = OrderItem::with('order', 'product')->find($id);

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Order item not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $item
        ]);
    }

    // Cập nhật order item
    public function update(Request $request, $id)
    {
        $item = OrderItem::find($id);

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Order item not found'
            ], 404);
        }

        $item->update($request->all());

        return response()->json([
            'status' => true,
            'data' => $item
        ]);
    }

    // Xóa order item
    public function destroy($id)
    {
        $item = OrderItem::find($id);

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Order item not found'
            ], 404);
        }

        $item->delete();

        return response()->json([
            'status' => true,
            'message' => 'Order item deleted successfully'
        ]);
    }
}
