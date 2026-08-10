<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    //
    public function index()
    {
        // session()->forget('cart');
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

         $user = Auth::user();

        $customer = Customer::where('UserID', $user->UserID)->first();

        return view('cart.checkout', compact('cart'));
    }


    public function add(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:Service,ServiceID',
        ]);

        $product = Service::where('ServiceID', $request->service_id)
            ->where('ServiceType', 1)
            ->where('IsActive', 1)
            ->firstOrFail();

        $cart = session()->get('cart', []);

        $id = $product->ServiceID;

        if (isset($cart[$id])) {

            $cart[$id]['quantity']++;
            $cart[$id]['image'] = $product->Image;

        } else {

            $cart[$id] = [
                'id' => $product->ServiceID,
                'name' => $product->ServiceName,
                'price' => $product->Price,
                'quantity' => 1,
                'image' => $product->Image,
            ];
        }

        session()->put('cart', $cart);

        return back()->with(
            'success',
            'Product added to cart successfully.'
        );
    }

    public function update(Request $request) {
        $cart = session()->get('cart', []);

        $id = $request->id;
        $quantity = (int) $request->quantity;

        if ( !isset($cart[$id])) {
            return back();
        }

        if ($quantity <= 0) {
            unset($cart[$id]);
        } else {
            $cart[$id]['quantity'] = $quantity;
        }

        session()->put('cart', $cart);
        return back();
    }

    public function remove(Request $request)
    {
        $id = $request->id;

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
        }

        session()->put('cart', $cart);

        return back();
    }
}
