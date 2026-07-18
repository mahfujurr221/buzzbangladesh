<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiscountController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:list-discount',   ['only' => ['index']]);
        $this->middleware('can:create-discount', ['only' => ['create', 'store']]);
        $this->middleware('can:edit-discount',   ['only' => ['edit', 'update']]);
        $this->middleware('can:delete-discount', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $query = Discount::with(['category', 'product']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('status')) {
            $today = now()->toDateString();
            match ($request->status) {
                'active'   => $query->where('active_status', true)->where('start_date', '<=', $today)->where('end_date', '>=', $today),
                'upcoming' => $query->where('active_status', true)->where('start_date', '>', $today),
                'expired'  => $query->where('end_date', '<', $today),
                'disabled' => $query->where('active_status', false),
                default    => null,
            };
        }

        if ($request->filled('date_from')) {
            $query->where('start_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('end_date', '<=', $request->date_to);
        }

        $discounts = $query->latest()->paginate(20)->withQueryString();

        return view('backend.pages.discount.index', compact('discounts'));
    }

    public function create()
    {
        $categories = Category::where('active_status', 1)->get();
        return view('backend.pages.discount.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name'                => 'required|string|max:150',
            'level'               => 'required|in:category,product,variation',
            'discount_percentage' => 'required|numeric|min:0.01|max:100',
            'start_date'          => 'required|date',
            'end_date'            => 'required|date|after_or_equal:start_date',
        ];

        if ($request->level === 'category') {
            $rules['category_id'] = 'required|exists:categories,id';
        } elseif ($request->level === 'product') {
            $rules['product_id'] = 'required|exists:products,id';
        } elseif ($request->level === 'variation') {
            $rules['product_id']      = 'required|exists:products,id';
            $rules['variation_ids']   = 'required|array|min:1';
            $rules['variation_ids.*'] = 'exists:product_variations,id';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            toast($validator->errors()->first(), 'error');
            return back()->withInput();
        }

        Discount::create([
            'name'                => $request->name,
            'level'               => $request->level,
            'category_id'         => $request->level === 'category' ? $request->category_id : null,
            'product_id'          => in_array($request->level, ['product', 'variation']) ? $request->product_id : null,
            'variation_ids'       => $request->level === 'variation' ? $request->variation_ids : null,
            'discount_percentage' => $request->discount_percentage,
            'start_date'          => $request->start_date,
            'end_date'            => $request->end_date,
            'active_status'       => $request->has('active_status') ? 1 : 0,
            'created_by'          => auth()->id(),
        ]);

        toast('Discount created successfully!', 'success');
        return redirect()->route('discounts.index');
    }

    public function edit($id)
    {
        $discount   = Discount::findOrFail($id);
        $categories = Category::where('active_status', 1)->get();

        // Preload products and variations for the current discount
        $products   = $discount->category_id
            ? Product::where('category_id', $discount->category_id)->where('active_status', 1)->get()
            : ($discount->product_id ? Product::where('id', $discount->product_id)->get() : collect());

        $variations = $discount->product_id
            ? ProductVariation::where('product_id', $discount->product_id)->where('active_status', 1)->get()
            : collect();

        return view('backend.pages.discount.edit', compact('discount', 'categories', 'products', 'variations'));
    }

    public function update(Request $request, $id)
    {
        $discount = Discount::findOrFail($id);

        $rules = [
            'name'                => 'required|string|max:150',
            'level'               => 'required|in:category,product,variation',
            'discount_percentage' => 'required|numeric|min:0.01|max:100',
            'start_date'          => 'required|date',
            'end_date'            => 'required|date|after_or_equal:start_date',
        ];

        if ($request->level === 'category') {
            $rules['category_id'] = 'required|exists:categories,id';
        } elseif ($request->level === 'product') {
            $rules['product_id'] = 'required|exists:products,id';
        } elseif ($request->level === 'variation') {
            $rules['product_id']      = 'required|exists:products,id';
            $rules['variation_ids']   = 'required|array|min:1';
            $rules['variation_ids.*'] = 'exists:product_variations,id';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            toast($validator->errors()->first(), 'error');
            return back()->withInput();
        }

        $discount->update([
            'name'                => $request->name,
            'level'               => $request->level,
            'category_id'         => $request->level === 'category' ? $request->category_id : null,
            'product_id'          => in_array($request->level, ['product', 'variation']) ? $request->product_id : null,
            'variation_ids'       => $request->level === 'variation' ? $request->variation_ids : null,
            'discount_percentage' => $request->discount_percentage,
            'start_date'          => $request->start_date,
            'end_date'            => $request->end_date,
            'active_status'       => $request->has('active_status') ? 1 : 0,
            'updated_by'          => auth()->id(),
        ]);

        toast('Discount updated successfully!', 'success');
        return redirect()->route('discounts.index');
    }

    public function destroy($id)
    {
        Discount::findOrFail($id)->delete();
        toast('Discount deleted successfully!', 'success');
        return back();
    }

    // ──────────────────────────────────────────────
    // AJAX Helpers
    // ──────────────────────────────────────────────

    /**
     * Return products for a given category (for the discount form AJAX cascade).
     */
    public function getProducts($categoryId)
    {
        $products = Product::where('category_id', $categoryId)
            ->where('active_status', 1)
            ->select('id', 'name')
            ->get();

        return response()->json($products);
    }

    /**
     * Return variations (SKUs) for a given product.
     */
    public function getVariations($productId)
    {
        $variations = ProductVariation::where('product_id', $productId)
            ->where('active_status', 1)
            ->with(['size', 'color'])
            ->get()
            ->map(function ($v) {
                $label = $v->sku;
                if ($v->size) $label .= ' — ' . $v->size->name;
                if ($v->color) $label .= ' / ' . $v->color->name;
                return ['id' => $v->id, 'sku' => $label];
            });

        return response()->json($variations);
    }
}
