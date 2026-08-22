<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Customer;
use App\Models\Cart;
use App\Models\CartDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    //CART INDEX

    public function index()
    {
        $cart = $this->getCurrentCart();

        return view(
            'customer.cart.index',
            compact('cart')
        );
    }


    //CHECKOUT
    

    public function checkout()
    {
        $cart = $this->getCurrentCart();

        if (empty($cart)) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        $customer = null;

        if (Auth::check()) {

            $customer = Customer::where(
                'UserID',
                Auth::user()->UserID
            )->first();
        }

        return view(
            'customer.cart.checkout',
            compact('cart', 'customer')
        );
    }


    // ADD TO CART

    public function add(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:Service,ServiceID',
        ]);

        $product = Service::where(
                'ServiceID',
                $request->service_id
            )
            ->where('ServiceType', 1)
            ->where('IsActive', 1)
            ->firstOrFail();


        // GUEST

        if (!Auth::check()) {

            $cart = session()->get('cart', []);

            $id = $product->ServiceID;

            if (isset($cart[$id])) {

                $cart[$id]['quantity']++;

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

            return redirect(
                url()->previous() . '#product-' . $product->ServiceID
            )->with(
                'success',
                'Product added to cart successfully.'
            );
        }


        // LOGGED-IN USER

        $cart = $this->getOrCreateUserCart();

        $detail = CartDetail::where(
                'CartID',
                $cart->CartID
            )
            ->where(
                'ServiceID',
                $product->ServiceID
            )
            ->first();

        if ($detail) {

            $detail->Quantity++;
            $detail->save();

        } else {

            CartDetail::create([
                'CartID' => $cart->CartID,
                'ServiceID' => $product->ServiceID,
                'Quantity' => 1,
            ]);
        }

        $this->updateCartTime($cart);


        return redirect(
            url()->previous() . '#product-' . $product->ServiceID
        )->with(
            'success',
            'Product added to cart successfully.'
        );
    }


    // UPDATE CART

    public function update(Request $request)
    {
        $id = $request->id;
        $quantity = (int) $request->quantity;


        // GUEST

        if (!Auth::check()) {

            $cart = session()->get('cart', []);

            if (!isset($cart[$id])) {
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


        // USER

        $cart = $this->getOrCreateUserCart();

        $detail = CartDetail::where(
                'CartID',
                $cart->CartID
            )
            ->where(
                'ServiceID',
                $id
            )
            ->first();

        if (!$detail) {
            return back();
        }

        if ($quantity <= 0) {

            $detail->delete();

        } else {

            $detail->Quantity = $quantity;
            $detail->save();
        }

        $this->updateCartTime($cart);

        return back();
    }


    // REMOVE
    public function remove(Request $request)
    {
        $id = $request->id;


        // GUEST
        
        if (!Auth::check()) {

            $cart = session()->get('cart', []);

            if (isset($cart[$id])) {
                unset($cart[$id]);
            }

            session()->put('cart', $cart);

            return back();
        }


        // USER

        $cart = $this->getOrCreateUserCart();

        CartDetail::where(
                'CartID',
                $cart->CartID
            )
            ->where(
                'ServiceID',
                $id
            )
            ->delete();

        $this->updateCartTime($cart);

        return back();
    }

    //  BUY NOW
    public function buyNow(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:Service,ServiceID',
        ]);

        $product = Service::where(
                'ServiceID',
                $request->service_id
            )
            ->where('ServiceType', 1)
            ->where('IsActive', 1)
            ->firstOrFail();


        // GUEST
        

        if (!Auth::check()) {

            $cart = session()->get('cart', []);

            $id = $product->ServiceID;

            if (isset($cart[$id])) {

                $cart[$id]['quantity']++;

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

            return redirect()->route('cart.index');
        }


        // USER

        $cart = $this->getOrCreateUserCart();

        $detail = CartDetail::where(
                'CartID',
                $cart->CartID
            )
            ->where(
                'ServiceID',
                $product->ServiceID
            )
            ->first();

        if ($detail) {

            $detail->Quantity++;
            $detail->save();

        } else {

            CartDetail::create([
                'CartID' => $cart->CartID,
                'ServiceID' => $product->ServiceID,
                'Quantity' => 1,
            ]);
        }

        $this->updateCartTime($cart);

        return redirect()->route('cart.index');
    }


    // GET CURRENT CART

    private function getCurrentCart()
    {
        // GUEST

        if (!Auth::check()) {

            return session()->get('cart', []);
        }


        //USER

        $cart = $this->getOrCreateUserCart();

        $details = CartDetail::with('service')
            ->where('CartID', $cart->CartID)
            ->get();

        $result = [];

        foreach ($details as $detail) {

            if (!$detail->service) {
                continue;
            }

            $service = $detail->service;

            $result[$service->ServiceID] = [
                'id' => $service->ServiceID,
                'name' => $service->ServiceName,
                'price' => $service->Price,
                'quantity' => $detail->Quantity,
                'image' => $service->Image,
            ];
        }

        return $result;
    }


    // GET / CREATE USER CART
    

    private function getOrCreateUserCart()
    {
        $userId = Auth::user()->UserID;

        $cart = Cart::where(
            'UserID',
            $userId
        )->first();

        if (!$cart) {

            $cart = Cart::create([
                'UserID' => $userId,
                'CreatedAt' => now(),
                'UpdatedAt' => now(),
            ]);
        }

        return $cart;
    }


    // UPDATE CART TIME

    private function updateCartTime($cart)
    {
        $cart->UpdatedAt = now();
        $cart->save();
    }
}