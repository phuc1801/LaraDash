<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    //  Danh sách sản phẩm
    public function index()
    {
        $products = Product::all();

        return response()->json([
            'status' => true,
            'data' => $products
        ]);
    }

    //  Thêm sản phẩm
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'real_price'  => 'nullable|numeric|min:0',
            'quantity'    => 'required|integer|min:0',
            'category_id' => 'nullable|integer',
            'status'      => 'nullable|string',
            'outline'     => 'nullable|string',
            'unit'        => 'nullable|string|max:50',
            'slug'        => 'nullable|string|max:255|unique:products,slug',
            'guarantee'   => 'nullable|string|max:100',
            'rating_avg'  => 'nullable|numeric|min:0|max:5',
            'rating_count'=> 'nullable|integer|min:0',
            'manufacturer'=> 'nullable|string|max:255',
        ]);

        $product = Product::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Product created successfully',
            'data' => $product
        ]);
    }

    //  Sửa sản phẩm
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'        => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'sometimes|numeric|min:0',
            'real_price'  => 'nullable|numeric|min:0',
            'quantity'    => 'sometimes|integer|min:0',
            'category_id' => 'nullable|integer',
            'status'      => 'nullable|string',
            'outline'     => 'nullable|string',
            'unit'        => 'nullable|string|max:50',
            'slug'        => 'nullable|string|max:255|unique:products,slug,' . $product->id,
            'guarantee'   => 'nullable|string|max:100',
            'rating_avg'  => 'nullable|numeric|min:0|max:5',
            'rating_count'=> 'nullable|integer|min:0',
            'manufacturer'=> 'nullable|string|max:255',
        ]);

        $product->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Product updated successfully',
            'data' => $product
        ]);
    }

    //  Xóa sản phẩm
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully'
        ]);
    }

    //  Thống kê
    public function stats()
    {
        $totalProducts   = Product::count();
        $inStockProducts = Product::where('quantity', '>', 0)->count();
        $outOfStock      = Product::where('quantity', '=', 0)->count();
        $inventoryValue  = Product::sum(DB::raw('price * quantity'));

        return response()->json([
            'status' => true,
            'data' => [
                'total_products'   => $totalProducts,
                'in_stock'         => $inStockProducts,
                'out_of_stock'     => $outOfStock,
                'inventory_value'  => $inventoryValue
            ]
        ]);
    }
}
