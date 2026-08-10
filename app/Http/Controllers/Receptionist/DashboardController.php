<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Notification;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();

        $todayAppointments = Appointment::where('appointment_date', $today)->count();

        $pendingAppointments = Appointment::whereIn('status', ['pending', 'confirmed'])->count();

        $checkedInAppointments = Appointment::where('status', 'checked_in')->count();

        $totalCustomers = Appointment::distinct('phone')->count('phone');

        $todayRevenue = Order::whereDate('created_at', $today)
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        $unreadNotifications = Notification::where('is_read', false)->count();

        $upcomingAppointments = Appointment::where('appointment_date', $today)
            ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
            ->orderBy('appointment_time')
            ->limit(8)
            ->get();

        $latestNotifications = Notification::orderByDesc('id')->limit(6)->get();

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