<?php
namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Customer;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
   public function showMyOrder()
    {
        $user = Auth::user();

        // Tìm Customer t.ứng với tk đanglogin
        $customer = \App\Models\Customer::where(
            'UserID',
            $user->UserID
        )->first();

        // Chưa có Customer => chx có đơn hàng
        if (!$customer) {
            $invoices = collect();

            return view('customer.orders.index', compact('invoices'));
        }

        // Lấy các Invoice thuộc Customer
        $invoices = Invoice::where('CustomerID', $customer->CustomerID)
            ->with([
                'appointment',
                'details.service'
            ])
            ->orderBy('InvoiceDate', 'desc')
            ->get();

        return view('customer.orders.index', compact('invoices'));
    }
    
        public function store(Request $request)
{
    $request->validate([
        'fullname' => 'required|string|max:100',
        'phone' => 'required|string|max:20',
        'email' => 'nullable|email|max:100',
        'province' => 'required',
        'ward' => 'required',
        'province_name' => 'required|string|max:100',
    'ward_name' => 'required|string|max:100',
        'address' => 'required|string|max:255',
        'payment_method' => 'required|in:cash,bank',
        'note' => 'nullable|string|max:500',
    ]);

    // cart

    $cart = [];

    if (Auth::check()) {

        // Lấy Cart của user
        $userCart = \App\Models\Cart::where(
            'UserID',
            Auth::user()->UserID
        )->first();

        if ($userCart) {
            $cartDetails = \App\Models\CartDetail::with('service')
                ->where('CartID', $userCart->CartID)
                ->get();

            foreach ($cartDetails as $detail) {

                if (!$detail->service) {
                    continue;
                }

                $cart[$detail->ServiceID] = [
                    'id' => $detail->ServiceID,
                    'name' => $detail->service->ServiceName,
                    'price' => $detail->service->Price,
                    'quantity' => $detail->Quantity,
                    'image' => $detail->service->Image,
                ];
            }
        }

    } else {

        // Guest → lấy cart từ session
        $cart = session()->get('cart', []);
    }


    // EMPTY CART
   

    if (empty($cart)) {

        return redirect()
            ->route('cart.index')
            ->with('error', 'Your cart is empty.');
    }


    DB::beginTransaction();

    try {

       // CUSTOMER

        $customer = null;

if (Auth::check()) {

    // User đã đăng nhập
    $customer = Customer::where(
        'UserID',
        Auth::id()
    )->first();

    // Nếu tài khoản chưa có Customer
    if (!$customer) {

        $customer = Customer::create([
            'UserID' => Auth::id(),
            'FullName' => $request->fullname,
            'Phone' => $request->phone,
            'Email' => $request->email,
            'DOB' => null,
            'Allergies' => null,
            'Notes' => null,
            'LoyaltyPoints' => 0,
            'MembershipTier' => 'Normal',
        ]);

    } else {

        // Cập nhật thông tin mới nhất
        $customer->FullName = $request->fullname;
        $customer->Phone = $request->phone;
        $customer->Email = $request->email;

        $customer->save();
    }

} else {
    // GUEST

    // Tìm Customer bằng số điện thoại
    $customer = Customer::where(
        'Phone',
        $request->phone
    )->first();

    // Chưa có thì tạo mới
    if (!$customer) {

        $customer = Customer::create([
            'UserID' => null,
            'FullName' => $request->fullname,
            'Phone' => $request->phone,
            'Email' => $request->email,
            'DOB' => null,
            'Allergies' => null,
            'Notes' => null,
            'LoyaltyPoints' => 0,
            'MembershipTier' => 'Normal',
        ]);
}
}


        //SHIPPING ADDRESS
    
       $shippingAddress =
    $request->address
    . ', ' . $request->ward_name
    . ', ' . $request->province_name;


        // CALCULATE TOTAL

        $totalAmount = 0;

        foreach ($cart as $item) {

            $totalAmount +=
                $item['price'] * $item['quantity'];
        }


        // CREATE INVOICE
        
        $invoice = Invoice::create([
            'CustomerID' => $customer->CustomerID,

            'AppointmentID' => null,

            'InvoiceDate' => now(),

            'TotalAmount' => $totalAmount,

            'DiscountAmount' => 0,

            'FinalAmount' => $totalAmount,

            'PaymentMethod' => $request->payment_method,

            'ShippingName' => $request->fullname,

            'ShippingPhone' => $request->phone,

            'ShippingAddress' => $shippingAddress,
        ]);


        // CREATE INVOICE DETAILS
        foreach ($cart as $item) {

            InvoiceDetail::create([
                'InvoiceID' => $invoice->InvoiceID,

                'ServiceID' => $item['id'],

                'Quantity' => $item['quantity'],

                'UnitPrice' => $item['price'],
            ]);
        }


        
        // CLEAR CART
        

        if (Auth::check()) {

            // Xóa CartDetail của user
            $userCart = \App\Models\Cart::where(
                'UserID',
                Auth::user()->UserID
            )->first();

            if ($userCart) {

                \App\Models\CartDetail::where(
                    'CartID',
                    $userCart->CartID
                )->delete();

                $userCart->UpdatedAt = now();
                $userCart->save();
            }

        } else {

            // Guest
            session()->forget('cart');
        }


        DB::commit();

        // SUCCESS

        return redirect()
            ->route(
                'customer.orders.success',
                $invoice->InvoiceID
            );


    } catch (\Exception $e) {

        DB::rollBack();

        dd(
            $e->getMessage(),
            $e->getFile(),
            $e->getLine()
        );
    }}
        
        public function success($invoiceId)
{
    $invoice = Invoice::findOrFail($invoiceId);

    return view(
        'customer.orders.success',
        compact('invoice')
    );
}

public function show($invoiceId)
{
    $invoice = Invoice::with([
        'details.service'
    ])->findOrFail($invoiceId);

    return view(
        'customer.orders.show',
        compact('invoice')
    );
}
}