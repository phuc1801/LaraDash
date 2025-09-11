<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ArticleStatsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\NotificationController;


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
Route::get('/users/statistics', [UserController::class, 'statistics']);
Route::get('/users/count', [UserController::class, 'count']);
Route::apiResource('users', UserController::class);


// Orders (RESTful API)
// Route::get('/orders', [OrderController::class, 'index']);
// Route::get('/orders/{id}', [OrderController::class, 'show']);
Route::get('/orders/revenue-by-month', [OrderController::class, 'revenueByMonth']);
Route::get('/orders/weekly-growth', [OrderController::class, 'weeklyGrowth']);
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



// CRUD bài viết + tìm kiếm
Route::apiResource('articles', ArticleController::class);

// CRUD ảnh bài viết
Route::get('articles/{article}/images', [ArticleImageController::class, 'index']);
Route::post('articles/{article}/images', [ArticleImageController::class, 'store']);
Route::put('images/{id}', [ArticleImageController::class, 'update']);
Route::delete('images/{id}', [ArticleImageController::class, 'destroy']);
Route::get('/articles-statistics', [ArticleStatsController::class, 'index']);
Route::apiResource('articles', ArticleController::class);


// Coupon
Route::apiResource('coupons', CouponController::class);


// Notification
Route::get('/notifications/latest', [NotificationController::class, 'latest']);

Route::prefix('notifications')->group(function () {
    Route::get('/user/{userId}', [NotificationController::class, 'index']);   // Danh sách theo user
    Route::get('/{id}', [NotificationController::class, 'show']);             // Chi tiết 1 thông báo
    Route::post('/', [NotificationController::class, 'store']);               // Thêm mới
    Route::put('/{id}/read', [NotificationController::class, 'markAsRead']);  // Đánh dấu đã đọc
    Route::delete('/{id}', [NotificationController::class, 'destroy']);       // Xóa
});

