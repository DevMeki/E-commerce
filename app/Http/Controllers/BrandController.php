<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Auth\Events\Registered;

class BrandController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'owner_name' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:brand',
            'password' => 'required|string|min:8|confirmed',
            'brand_name' => 'required|string|max:100',
            'brand_slug' => 'nullable|string|max:50|unique:brand,slug',
            'brand_category' => 'required|string|max:50',
            'brand_location' => 'required|string|max:100',
        ]);

        $slug = $request->brand_slug ?: \Illuminate\Support\Str::slug($request->brand_name);

        $brand = Brand::create([
            'owner_name' => $request->owner_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'brand_name' => $request->brand_name,
            'slug' => $slug,
            'category' => $request->brand_category,
            'location' => $request->brand_location,
            'status' => 'pending',
            'rating' => 0.00,
            'total_reviews' => 0,
            'total_sales' => 0,
            'followers' => 0,
            'products_count' => 0,
            'store_views' => 0,
        ]);

        event(new Registered($brand));
        Auth::guard('brand')->login($brand);

        return redirect()->route('brand.dashboard');
    }

    public function onboarding()
    {
        return Inertia::render('brand/Onboarding');
    }

    public function storeOnboarding(Request $request)
    {
        $brand = Auth::guard('brand')->user();

        $request->validate([
            'brand_name' => 'required|string|max:255',
            'brand_category' => 'required|string',
            'brand_location' => 'required|string',
            'brand_bio' => 'required|string',
            'contact_email' => 'required|email',
            'brand_logo' => 'nullable|image|max:2048',
        ]);

        $data = $request->only([
            'brand_name', 'brand_slug', 'brand_category', 'brand_location',
            'brand_tagline', 'brand_bio', 'whatsapp', 'instagram',
            'shipping_policy', 'return_policy', 'contact_email'
        ]);

        if ($request->hasFile('brand_logo')) {
            $path = $request->file('brand_logo')->store('brands/logos', 'public');
            $data['logo'] = '/storage/' . $path;
        }

        $data['status'] = 'active'; // Mark as active after onboarding

        Brand::where('id', $brand->id)->update($data);

        return redirect()->route('brand.dashboard')->with('success', 'Brand profile updated successfully.');
    }

    public function help()
    {
        return Inertia::render('brand/Help');
    }

    public function dashboard()
    {
        $brand = Auth::guard('brand')->user();
        
        // Stats
        $revenueToday = \App\Models\Order::where('brand_id', $brand->id)
            ->whereDate('created_at', now()->today())
            ->where('status', '!=', 'cancelled')
            ->sum('total');

        $revenue30d = \App\Models\Order::where('brand_id', $brand->id)
            ->where('created_at', '>=', now()->subDays(30))
            ->where('status', '!=', 'cancelled')
            ->sum('total');

        $orders30d = \App\Models\Order::where('brand_id', $brand->id)
            ->where('created_at', '>=', now()->subDays(30))
            ->where('status', '!=', 'cancelled')
            ->count();

        $recentOrders = \App\Models\Order::where('brand_id', $brand->id)
            ->with(['items' => function($query) {
                $query->limit(1);
            }])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return Inertia::render('brand/Dashboard', [
            'brand' => $brand,
            'stats' => [
                'revenue_today' => $revenueToday,
                'revenue_30d' => $revenue30d,
                'orders_30d' => $orders30d,
                'products_live' => $brand->products_count,
                'store_views' => $brand->store_views,
            ],
            'recentOrders' => $recentOrders
        ]);
    }
}
