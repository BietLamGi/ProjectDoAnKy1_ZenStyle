<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

Route::prefix('admin')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('users', UserController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('products', ProductController::class);
    Route::resource('appointments', AppointmentController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('feedbacks', FeedbackController::class);
    Route::resource('notifications', NotificationController::class);

});


Route::get('/home', [HomeController::class, 'index'])->name('home');

// xlý submit form đặt lịch booking
Route::post('/booking', [AppointmentController::class, 'store'])->name('booking.store');

// Truy cập trực tiếp /booking (GET) sẽ đưa thẳng về section đặt lịch trên trang chủ
Route::get('/booking', function () {
    return redirect(route('home') . '#booking');
});

Route::get('/about', function () {
    return redirect(route('home') . '#about');
})->name('about');

Route::get('/services', function () {
    return redirect(route('home') . '#services');
})->name('services');

Route::get('/contact', function () {
    return redirect(route('home') . '#contact');
})->name('contact');

// đky tk
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');