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

Route::get('/dashboard', function() {
    return view('dashboard'); 
})->name('dashboard');

Route::get('/users', function() {
    return view('users'); 
})->name('users');

Route::get('/messages', function() {
    return view('messages'); 
})->name('messages');

Route::get('/calendar', function() {
    return view('calendar'); 
})->name('calendar');

Route::get('/settings', function() {
    return view('admin.settings');
})->name('settings');

Route::get('/security', function() {
    return view('admin.security');
})->name('security');
   

Route::get('/help', function() {
    return view('admin.help'); 
})->name('help');

Route::get('/article', function() {
    return view('article'); 
})->name('article');
