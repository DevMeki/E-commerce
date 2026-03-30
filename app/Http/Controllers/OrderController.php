<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function brandOrders(Request $request)
    {
        $brand = Auth::guard('brand')->user();
        $status = $request->input('status', 'all');
        $search = $request->input('q');

        $query = Order::where('brand_id', $brand->id);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'LIKE', "%{$search}%")
                  ->orWhere('customer_name', 'LIKE', "%{$search}%")
                  ->orWhere('customer_email', 'LIKE', "%{$search}%");
            });
        }

        $orders = $query->with(['items' => function($q) {
            $q->orderBy('id', 'asc');
        }])->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $statusCounts = [
            'all' => Order::where('brand_id', $brand->id)->count(),
            'processing' => Order::where('brand_id', $brand->id)->where('status', 'processing')->count(),
            'paid' => Order::where('brand_id', $brand->id)->where('status', 'paid')->count(),
            'shipped' => Order::where('brand_id', $brand->id)->where('status', 'shipped')->count(),
            'delivered' => Order::where('brand_id', $brand->id)->where('status', 'delivered')->count(),
            'cancelled' => Order::where('brand_id', $brand->id)->where('status', 'cancelled')->count(),
        ];

        return \Inertia\Inertia::render('brand/Orders', [
            'orders' => $orders,
            'statusCounts' => $statusCounts,
            'filters' => $request->only(['status', 'q'])
        ]);
    }

    public function brandOrderDetails($id)
    {
        $brand = Auth::guard('brand')->user();

        $order = Order::with(['items', 'buyer', 'address'])
            ->where('brand_id', $brand->id)
            ->where('id', $id)
            ->firstOrFail();

        return \Inertia\Inertia::render('brand/OrderDetails', [
            'order' => $order
        ]);
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $brand = Auth::guard('brand')->user();

        $order = Order::where('id', $id)->whereHas('items.product', function ($query) use ($brand) {
            $query->where('brand_id', $brand->id);
        })->firstOrFail();

        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $order->status = $request->status;
        $order->save();

        return back()->with('success', 'Order status updated successfully');
    }
}
