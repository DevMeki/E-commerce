<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = session()->get('cart', []);
        
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $deliveryEstimate = $subtotal > 0 ? 1500 : 0; // Flat estimate for now
        $total = $subtotal + $deliveryEstimate;

        $items = collect($cart)->map(function ($item, $key) {
            $variantStr = null;
            if (isset($item['variants']) && is_array($item['variants'])) {
                $variantStr = implode(', ', array_values($item['variants']));
            }

            return [
                'id' => $key,
                'product_id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'qty' => $item['quantity'],
                'main_image' => $item['image'],
                'seller' => $item['brand']['brand_name'] ?? 'Local Brand',
                'variant' => $variantStr
            ];
        })->values()->all();

        return Inertia::render('Cart', [
            'cartItems' => $items,
            'subtotal' => $subtotal,
            'deliveryEstimate' => $deliveryEstimate,
            'total' => $total
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:product,id',
            'quantity' => 'required|integer|min:1',
            'variants' => 'nullable|string',
            'redirect_to_checkout' => 'nullable|string'
        ]);

        $product = Product::with('brand')->findOrFail($request->product_id);
        $cart = session()->get('cart', []);

        $cartKey = $product->id . '_' . md5($request->variants ?? '');

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $request->quantity;
        } else {
            $cart[$cartKey] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $request->quantity,
                'image' => $product->main_image,
                'brand' => $product->brand ? clone $product->brand : null,
                'variants' => $request->variants ? json_decode($request->variants, true) : null
            ];
        }

        session()->put('cart', $cart);

        if ($request->redirect_to_checkout === 'true') {
            return redirect()->route('cart.index')->with('success', 'Proceeding to checkout');
        }

        return back()->with('success', 'Product added to cart');
    }

    public function update(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|string',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);
        if (isset($cart[$request->cart_id])) {
            $cart[$request->cart_id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Cart updated');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|string'
        ]);

        $cart = session()->get('cart', []);
        if (isset($cart[$request->cart_id])) {
            unset($cart[$request->cart_id]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Product removed from cart');
    }
}
