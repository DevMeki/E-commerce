<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Jobs\ProcessProductImages;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $brand = Auth::guard('brand')->user();
        $status = $request->input('status', 'all');
        $search = $request->input('q');

        $query = Product::where('brand_id', $brand->id);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%")
                  ->orWhere('category', 'LIKE', "%{$search}%");
            });
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $stats = [
            'all' => Product::where('brand_id', $brand->id)->count(),
            'active' => Product::where('brand_id', $brand->id)->where('status', 'active')->count(),
            'draft' => Product::where('brand_id', $brand->id)->where('status', 'draft')->count(),
            'archived' => Product::where('brand_id', $brand->id)->where('status', 'archived')->count(),
            'hidden' => Product::where('brand_id', $brand->id)->where('visibility', 'private')->count(),
        ];
            
        return \Inertia\Inertia::render('brand/Products', [
            'products' => $products,
            'stats' => $stats,
            'filters' => $request->only(['status', 'q'])
        ]);
    }

    public function create()
    {
        return \Inertia\Inertia::render('brand/AddProduct');
    }

    public function edit($id)
    {
        $brand = Auth::guard('brand')->user();
        $product = Product::with('images')->where('brand_id', $brand->id)->findOrFail($id);
        
        return \Inertia\Inertia::render('brand/AddProduct', [
            'product' => $product
        ]);
    }

    public function update(Request $request, $id)
    {
        $brand = Auth::guard('brand')->user();
        $product = Product::where('brand_id', $brand->id)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'short_desc' => 'required|string|max:500',
            'long_desc' => 'nullable|string',
            'status' => 'required|in:draft,active,archived',
            'visibility' => 'required|in:public,private',
            'main_image_file' => 'nullable|image|max:5120',
            'shipping_fee' => 'nullable|numeric|min:0',
            'ships_from' => 'required|string|max:100',
            'processing_time' => 'nullable|string|max:100',
            'variants_text' => 'nullable|string',
            'gallery.*' => 'nullable|image|max:5120'
        ]);

        if ($request->hasFile('main_image_file')) {
            $path = $request->file('main_image_file')->store('products', 'public');
            $product->main_image = '/storage/' . $path;
        }

        $product->update([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'price' => $validated['price'],
            'compare_at_price' => $validated['compare_at_price'] ?? null,
            'stock' => $validated['stock'],
            'short_desc' => $validated['short_desc'],
            'long_desc' => $validated['long_desc'] ?? null,
            'status' => $validated['status'],
            'visibility' => $validated['visibility'],
            'shipping_fee' => $validated['shipping_fee'] ?? 0.00,
            'ships_from' => $validated['ships_from'],
            'processing_time' => $validated['processing_time'] ?? null,
            'variants_text' => $validated['variants_text'] ?? null,
        ]);

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => '/storage/' . $path,
                    'sort_order' => $product->images()->count()
                ]);
            }
        }

        ProcessProductImages::dispatch($product);

        return redirect()->route('brand.products')->with('success', 'Product updated successfully');
    }

    public function handleAction(Request $request)
    {
        $brand = Auth::guard('brand')->user();
        $productId = $request->input('product_id');
        $action = $request->input('action');

        $product = Product::where('brand_id', $brand->id)->findOrFail($productId);

        if ($action === 'delete') {
            $product->delete();
            return response()->json(['success' => true, 'message' => 'Product deleted successfully.']);
        }

        if ($action === 'archive') {
            $product->update(['status' => 'archived']);
            return response()->json(['success' => true, 'message' => 'Product archived.']);
        }

        if ($action === 'unarchive') {
            $product->update(['status' => 'active']);
            return response()->json(['success' => true, 'message' => 'Product unarchived.']);
        }

        return response()->json(['success' => false, 'message' => 'Invalid action.']);
    }

    public function show($id)
    {
        $product = Product::with(['brand', 'images'])->where('id', $id)->where('status', 'active')->firstOrFail();
        
        // Prepare images array
        $images = $product->images->pluck('image_url')->toArray();
        if ($product->main_image) {
            array_unshift($images, $product->main_image);
        }
        if (empty($images)) {
            $images = ['https://via.placeholder.com/600x600?text=Product+Image'];
        }

        // Prepare seller data
        $seller = [
            'name' => $product->brand->brand_name,
            'location' => $product->ships_from,
            'rating' => $product->brand->rating ?? 4.5,
            'total_products' => Product::where('brand_id', $product->brand_id)->count(),
        ];

        // Prepare variants
        $variants = [];
        if ($product->variants_text) {
            $parts = explode('|', $product->variants_text);
            foreach ($parts as $part) {
                $sub = explode(':', $part);
                if (count($sub) == 2) {
                    $type = trim($sub[0]);
                    $opts = array_map('trim', explode(',', $sub[1]));
                    $variants[$type] = $opts;
                }
            }
        }

        // Related Products (same category)
        $relatedProducts = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->limit(4)
            ->get();

        // More from Brand
        $moreBrandProducts = Product::where('brand_id', $product->brand_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->limit(4)
            ->get();

        // Ensure these extra properties are on the product object for JS
        $product->brand_name = $product->brand->brand_name;
        $product->brand_slug = $product->brand->brand_slug;

        return \Inertia\Inertia::render('Product', [
            'product' => $product,
            'seller' => $seller,
            'images' => $images,
            'relatedProducts' => $relatedProducts,
            'moreBrandProducts' => $moreBrandProducts,
            'variants' => $variants
        ]);
    }

    public function store(Request $request)
    {
        $brand = Auth::guard('brand')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'short_desc' => 'required|string|max:500',
            'long_desc' => 'nullable|string',
            'status' => 'required|in:draft,active,archived',
            'visibility' => 'required|in:public,private',
            'featured' => 'nullable|boolean',
            'main_image' => 'required|image|max:5120',
            'shipping_fee' => 'nullable|numeric|min:0',
            'ships_from' => 'required|string|max:100',
            'processing_time' => 'nullable|string|max:100',
            'variants_text' => 'nullable|string',
            'gallery.*' => 'nullable|image|max:5120'
        ]);

        $slug = Str::slug($validated['name']);
        if (Product::where('slug', $slug)->exists()) {
            $slug .= '-' . time();
        }

        $sku = $request->input('sku', 'PROD-' . strtoupper(substr(md5(uniqid()), 0, 8)));

        $mainImageUrl = null;
        if ($request->hasFile('main_image')) {
            $path = $request->file('main_image')->store('products', 'public');
            $mainImageUrl = '/storage/' . $path;
        }

        $publishedAt = $validated['status'] === 'active' ? now() : null;

        $product = Product::create([
            'brand_id' => $brand->id,
            'name' => $validated['name'],
            'slug' => $slug,
            'sku' => $sku,
            'category' => $validated['category'],
            'price' => $validated['price'],
            'compare_at_price' => $validated['compare_at_price'] ?? null,
            'stock' => $validated['stock'],
            'short_desc' => $validated['short_desc'],
            'long_desc' => $validated['long_desc'] ?? null,
            'status' => $validated['status'],
            'visibility' => $validated['visibility'],
            'featured' => $validated['featured'] ?? 0,
            'main_image' => $mainImageUrl,
            'shipping_fee' => $validated['shipping_fee'] ?? 0.00,
            'ships_from' => $validated['ships_from'],
            'processing_time' => $validated['processing_time'] ?? null,
            'variants_text' => $validated['variants_text'] ?? null,
            'rating' => 0.00,
            'total_reviews' => 0,
            'total_sales' => 0,
            'views' => 0,
            'published_at' => $publishedAt
        ]);

        if ($request->hasFile('gallery')) {
            $sortOrder = 0;
            foreach ($request->file('gallery') as $image) {
                if ($sortOrder >= 8) break;
                
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => '/storage/' . $path,
                    'sort_order' => $sortOrder
                ]);
                $sortOrder++;
            }
        }

        ProcessProductImages::dispatch($product);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $validated['status'] === 'draft' ? 'Product saved as draft successfully!' : 'Product published successfully!'
            ]);
        }

        return redirect()->route('brand.products')->with('success', 'Product created successfully');
    }
}
