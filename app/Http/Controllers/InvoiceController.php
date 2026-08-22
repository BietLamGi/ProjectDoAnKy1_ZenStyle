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
    //
    // Quantities come from the session (guest) or CartDetail (logged-in
    // user), but the actual Service/Price/promotion are always re-fetched
    // fresh from the DB below - never trust whatever price was cached at
    // add-to-cart time, since a promotion may have started, ended, or
    // changed since then. This is also what fixes "customer pays the old
    // price": previously this always used the raw, undiscounted
    // Service->Price with DiscountAmount hard-coded to 0.

    $quantities = [];

    if (Auth::check()) {

        $userCart = \App\Models\Cart::where(
            'UserID',
            Auth::user()->UserID
        )->first();

        if ($userCart) {
            $quantities = \App\Models\CartDetail::where('CartID', $userCart->CartID)
                ->pluck('Quantity', 'ServiceID')
                ->all();
        }

    } else {

        // Guest → lấy cart từ session
        foreach (session()->get('cart', []) as $id => $item) {
            $quantities[$id] = (int) $item['quantity'];
        }
    }

    $cart = [];

    if (!empty($quantities)) {

        $services = Service::with('activePromotion')
            ->whereIn('ServiceID', array_keys($quantities))
            ->get()
            ->keyBy('ServiceID');

        foreach ($quantities as $id => $quantity) {

            $service = $services->get($id);

            if (!$service) {
                continue;
            }

            $cart[$id] = [
                'id' => $service->ServiceID,
                'name' => $service->ServiceName,
                'price' => (float) $service->Price,
                'discounted_price' => (float) $service->discounted_price,
                'promotion' => $service->activePromotion,
                'quantity' => $quantity,
                'image' => $service->Image,
            ];
        }
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
        //
        // Mỗi item tự áp promotion của chính nó (nếu có) - TotalAmount là
        // tổng giá gốc, FinalAmount là tổng giá đã giảm (số tiền khách thực
        // sự trả), DiscountAmount là phần chênh lệch. Không còn hard-code
        // DiscountAmount = 0 / charge giá gốc như trước.

        $totalAmount = 0;
        $finalAmount = 0;

        foreach ($cart as $item) {

            $totalAmount += $item['price'] * $item['quantity'];
            $finalAmount += $item['discounted_price'] * $item['quantity'];
        }

        $discountAmount = max(0, $totalAmount - $finalAmount);


        // CREATE INVOICE
        
        $invoice = Invoice::create([
            'CustomerID' => $customer->CustomerID,

            'AppointmentID' => null,

            'InvoiceDate' => now(),

            'TotalAmount' => $totalAmount,

            'DiscountAmount' => $discountAmount,

            'FinalAmount' => $finalAmount,

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