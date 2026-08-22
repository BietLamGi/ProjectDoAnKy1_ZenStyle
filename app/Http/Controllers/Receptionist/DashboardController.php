<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Notification;
use App\Models\Invoice;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();

        $todayAppointments = Appointment::whereDate(
            'AppointmentDate',
            $today
        )->count();

        $pendingAppointments = Appointment::whereIn(
            'Status',
            ['Pending', 'Confirmed']
        )->count();

        $checkedInAppointments = Appointment::where(
            'Status',
            'CheckedIn'
        )->count();

        $totalCustomers = Customer::count();

        $todayRevenue = Invoice::whereDate(
            'InvoiceDate',
            $today
        )->sum('FinalAmount');

        $unreadNotifications = Notification::where(
            'IsRead',
            false
        )->count();

        $unconfirmedInvoices = Invoice::where(function ($q) {
            $q->whereNull('PaymentMethod')
              ->orWhere('PaymentMethod', '');
        })->count();

        $upcomingAppointments = Appointment::with([
            'customer',
            'services.service'
        ])
        ->whereDate('AppointmentDate', $today)
        ->whereIn('Status', [
            'Pending',
            'Confirmed',
            'CheckedIn'
        ])
        ->orderBy('StartTime')
        ->limit(8)
        ->get();

        $latestNotifications = Notification::orderByDesc(
            'NotificationID'
        )
        ->limit(6)
        ->get();

        return view('receptionist.dashboard', compact(
            'todayAppointments',
            'pendingAppointments',
            'checkedInAppointments',
            'totalCustomers',
            'todayRevenue',
            'unreadNotifications',
            'unconfirmedInvoices',
            'upcomingAppointments',
            'latestNotifications'
        ));
    }
}