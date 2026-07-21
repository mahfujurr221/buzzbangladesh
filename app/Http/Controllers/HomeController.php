<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\FlashModal;

class HomeController extends Controller
{
    public function index()
    {
        $flashModal = FlashModal::active()->latest()->first();
        
        $banners = \App\Models\Banner::where('status', 1)->latest()->get();
        
        $categories = \App\Models\Category::where('active_status', 1)
            ->with(['products' => function($query) {
                $query->where('active_status', 1)
                      ->with(['images', 'category', 'variations.color']) // eager load relations for the frontend
                      ->latest()
                      ->take(8);
            }])
            ->whereHas('products', function($query) {
                $query->where('active_status', 1);
            })
            ->take(5)
            ->get();
            
        $allCategories = \App\Models\Category::where('active_status', 1)->get();
        
        $bestSellers = \App\Models\Product::with(['images', 'category', 'variations.color'])->where('active_status', 1)->where('is_best_seller', 1)->latest()->take(8)->get();
        $onSale = \App\Models\Product::with(['images', 'category', 'variations.color'])->where('active_status', 1)->where('is_on_sale', 1)->latest()->take(8)->get();
        $newArrivals = \App\Models\Product::with(['images', 'category', 'variations.color'])->where('active_status', 1)->where('is_new_arrival', 1)->latest()->take(8)->get();
        $instagramFeeds = \App\Models\InstagramFeed::where('status', 1)->latest()->take(10)->get();

        return view('frontend.home', compact('flashModal', 'banners', 'categories', 'allCategories', 'bestSellers', 'onSale', 'newArrivals', 'instagramFeeds'));
    }

    public function shop(Request $request)
    {
        $categories = \App\Models\Category::where('active_status', 1)->withCount('products')->get();
        $colors = \App\Models\ProductColor::where('active_status', 1)->get();
        $sizes = \App\Models\ProductSize::where('active_status', 1)->get();
        $brands = \App\Models\Brand::where('active_status', 1)->withCount('products')->get();

        $query = \App\Models\Product::with(['images', 'category', 'variations.color', 'variations.size'])
            ->where('active_status', 1);

        // Filter by Category
        if ($request->filled('category')) {
            $categorySlug = $request->category;
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug)->orWhere('name', $categorySlug);
            });
        }

        // Filter by Size
        if ($request->filled('size')) {
            $sizeParam = is_array($request->size) ? $request->size : [$request->size];
            $query->whereHas('variations.size', function ($q) use ($sizeParam) {
                $q->whereIn('name', $sizeParam);
            });
        }

        // Filter by Color
        if ($request->filled('color')) {
            $colorParam = is_array($request->color) ? $request->color : [$request->color];
            $query->whereHas('variations.color', function ($q) use ($colorParam) {
                $q->whereIn('name', $colorParam);
            });
        }

        // Filter by Brand
        if ($request->filled('brands')) {
            $brandParam = is_array($request->brands) ? $request->brands : [$request->brands];
            $query->whereHas('brand', function ($q) use ($brandParam) {
                $q->whereIn('name', $brandParam)->orWhereIn('slug', $brandParam);
            });
        }

        // Filter by Price
        if ($request->filled('min_price')) {
            $query->where('sale_price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('sale_price', '<=', $request->max_price);
        }

        // Filter Sale Products Only
        if ($request->filled('on_sale')) {
            $query->where('is_on_sale', 1);
        }

        // Sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'priceLowToHigh':
                    $query->orderBy('sale_price', 'asc');
                    break;
                case 'priceHighToLow':
                    $query->orderBy('sale_price', 'desc');
                    break;
                case 'discountHighToLow':
                    // If discount is derived, we might order by sale_price for now if no specific column
                    $query->where('is_on_sale', 1)->orderBy('sale_price', 'asc');
                    break;
                case 'soldQuantityHighToLow':
                    $query->orderBy('is_best_seller', 'desc');
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();

        return view('frontend.shop', compact('categories', 'colors', 'sizes', 'brands', 'products'));
    }

    public function productDetails($slug)
    {
        $product = \App\Models\Product::with([
            'images',
            'variations.color',
            'variations.size',
            'category',
            'brand'
        ])->where('slug', $slug)->firstOrFail();
        
        $relatedProducts = \App\Models\Product::with(['images', 'variations', 'category'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('active_status', 1)
            ->limit(8)
            ->get();
            
        return view('frontend.product-details', compact('product', 'relatedProducts'));
    }

    public function checkout()
    {
        return view('frontend.checkout');
    }

    public function blog()
    {
        return view('frontend.blog');
    }

    public function about()
    {
        return view('frontend.about');
    }

    public function contact()
    {
        return view('frontend.contact');
    }

    public function page($slug)
    {
        $page = \App\Models\Page::where('slug', $slug)->where('status', 1)->firstOrFail();
        return view('frontend.page', compact('page'));
    }
}
