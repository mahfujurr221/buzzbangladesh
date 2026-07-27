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
        $latestProducts = \App\Models\Product::with(['images', 'category', 'variations.color'])->where('active_status', 1)->latest()->take(8)->get();
        $instagramFeeds = \App\Models\InstagramFeed::where('status', 1)->latest()->take(10)->get();
        
        $testimonials = \App\Models\Testimonial::where('active_status', 1)->latest()->get();

        return view('frontend.home', compact('flashModal', 'banners', 'categories', 'allCategories', 'bestSellers', 'onSale', 'newArrivals', 'latestProducts', 'instagramFeeds', 'testimonials'));
    }

    public function shop(Request $request)
    {
        $shopCategories = \App\Models\Category::where('active_status', 1)->with(['subCategories' => function($q) {
            $q->where('active_status', 1)->withCount(['products' => function ($query) {
                $query->where('active_status', 1);
            }]);
        }])->withCount(['products' => function ($query) {
            $query->where('active_status', 1);
        }])->get();

        $shopSeasons = \App\Models\Season::where('active_status', 1)
            ->where(function ($query) {
                $query->whereNull('start_date')
                      ->orWhere('start_date', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
            })
            ->withCount(['products' => function ($query) {
                $query->where('active_status', 1);
            }])
            ->get();

        $colors = \App\Models\ProductColor::where('active_status', 1)->get();
        $sizes = \App\Models\ProductSize::where('active_status', 1)->get();
        $brands = \App\Models\Brand::where('active_status', 1)->withCount(['products' => function ($query) {
            $query->where('active_status', 1);
        }])->get();

        $query = \App\Models\Product::with(['images', 'category', 'variations.color', 'variations.size'])
            ->where('active_status', 1);

        // Filter by Category
        if ($request->filled('category')) {
            $categorySlugs = is_array($request->category) ? $request->category : [$request->category];
            // Remove empty values
            $categorySlugs = array_filter($categorySlugs);
            if (!empty($categorySlugs)) {
                $query->whereHas('category', function ($q) use ($categorySlugs) {
                    $q->whereIn('slug', $categorySlugs);
                });
            }
        }

        // Filter by SubCategory
        if ($request->filled('subcategory')) {
            $subCategorySlugs = is_array($request->subcategory) ? $request->subcategory : [$request->subcategory];
            $subCategorySlugs = array_filter($subCategorySlugs);
            if (!empty($subCategorySlugs)) {
                $query->whereHas('subCategory', function ($q) use ($subCategorySlugs) {
                    $q->whereIn('slug', $subCategorySlugs);
                });
            }
        }

        // Filter by Season
        if ($request->filled('season')) {
            $seasonSlugs = is_array($request->season) ? $request->season : [$request->season];
            $seasonSlugs = array_filter($seasonSlugs);
            if (!empty($seasonSlugs)) {
                $query->whereHas('season', function ($q) use ($seasonSlugs) {
                    $q->whereIn('slug', $seasonSlugs);
                });
            }
        }

        // Filter by Search Query (Name)
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where('name', 'like', '%' . $searchTerm . '%');
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

        // Hot Deals filter: show only discounted products
        $isHotDeals = $request->get('filter') === 'hot-deals';
        if ($isHotDeals) {
            $discountService = app(\App\Services\DiscountService::class);
            $discountedIds = $discountService->getDiscountedProductIds();
            if (!empty($discountedIds)) {
                $query->whereIn('id', $discountedIds);
            } else {
                // No active discounts, return empty
                $query->whereRaw('1 = 0');
            }
        }

        $products = $query->paginate(12)->withQueryString();

        return view('frontend.shop', compact('shopCategories', 'shopSeasons', 'colors', 'sizes', 'brands', 'products', 'isHotDeals'));
    }

    public function searchSuggestions(Request $request)
    {
        $query = $request->get('q');
        if (empty($query)) {
            return response()->json([]);
        }

        $products = \App\Models\Product::with(['images'])
            ->where('active_status', 1)
            ->where('name', 'like', '%' . $query . '%')
            ->select('id', 'name', 'slug', 'sale_price', 'purchase_price')
            ->take(5)
            ->get();

        $formattedProducts = $products->map(function ($product) {
            $img = $product->images->first();
            $imageUrl = $img ? asset($img->image_path) : asset('backend/images/products/placeholder.png');
            $price = number_format($product->sale_price ?? $product->purchase_price, 2);

            return [
                'name' => $product->name,
                'slug' => $product->slug,
                'url' => route('frontend.product.details', $product->slug),
                'image' => $imageUrl,
                'price' => '৳' . $price
            ];
        });

        return response()->json($formattedProducts);
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
        $customer = null;
        if (auth()->check()) {
            $customer = \App\Models\Customer::where('phone', auth()->user()->phone)->first();
        }
        return view('frontend.checkout', compact('customer'));
    }

    public function contact()
    {
        return view('frontend.contact');
    }

    public function contactSubmit(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        \App\Models\ContactMessage::create($request->all());

        return redirect()->back()->with('success', 'Your message has been sent successfully. We will get back to you soon!');
    }

    public function page($slug)
    {
        $page = \App\Models\Page::where('slug', $slug)->where('status', 1)->firstOrFail();
        $generalSetting = \App\Models\Setting::first();
        return view('frontend.page', compact('page', 'generalSetting'));
    }
}
