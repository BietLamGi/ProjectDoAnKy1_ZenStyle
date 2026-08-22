<?php

//home 
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Invoicecontroller as CustomerInvoiceController;
use App\Http\Controllers\AppointmentController as CustomerAppointmentController;
use App\Http\Controllers\ServiceController as CustomerServiceController;
use App\Http\Controllers\TrackController;

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
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\WorkScheduleController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Admin\LeaveRequestController;

//staff
use App\Http\Controllers\Staff\WorkScheduleController as StaffWorkScheduleController;
use App\Http\Controllers\Staff\LeaveRequestController as StaffLeaveRequestController;

//  ADMIN
Route::middleware(['auth', 'role:1'])
    ->prefix('admin')
    ->group(function () {

    Route::get('/', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::resource('users', UserController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('promotions', PromotionController::class);
    Route::resource('appointments', AppointmentController::class);
    Route::resource('work-schedules', WorkScheduleController::class);
    Route::resource('feedbacks', FeedbackController::class);
    Route::resource('notifications', NotificationController::class);
    Route::resource('customers', CustomerController::class);
   Route::get('/leave-requests', [
        LeaveRequestController::class,
        'index'
    ])->name('admin.leave-requests.index');


    Route::patch('/leave-requests/{id}/approve', [
        LeaveRequestController::class,
        'approve'
    ])->name('admin.leave-requests.approve');


    Route::patch('/leave-requests/{id}/reject', [
        LeaveRequestController::class,
        'reject'
    ])->name('admin.leave-requests.reject');
});
// Receptionist - khu vực nghiệp vụ lễ tân
Route::middleware(['auth', 'role:2'])
    ->prefix('receptionist')->name('receptionist.')
    ->group(function () {
    Route::get('/', [ReceptionistDashboardController::class, 'index'])->name('dashboard');
    
    Route::post('work-schedules/{workSchedule}/check-in',
    [ReceptionistWorkScheduleController::class, 'checkIn']
)->name('work-schedules.check-in');

Route::post('work-schedules/{workSchedule}/check-out',
    [ReceptionistWorkScheduleController::class, 'checkOut']
)->name('work-schedules.check-out');

    // Customer management
    Route::resource('customers', ReceptionistCustomerController::class);
    // Appointment management (book at the counter, confirm, check-in, complete, cancel)
    Route::resource('appointments', ReceptionistAppointmentController::class);
    Route::patch('appointments/{appointment}/status', [ReceptionistAppointmentController::class, 'updateStatus'])
        ->name('appointments.status');
    // Live availability check used by the create/edit forms (busy staff + customer double-booking)
    Route::get('appointments-availability', [ReceptionistAppointmentController::class, 'checkAvailability'])
        ->name('appointments.availability');
    // Free time slots per staff for a given service/date, used by the "find a slot" panel on create/edit forms.
    Route::get('appointments-slots', [ReceptionistAppointmentController::class, 'availableSlots'])
        ->name('appointments.slots');
    // Services within an appointment (AppointmentService) - add/remove services for one appointment
    Route::post('appointments/{appointment}/services', [ReceptionistAppointmentServiceController::class, 'store'])
        ->name('appointments.services.store');
    Route::delete('appointments/{appointment}/services/{appointmentService}', [ReceptionistAppointmentServiceController::class, 'destroy'])
        ->name('appointments.services.destroy');
    // Invoices for services/products
    Route::resource('invoices', ReceptionistInvoiceController::class);
    // Record a missing payment method on an already-created invoice (see InvoiceController::index()).
    Route::patch('invoices/{invoice}/confirm-payment', [ReceptionistInvoiceController::class, 'confirmPayment'])
        ->name('invoices.confirm-payment');
    // Promotions - lookup only (view campaigns to apply on an invoice)
    Route::get('promotions', [ReceptionistPromotionController::class, 'index'])->name('promotions.index');
    Route::get('promotions/{promotion}', [ReceptionistPromotionController::class, 'show'])->name('promotions.show');
    // Staff work schedule - view everyone's shifts (read-only). Recording
    // attendance (check-in/check-out) is an Admin/HR action - done from the
    // Admin work-schedules screen, not here.
    Route::get('work-schedules', [ReceptionistWorkScheduleController::class, 'index'])->name('work-schedules.index');
    // Service / product price list (lookup)
    Route::get('services', [ReceptionistServiceController::class, 'index'])->name('services.index');
    Route::get('feedbacks', [ReceptionistFeedbackController::class, 'index'])->name('feedbacks.index');
    // Internal notifications - receive/read only, no sending
    Route::get('notifications', [ReceptionistNotificationController::class, 'index'])->name('notifications.index');
    Route::patch('notifications/{notification}/read', [ReceptionistNotificationController::class, 'markRead'])
        ->name('notifications.read');
    Route::patch('notifications/read-all', [ReceptionistNotificationController::class, 'markAllRead'])
        ->name('notifications.read-all');
});


// --------------------- HOME ----------------------------- 
Route::get('/', [HomeController::class, 'index'])
    ->middleware('customer.frontend')
    ->name('home');

// xlý submit form đặt lịch booking
Route::get('/booking', [CustomerAppointmentController::class, 'create'])
    ->middleware('customer.frontend')
    ->name('booking');
Route::post('/booking', [CustomerAppointmentController::class, 'store'])
    ->middleware('customer.frontend')
    ->name('booking.store');

// book xong chuyển qua success page
Route::get('/booking/success/{id}', [CustomerAppointmentController::class, 'success'])
    ->middleware('customer.frontend')
    ->name('booking.success');

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

// xem all info tk of mình
Route::middleware(['auth', 'role:4'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile');

      Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('/profile/update', [ProfileController::class, 'update'])
        ->name('profile.update');

        // lịch cá x
    Route::get('/my-appointments',
        [CustomerAppointmentController::class, 'myAppointments'])
        ->name('appointments.my');

    Route::get('/my-appointments/{appointment}',
    [CustomerAppointmentController::class, 'showMyAppointment'])
    ->name('customer.appointments.show');

    Route::post('/my-appointments/{appointment}/cancel',
    [CustomerAppointmentController::class, 'cancel'])
    ->name('customer.appointments.cancel');
    
    // xem Order
     Route::get('/my-orders',
    [CustomerInvoiceController::class, 'showMyOrder'])
    ->name('customer.orders.index');
    

    // chi tiết invoice
    Route::get('/my-orders/{invoice}', [CustomerInvoiceController::class, 'show'])
    ->name('customer.orders.show');
    
});
   
// guest order bt
 // tạo đơn
    Route::post('/my-orders/store',
    [CustomerInvoiceController::class, 'store']
    )->name('customer.orders.store');
    
    // đh ok qua dday
    Route::get('/my-orders/success/{id}',
    [CustomerInvoiceController::class, 'success']
    )->name('customer.orders.success');

    
Route::get('/cart', [CartController::class, 'index'])
  ->middleware('customer.frontend')
->name('cart.index');
// add product into cart
Route::post('/cart/add', [CartController::class, 'add'])
  ->middleware('customer.frontend')
->name('cart.add');
// edit quantity of product in cart
Route::post('/cart/update', [CartController::class, 'update'])
  ->middleware('customer.frontend')
->name('cart.update');
// remove product from cart
Route::post('/cart/remove', [CartController::class, 'remove'])
  ->middleware('customer.frontend')
->name('cart.remove');
  
// buy now
Route::post('/cart/buy-now', [CartController::class, 'buyNow'])
  ->middleware('customer.frontend')
    ->name('cart.buyNow');
    
Route::get('/checkout', [CartController::class, 'checkout'])
  ->middleware('customer.frontend')
->name('checkout');


// tra đơn & lịch
Route::get('/track-order', [TrackController::class, 'index'])
    ->name('track-order.index');

Route::post('/track-order/search', [TrackController::class, 'search'])
    ->name('track-order.search');

Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');


Route::middleware(['auth', 'role:3'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {

        Route::get('/work-schedule',
            [StaffWorkScheduleController::class, 'index']
        )->name('work-schedule.index');


        Route::get('/leave-requests',
            [StaffLeaveRequestController::class, 'index']
        )->name('leave-requests.index');


        Route::get('/leave-requests/create',
            [StaffLeaveRequestController::class, 'create']
        )->name('leave-requests.create');


        Route::post('/leave-requests',
            [StaffLeaveRequestController::class, 'store']
        )->name('leave-requests.store');


        Route::delete('/leave-requests/{id}',
            [StaffLeaveRequestController::class, 'destroy']
        )->name('leave-requests.destroy');

    });