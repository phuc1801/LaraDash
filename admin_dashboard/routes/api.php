<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// users
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Orders (RESTful API)
// Route::get('/orders', [OrderController::class, 'index']);
// Route::get('/orders/{id}', [OrderController::class, 'show']);
Route::get('/orders/count', [OrderController::class, 'count']);
Route::get('/orders/status-count', [OrderController::class, 'countByStatus']);
Route::get('/orders/revenue', [OrderController::class, 'revenue']);




// OrderItem (RESTful API)
Route::apiResource('order-items', OrderItemController::class);



// product
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);       // lấy danh sách
    Route::post('/', [ProductController::class, 'store']);      // thêm
    Route::put('/{product}', [ProductController::class, 'update']); // sửa
    Route::delete('/{product}', [ProductController::class, 'destroy']); // xóa
    Route::get('/stats', [ProductController::class, 'stats']);  // thống kê
});