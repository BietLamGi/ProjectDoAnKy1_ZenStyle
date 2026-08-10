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
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;

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
Route::get('/booking', [AppointmentController::class, 'create'])->name('booking');
Route::post('/booking', [AppointmentController::class, 'store'])->name('booking.store');

// book xong chuyển qua success page
Route::get('/booking/success/{id}', [AppointmentController::class, 'success'])->name('booking.success');


Route::get('/services', [ServiceController::class, 'index'])->name('services');
Route::get('/products', [ProductController::class, 'index'])->name('products');


Route::get('/about', function () {
    return redirect(route('home') . '#about');
})->name('about');

Route::get('/contact', function () {
    return redirect(route('home') . '#contact');
})->name('contact');

// đky tk
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// xem tk of mình
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile');

      Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('/profile/update', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::get('/my-appointments',
        [AppointmentController::class, 'myAppointments'])
        ->name('appointments.my');

    Route::get('/my-appointments/{appointment}',
    [AppointmentController::class, 'showMyAppointment'])
    ->name('customer.appointments.show');

    Route::post('/my-appointments/{appointment}/cancel',
    [AppointmentController::class, 'cancel'])
    ->name('customer.appointments.cancel');
});

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
// add product into cart
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
// edit quantity of product in cart
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
// remove product from cart
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');

Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');