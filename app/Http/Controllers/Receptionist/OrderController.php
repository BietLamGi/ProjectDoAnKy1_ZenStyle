<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Danh sách hoá đơn - lọc theo trạng thái thanh toán.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');

        $orders = Order::with(['customer', 'appointment'])
            ->when($status, fn ($query) => $query->where('payment_status', $status))
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('receptionist.orders.index', compact('orders', 'status'));
    }

    /**
     * Form lập hoá đơn mới - có thể khởi tạo từ một lịch hẹn có sẵn.
     */
    public function create(Request $request)
    {
        $appointment = null;

        if ($request->filled('appointment_id')) {
            $appointment = Appointment::with(['customer', 'services.service'])
                ->find($request->appointment_id);
        }

        $customers = Customer::orderBy('FullName')->get();

        $services = Service::where('IsActive', 1)
            ->orderBy('ServiceType')
            ->orderBy('Category')
            ->get();

        return view('receptionist.orders.create', compact('appointment', 'customers', 'services'));
    }

    /**
     * Lưu hoá đơn cùng các dòng dịch vụ/sản phẩm.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:Customer,CustomerID',
            'appointment_id' => 'nullable|exists:Appointment,AppointmentID',
            'payment_method' => 'required|in:cash,card,transfer',
            'payment_status' => 'required|in:paid,unpaid',
            'note' => 'nullable|max:500',
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'required|exists:Service,ServiceID',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $order = DB::transaction(function () use ($request) {
            $total = 0;
            $lines = [];

            foreach ($request->items as $item) {
                $service = Service::findOrFail($item['service_id']);
                $quantity = (int) $item['quantity'];
                $subtotal = $service->Price * $quantity;
                $total += $subtotal;

                $lines[] = [
                    'service_id' => $service->ServiceID,
                    'service_name' => $service->ServiceName,
                    'quantity' => $quantity,
                    'unit_price' => $service->Price,
                    'subtotal' => $subtotal,
                ];
            }

            $order = Order::create([
                'customer_id' => $request->customer_id,
                'appointment_id' => $request->appointment_id,
                'receptionist_id' => Auth::id(),
                'total_amount' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_status,
                'note' => $request->note,
            ]);

            foreach ($lines as $line) {
                $order->details()->create($line);
            }

            if ($request->filled('appointment_id')) {
                Appointment::where('AppointmentID', $request->appointment_id)
                    ->update(['Status' => 'Completed']);
            }

            return $order;
        });

        return redirect()
            ->route('receptionist.orders.show', $order->id)
            ->with('success', 'Đã lập hoá đơn #' . $order->id . '.');
    }

    /**
     * Xem chi tiết hoá đơn (dùng để in/xuất cho khách).
     */
    public function show(Order $order)
    {
        $order->load(['customer', 'appointment', 'details.service', 'receptionist']);

        return view('receptionist.orders.show', compact('order'));
    }

    /**
     * Đánh dấu hoá đơn đã thanh toán.
     */
    public function markPaid(Order $order)
    {
        $order->update(['payment_status' => 'paid']);

        return back()->with('success', 'Đã xác nhận thanh toán hoá đơn #' . $order->id . '.');
    }
}
