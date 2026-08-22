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

    /**
     * Build a cart line's display data from a fresh Service record - always
     * reads Price/activePromotion live off the DB rather than trusting
     * whatever was cached in the session/CartDetail at add-to-cart time, so
     * a promotion that starts, ends, or changes while an item sits in the
     * cart is reflected correctly instead of charging a stale price.
     */
    private function buildCartItem(Service $service, int $quantity): array
    {
        return [
            'id' => $service->ServiceID,
            'name' => $service->ServiceName,
            'price' => (float) $service->Price,
            'discounted_price' => (float) $service->discounted_price,
            'promotion' => $service->activePromotion,
            'quantity' => $quantity,
            'image' => $service->Image,
        ];
    }

    private function getCurrentCart()
    {
        // GUEST: session only stores {id, quantity} that matters here (name/
        // price/image are re-derived fresh below) - kept as an array keyed
        // by ServiceID so add()/update()/remove() can still look items up
        // by id the way they already do.

        if (!Auth::check()) {

            $sessionCart = session()->get('cart', []);

            if (empty($sessionCart)) {
                return [];
            }

            $services = Service::with('activePromotion')
                ->whereIn('ServiceID', array_keys($sessionCart))
                ->get()
                ->keyBy('ServiceID');

            $result = [];

            foreach ($sessionCart as $id => $item) {

                $service = $services->get($id);

                // Product was deleted/deactivated since it was added - drop it
                // rather than show stale cached data for something that no
                // longer exists.
                if (!$service) {
                    continue;
                }

                $result[$id] = $this->buildCartItem($service, (int) $item['quantity']);
            }

            return $result;
        }


        //USER

        $cart = $this->getOrCreateUserCart();

        $details = CartDetail::with('service.activePromotion')
            ->where('CartID', $cart->CartID)
            ->get();

        $result = [];

        foreach ($details as $detail) {

            if (!$detail->service) {
                continue;
            }

            $result[$detail->service->ServiceID] = $this->buildCartItem($detail->service, (int) $detail->Quantity);
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