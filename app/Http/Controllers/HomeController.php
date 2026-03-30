<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        // Featured Products
        $products = Product::with('brand')
            ->where('status', 'active')
            ->where('visibility', 'public')
            ->whereHas('brand', function ($q) {
                $q->where('status', 'active');
            })
            ->orderBy('created_at', 'desc')
            ->limit(4)->get();

        $totalProducts = Product::where('status', 'active')
            ->where('visibility', 'public')
            ->whereHas('brand', function ($q) {
                $q->where('status', 'active');
            })->count();

        // Distinct Categories
        $categories = Product::where('status', 'active')
            ->distinct()
            ->limit(8)
            ->pluck('category');

        // Featured Brands
        $brands = Brand::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->limit(4)->get();

        return Inertia::render('Home', [
            'featuredProducts' => $products,
            'totalProducts' => $totalProducts,
            'categories' => $categories,
            'featuredBrands' => $brands,
        ]);
    }
}
