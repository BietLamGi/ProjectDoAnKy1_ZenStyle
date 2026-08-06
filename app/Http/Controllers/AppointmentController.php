<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * Xử lý dữ liệu gửi lên từ form đặt lịch ở trang chủ (#booking).
     */
    public function store(Request $request)
    {
        $services = Service::salonServices();

        $validated = $request->validate([
            'fullname'          => ['required', 'string', 'max:100'],
            'phone'             => ['required', 'regex:/^[0-9+\-\s]{9,15}$/'],
            'service'           => ['required', 'in:' . implode(',', array_keys($services))],
            'appointment_date'  => ['nullable', 'date', 'after_or_equal:today'],
            'appointment_time'  => ['nullable', 'string', 'max:20'],
            'note'              => ['nullable', 'string', 'max:500'],
        ], [
            'fullname.required'            => 'Vui lòng nhập họ và tên.',
            'phone.required'               => 'Vui lòng nhập số điện thoại.',
            'phone.regex'                  => 'Số điện thoại không hợp lệ.',
            'service.required'             => 'Vui lòng chọn dịch vụ bạn muốn đặt.',
            'service.in'                   => 'Dịch vụ được chọn không hợp lệ.',
            'appointment_date.after_or_equal' => 'Ngày hẹn phải từ hôm nay trở đi.',
        ]);

        $validated['status'] = 'pending';

        Appointment::create($validated);

        return redirect(route('home') . '#booking')
            ->with('success', 'Đặt lịch thành công! Chúng tôi sẽ liên hệ xác nhận với bạn trong thời gian sớm nhất.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        //
    }
}
