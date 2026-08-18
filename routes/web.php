<?php

//home 
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AppointmentController as CustomerAppointmentController;
use App\Http\Controllers\AppointmentServiceController as CustomerAppointmentServiceController;
use App\Http\Controllers\ServiceController as CustomerServiceController;

// receptionist
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Receptionist\CustomerController as ReceptionistCustomerController;
use App\Http\Controllers\Receptionist\AppointmentController as ReceptionistAppointmentController;
use App\Http\Controllers\Receptionist\AppointmentServiceController as ReceptionistAppointmentServiceController;
use App\Http\Controllers\Receptionist\InvoiceController as ReceptionistInvoiceController;
use App\Http\Controllers\Receptionist\PromotionController as ReceptionistPromotionController;
use App\Http\Controllers\Receptionist\WorkScheduleController as ReceptionistWorkScheduleController;
use App\Http\Controllers\Receptionist\ServiceController as ReceptionistServiceController;
use App\Http\Controllers\Receptionist\FeedbackController as ReceptionistFeedbackController;
use App\Http\Controllers\Receptionist\NotificationController as ReceptionistNotificationController;
use App\Http\Controllers\Receptionist\DashboardController as ReceptionistDashboardController;

//admin 
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\WorkScheduleController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\CustomerController;

//  ADMIN
Route::prefix('admin')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::resource('users', UserController::class);
    // Chỗ thêm staff
    Route::get('staff', [StaffController::class, 'index'])
        ->name('staff.index');
    Route::get('staff/{id}', [StaffController::class, 'show'])
        ->name('staff.show');

    Route::resource('services', ServiceController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('promotions', PromotionController::class);
    Route::resource('appointments', AppointmentController::class);
    Route::resource('work-schedules', WorkScheduleController::class);
    Route::resource('feedbacks', FeedbackController::class);
    Route::resource('notifications', NotificationController::class);
    Route::resource('customers', CustomerController::class);

});
// Receptionist - khu vực nghiệp vụ lễ tân
Route::prefix('receptionist')->name('receptionist.')->group(function () {
    Route::get('/', [ReceptionistDashboardController::class, 'index'])->name('dashboard');
    // Quản lý khách hàng
    Route::resource('customers', ReceptionistCustomerController::class);
    // Quản lý lịch hẹn (đặt lịch tại quầy, xác nhận, check-in, hoàn tất, huỷ)
    Route::resource('appointments', ReceptionistAppointmentController::class);
    Route::patch('appointments/{appointment}/status', [ReceptionistAppointmentController::class, 'updateStatus'])
        ->name('appointments.status');
    // Dịch vụ trong lịch hẹn (AppointmentService) - thêm/xoá dịch vụ cho 1 lịch hẹn
    Route::post('appointments/{appointment}/services', [ReceptionistAppointmentServiceController::class, 'store'])
        ->name('appointments.services.store');
    Route::delete('appointments/{appointment}/services/{appointmentService}', [ReceptionistAppointmentServiceController::class, 'destroy'])
        ->name('appointments.services.destroy');
    // Hoá đơn thanh toán dịch vụ/sản phẩm
    Route::resource('invoices', ReceptionistInvoiceController::class);
    // Khuyến mãi
    Route::resource('promotions', ReceptionistPromotionController::class);
    // Lịch làm việc nhân viên
    Route::resource('work-schedules', ReceptionistWorkScheduleController::class);
    // Bảng giá dịch vụ / sản phẩm (tra cứu)
    Route::get('services', [ReceptionistServiceController::class, 'index'])->name('services.index');
    // Phản hồi khách hàng
    Route::get('feedbacks', [ReceptionistFeedbackController::class, 'index'])->name('feedbacks.index');
    Route::patch('feedbacks/{feedback}/status', [ReceptionistFeedbackController::class, 'updateStatus'])
        ->name('feedbacks.status');
    // Thông báo nội bộ
    Route::get('notifications', [ReceptionistNotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/create', [ReceptionistNotificationController::class, 'create'])->name('notifications.create');
    Route::post('notifications', [ReceptionistNotificationController::class, 'store'])->name('notifications.store');
    Route::patch('notifications/{notification}/read', [ReceptionistNotificationController::class, 'markRead'])
        ->name('notifications.read');
    Route::patch('notifications/read-all', [ReceptionistNotificationController::class, 'markAllRead'])
        ->name('notifications.read-all');
});


// --------------------- HOME ----------------------------- 
Route::get('/', [HomeController::class, 'index'])->name('home');

// xlý submit form đặt lịch booking
Route::get('/booking', [CustomerAppointmentController::class, 'create'])->name('booking');
Route::post('/booking', [CustomerAppointmentController::class, 'store'])->name('booking.store');

// book xong chuyển qua success page
Route::get('/booking/success/{id}', [CustomerAppointmentController::class, 'success'])->name('booking.success');


Route::get('/services', [CustomerServiceController::class, 'index'])->name('services');
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
        [CustomerAppointmentController::class, 'myAppointments'])
        ->name('appointments.my');

    Route::get('/my-appointments/{appointment}',
    [CustomerAppointmentController::class, 'showMyAppointment'])
    ->name('customer.appointments.show');

    Route::post('/my-appointments/{appointment}/cancel',
    [CustomerAppointmentController::class, 'cancel'])
    ->name('customer.appointments.cancel');

    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
    ->name('profile.password.update');
    
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