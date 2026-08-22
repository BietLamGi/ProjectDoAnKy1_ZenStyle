<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Feedback;

class DashboardController extends Controller{
    public function index()
    {
        $totalUsers = User::count();

        $totalCustomers = Customer::count();

        $activeServices = Service::where('IsActive', 1)->count();

        $totalAppointments = Appointment::count();

        $todayAppointments = Appointment::whereDate(
            'AppointmentDate',
            today()
        )->count();

        $totalRevenue = Invoice::sum('FinalAmount');

        $todayRevenue = Invoice::whereDate(
            'InvoiceDate',
            today()
        )->sum('FinalAmount');

        $totalFeedback = Feedback::count();

        $averageRating = Feedback::avg('Rating');

        $todayList = Appointment::whereDate(
            'AppointmentDate',
            today()
        )
        ->orderBy('StartTime')
        ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalCustomers',
            'activeServices',
            'totalAppointments',
            'todayAppointments',
            'totalRevenue',
            'todayRevenue',
            'totalFeedback',
            'averageRating',
            'todayList'
        ));
    }
}