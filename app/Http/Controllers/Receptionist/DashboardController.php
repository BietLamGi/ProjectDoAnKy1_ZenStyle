<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Notification;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();

       $todayAppointments = Appointment::whereDate('AppointmentDate', $today)->count();

        $pendingAppointments = Appointment::whereIn('status', ['pending', 'confirmed'])->count();

        $checkedInAppointments = Appointment::where('status', 'checked_in')->count();

        $totalCustomers = Appointment::distinct('CustomerID')->count('CustomerID');

        $todayRevenue = Invoice::whereDate('InvoiceDate', $today)
    ->sum('FinalAmount');

        $unreadNotifications = Notification::where('IsRead', false)->count();

        $upcomingAppointments = Appointment::whereDate('AppointmentDate', $today)
            ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
            ->orderBy('StartTime')
            ->limit(8)
            ->get();

        $latestNotifications = Notification::orderByDesc('NotificationID')->limit(6)->get();

        return view('receptionist.dashboard', compact(
            'todayAppointments',
            'pendingAppointments',
            'checkedInAppointments',
            'totalCustomers',
            'todayRevenue',
            'unreadNotifications',
            'upcomingAppointments',
            'latestNotifications'
        ));
    }
}