<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Danh sách khách hàng - hỗ trợ tìm theo tên/số điện thoại.
     */
    public function index(Request $request)
    {
        $keyword = $request->query('q');

        $customers = Customer::query()
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('FullName', 'like', "%{$keyword}%")
                    ->orWhere('Phone', 'like', "%{$keyword}%")
                    ->orWhere('Email', 'like', "%{$keyword}%");
            })
            ->orderByDesc('CustomerID')
            ->paginate(10)
            ->withQueryString();

        return view('receptionist.customers.index', compact('customers', 'keyword'));
    }

    /**
     * Form tạo khách hàng mới (đón khách walk-in).
     */
    public function create()
    {
        return view('receptionist.customers.create');
    }

    /**
     * Lưu khách hàng mới.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'FullName' => 'required|max:100',
            'Phone' => 'required|max:20|unique:Customer,Phone',
            'Email' => 'nullable|email|max:100',
            'DOB' => 'nullable|date',
            'Allergies' => 'nullable|max:255',
            'Notes' => 'nullable|max:500',
            'MembershipTier' => 'nullable|max:30',
        ]);

        $data['LoyaltyPoints'] = 0;
        $data['MembershipTier'] = $data['MembershipTier'] ?? 'Normal';

        $customer = Customer::create($data);

        return redirect()
            ->route('receptionist.customers.index')
            ->with('success', 'Đã thêm khách hàng "' . $customer->FullName . '".');
    }

    /**
     * Xem chi tiết khách hàng: lịch sử lịch hẹn & hóa đơn.
     */
    public function show(Customer $customer)
    {
        $customer->load([
            'appointments' => function ($query) {
                $query->orderByDesc('AppointmentDate')->orderByDesc('StartTime');
            },
            'appointments.services.service',
            'invoices' => function ($query) {
                $query->orderByDesc('InvoiceID');
            },
        ]);

        return view('receptionist.customers.show', compact('customer'));
    }

    /**
     * Form chỉnh sửa khách hàng.
     */
    public function edit(Customer $customer)
    {
        return view('receptionist.customers.edit', compact('customer'));
    }

    /**
     * Cập nhật thông tin khách hàng.
     */
    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'FullName' => 'required|max:100',
            'Phone' => 'required|max:20|unique:Customer,Phone,' . $customer->CustomerID . ',CustomerID',
            'Email' => 'nullable|email|max:100',
            'DOB' => 'nullable|date',
            'Allergies' => 'nullable|max:255',
            'Notes' => 'nullable|max:500',
            'MembershipTier' => 'nullable|max:30',
        ]);

        $customer->update($data);

        return redirect()
            ->route('receptionist.customers.index')
            ->with('success', 'Đã cập nhật thông tin khách hàng.');
    }

    /**
     * Xoá khách hàng (chỉ khi không còn dữ liệu liên quan quan trọng).
     */
   public function destroy(Customer $customer)
    {
        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($customer) {
                $appointmentIds = $customer->appointments()->pluck('AppointmentID');

                \App\Models\AppointmentService::whereIn('AppointmentID', $appointmentIds)->delete();

                \App\Models\Invoice::whereIn('AppointmentID', $appointmentIds)
                    ->orWhere('CustomerID', $customer->CustomerID)
                    ->delete();

                $customer->appointments()->delete();

                $customer->delete();
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()
                ->route('receptionist.customers.index')
                ->with('error', 'Không thể xoá khách hàng "' . $customer->FullName . '" vì còn dữ liệu liên quan khác trong hệ thống.');
        }

        return redirect()
            ->route('receptionist.customers.index')
            ->with('success', 'Đã xoá khách hàng cùng toàn bộ lịch hẹn và hoá đơn liên quan.');
    }
}
