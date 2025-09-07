<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;



Route::get('/login', function () {
    return view('auth.login');
});

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', function(){
    Session::forget('user');
    return redirect('/login');
});



// view

Route::get('/dashboard', function () {
    // Kiểm tra session user
    if (!Session::has('user')) {
        return redirect('/login');
    }

    $user = Session::get('user');
    return view('dashboard', compact('user'));
});

Route::get('/analytics', function() {
    return view('analytics'); 
})->name('analytics');


Route::get('/orders', function() {
    return view('orders'); 
})->name('orders');


Route::get('/products', function() {
    return view('products'); 
})->name('products');

