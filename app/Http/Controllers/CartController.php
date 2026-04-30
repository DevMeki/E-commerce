<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Product;
use Inertia\Inertia;

class CartController extends Controller
{
    /**
     * Get the authenticated buyer's ID, or null for guests.
     */
    private function buyerId(): ?int
    {
        return Auth::guard('buyer')->id();
    }

    /**
     * Format a raw cart item (DB row or session entry) into the shape Cart.tsx expects.
     */
    private function formatDbItem(Cart $item): array
    {
        $variantStr = null;
        if ($item->variants) {
            $decoded = json_decode($item->variants, true);
            if (is_array($decoded)) {
                $variantStr = implode(', ', array_values($decoded));
            } else {
                $variantStr = $item->variants;
            }
        }

        $product = $item->product;

        return [
            'id'         => (string) $item->id,  // cart row primary key (used for update/remove)
            'product_id' => $item->product_id,
            'name'       => $product?->name ?? 'Unknown Product',
            'price'      => $product?->price ?? 0,
            'qty'        => $item->quantity,
            'main_image' => $product?->main_image,
            'seller'     => $product?->brand?->brand_name ?? 'Local Brand',
            'variant'    => $variantStr,
        ];
    }

    /**
     * Format a session cart entry into the shape Cart.tsx expects.
     */
    private function formatSessionItem(string $key, array $item): array
    {
        $variantStr = null;
        if (isset($item['variants']) && is_array($item['variants'])) {
            $variantStr = implode(', ', array_values($item['variants']));
        }

        return [
            'id'         => $key,
            'product_id' => $item['id'],
            'name'       => $item['name'],
            'price'      => $item['price'],
            'qty'        => $item['quantity'],
            'main_image' => $item['image'],
            'seller'     => $item['brand']['brand_name'] ?? 'Local Brand',
            'variant'    => $variantStr,
        ];
    }

    // -------------------------------------------------------------------------

    public function index(Request $request)
    {
        $buyerId = $this->buyerId();

        if ($buyerId) {
            // ── Database cart ──────────────────────────────────────────────
            $rows = Cart::where('buyer_id', $buyerId)
                ->with('product.brand')
                ->get();

            $items = $rows->map(fn($item) => $this->formatDbItem($item))->values()->all();

            $subtotal = collect($items)->sum(fn($i) => $i['price'] * $i['qty']);
        } else {
            // ── Session cart ───────────────────────────────────────────────
            $cart = session()->get('cart', []);

            $items = collect($cart)
                ->map(fn($item, $key) => $this->formatSessionItem($key, $item))
                ->values()
                ->all();

            $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
        }

        $deliveryEstimate = $subtotal > 0 ? 1500 : 0;
        $total = $subtotal + $deliveryEstimate;

        return Inertia::render('Cart', [
            'cartItems'        => $items,
            'subtotal'         => $subtotal,
            'deliveryEstimate' => $deliveryEstimate,
            'total'            => $total,
        ]);
    }

    // -------------------------------------------------------------------------

    public function add(Request $request)
    {
        $request->validate([
            'product_id'          => 'required|exists:product,id',
            'quantity'            => 'required|integer|min:1',
            'variants'            => 'nullable|string',
            'redirect_to_checkout'=> 'nullable|string',
        ]);

        $product  = Product::with('brand')->findOrFail($request->product_id);
        $buyerId  = $this->buyerId();
        $variants = $request->variants; // already a JSON string or null

        if ($buyerId) {
            // ── Database cart ──────────────────────────────────────────────
            $existing = Cart::where('buyer_id', $buyerId)
                ->where('product_id', $product->id)
                ->where('variants', $variants)
                ->first();

            if ($existing) {
                $existing->increment('quantity', $request->quantity);
            } else {
                Cart::create([
                    'buyer_id'   => $buyerId,
                    'product_id' => $product->id,
                    'quantity'   => $request->quantity,
                    'variants'   => $variants,
                    'added_at'   => now(),
                ]);
            }
        } else {
            // ── Session cart ───────────────────────────────────────────────
            $cart    = session()->get('cart', []);
            $cartKey = $product->id . '_' . md5($variants ?? '');

            if (isset($cart[$cartKey])) {
                $cart[$cartKey]['quantity'] += $request->quantity;
            } else {
                $cart[$cartKey] = [
                    'id'       => $product->id,
                    'name'     => $product->name,
                    'price'    => $product->price,
                    'quantity' => $request->quantity,
                    'image'    => $product->main_image,
                    'brand'    => $product->brand ? ['brand_name' => $product->brand->brand_name] : null,
                    'variants' => $variants ? json_decode($variants, true) : null,
                ];
            }

            session()->put('cart', $cart);
        }

        if ($request->redirect_to_checkout === 'true') {
            return redirect()->route('cart.index')->with('success', 'Proceeding to checkout');
        }

        return back()->with('success', 'Product added to cart');
    }

    // -------------------------------------------------------------------------

    public function update(Request $request)
    {
        $request->validate([
            'cart_id'  => 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        $buyerId = $this->buyerId();

        if ($buyerId) {
            // ── Database cart ──────────────────────────────────────────────
            Cart::where('id', $request->cart_id)
                ->where('buyer_id', $buyerId)   // ownership check
                ->update(['quantity' => $request->quantity]);
        } else {
            // ── Session cart ───────────────────────────────────────────────
            $cart = session()->get('cart', []);
            if (isset($cart[$request->cart_id])) {
                $cart[$request->cart_id]['quantity'] = $request->quantity;
                session()->put('cart', $cart);
            }
        }

        return back()->with('success', 'Cart updated');
    }

    // -------------------------------------------------------------------------

    public function remove(Request $request)
    {
        $request->validate([
            'cart_id' => 'required',
        ]);

        $buyerId = $this->buyerId();

        if ($buyerId) {
            // ── Database cart ──────────────────────────────────────────────
            Cart::where('id', $request->cart_id)
                ->where('buyer_id', $buyerId)   // ownership check
                ->delete();
        } else {
            // ── Session cart ───────────────────────────────────────────────
            $cart = session()->get('cart', []);
            if (isset($cart[$request->cart_id])) {
                unset($cart[$request->cart_id]);
                session()->put('cart', $cart);
            }
        }

        return back()->with('success', 'Product removed from cart');
    }

    // -------------------------------------------------------------------------

    /**
     * Merge a guest session cart into the buyer's database cart.
     * Called after a successful buyer login.
     */
    public static function mergeSessionCartToDb(int $buyerId): void
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return;
        }

        foreach ($cart as $item) {
            $variants = isset($item['variants']) ? json_encode($item['variants']) : null;

            $existing = Cart::where('buyer_id', $buyerId)
                ->where('product_id', $item['id'])
                ->where('variants', $variants)
                ->first();

            if ($existing) {
                $existing->increment('quantity', $item['quantity']);
            } else {
                Cart::create([
                    'buyer_id'   => $buyerId,
                    'product_id' => $item['id'],
                    'quantity'   => $item['quantity'],
                    'variants'   => $variants,
                    'added_at'   => now(),
                ]);
            }
        }

        session()->forget('cart');
    }
}
