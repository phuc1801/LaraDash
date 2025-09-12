<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    // Danh sách coupon
    public function index()
    {
        $coupons = Coupon::with('product')->paginate(10);
        return response()->json($coupons);
    }

    // Chi tiết 1 coupon
    public function show($id)
    {
        $coupon = Coupon::find($id);
        if (!$coupon) {
            return response()->json(['message' => 'Coupon not found'], 404);
        }
        return response()->json($coupon);
    }

    // Thêm coupon
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'expiry_date' => 'required|date',
            'status'      => 'in:valid,invalid',
            'value'       => 'required|integer|min:1',
            'code'        => 'required|string|max:255|unique:coupons,code',
            'product_id'  => 'required|exists:products,id',
        ]);

        $coupon = Coupon::create($request->all());

        return response()->json($coupon, 201);
    }

    // Sửa coupon
    public function update(Request $request, $id)
    {
        $coupon = Coupon::find($id);
        if (!$coupon) {
            return response()->json(['message' => 'Coupon not found'], 404);
        }

        $request->validate([
            'expiry_date' => 'date',
            'status'      => 'in:valid,invalid',
            'value'       => 'integer|min:1',
            'code'        => 'string|max:255|unique:coupons,code,' . $id,
            'product_id'  => 'exists:products,id',
        ]);

        $coupon->update($request->all());

        return response()->json($coupon);
    }

    // Xoá coupon
    public function destroy($id)
    {
        $coupon = Coupon::find($id);
        if (!$coupon) {
            return response()->json(['message' => 'Coupon not found'], 404);
        }
        $coupon->delete();

        return response()->json(['message' => 'Coupon deleted successfully']);
    }

    public function count()
    {
        $totalCoupons = Coupon::count();
        return response()->json([
            'total_coupons' => $totalCoupons
        ]);
    }
}
