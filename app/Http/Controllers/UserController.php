<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserController extends Controller
{
    public function dashboard()
    {
        $buyer = Auth::guard('buyer')->user();
        return Inertia::render('user/Dashboard', [
            'user' => $buyer
        ]);
    }

    public function purchases()
    {
        $buyerId = Auth::guard('buyer')->id();
        $orders = Order::where('buyer_id', $buyerId)
            ->with(['items.product'])
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('user/Purchases', [
            'orders' => $orders
        ]);
    }

    public function wishlist()
    {
        $buyerId = Auth::guard('buyer')->id();
        $wishlist = Wishlist::where('buyer_id', $buyerId)
            ->with('product.brand')
            ->get();

        return Inertia::render('user/Wishlist', [
            'wishlist' => $wishlist
        ]);
    }
}
