<?php




use Illuminate\Support\Facades\Route;
use
 App\Http\Controllers\Receptionist\CustomerController as ReceptionistCustomerController;
use App\Http\Controllers\Receptionist\AppointmentController as ReceptionistAppointmentController;
use App\Http\Controllers\Receptionist\AppointmentServiceController as ReceptionistAppointmentServiceController;
use App\Http\Controllers\Receptionist\InvoiceController as ReceptionistInvoiceController;
use App\Http\Controllers\Receptionist\PromotionController as ReceptionistPromotionController;
use App\Http\Controllers\Receptionist\WorkScheduleController as ReceptionistWorkScheduleController;
use App\Http\Controllers\Receptionist\ServiceController as ReceptionistServiceController;
use App\Http\Controllers\Receptionist\FeedbackController as ReceptionistFeedbackController;
use App\Http\Controllers\Receptionist\NotificationController as ReceptionistNotificationController;
use App\Http\Controllers\Receptionist\DashboardController as ReceptionistDashboardController;

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
