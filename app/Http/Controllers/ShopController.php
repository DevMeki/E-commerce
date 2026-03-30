<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Product;
use App\Models\BrandFollower;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function storeView(Request $request, $slug)
    {
        $brand = Brand::where('slug', $slug)->where('status', 'active')->firstOrFail();
        
        $products = Product::where('brand_id', $brand->id)
            ->where('status', 'active')
            ->where('visibility', 'public')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $categories = $products->pluck('category')->unique()->values()->all();
        array_unshift($categories, 'All');

        $isFollowing = false;
        if (Auth::guard('buyer')->check()) {
            $isFollowing = BrandFollower::where('buyer_id', Auth::guard('buyer')->id())
                ->where('brand_id', $brand->id)->exists();
        }

        return Inertia::render('Store', [
            'store' => clone $brand,
            'products' => $products,
            'categories' => $categories,
            'isFollowing' => $isFollowing
        ]);
    }

    public function marketplace(Request $request)
    {
        $query = Product::with('brand')
            ->where('status', 'active')
            ->where('visibility', 'public')
            ->whereHas('brand', function ($q) {
                $q->where('status', 'active');
            });

        if ($request->filled('q')) {
            $searchTerm = '%' . $request->q . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('category', 'like', $searchTerm)
                  ->orWhere('short_desc', 'like', $searchTerm);
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $categories = Product::where('status', 'active')->distinct()->pluck('category');

        return Inertia::render('Marketplace', [
            'products' => $products,
            'categories' => $categories,
            'filters' => $request->only(['q', 'category'])
        ]);
    }

    public function brandsPage(Request $request)
    {
        $brands = Brand::where('status', 'active')->orderBy('created_at', 'desc')->paginate(20);
        return Inertia::render('BrandsPage', [
            'brands' => $brands
        ]);
    }

    public function categoriesPage(Request $request)
    {
        $categories = Product::where('status', 'active')
            ->distinct('category')
            ->pluck('category')
            ->toArray();

        // Also fetch product counts per category if needed, but for now just the list
        $categoryCounts = [];
        foreach ($categories as $cat) {
            $categoryCounts[$cat] = Product::where('status', 'active')->where('category', $cat)->count();
        }

        return Inertia::render('Categories', [
            'categories' => $categoryCounts
        ]);
    }
}
