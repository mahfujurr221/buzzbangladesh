<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;
use App\Models\ProductSize;
use App\Models\ProductColor;
use App\Models\ProductImage;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:list-product', ['only' => ['index']]);
        $this->middleware('can:create-product', ['only' => ['create', 'store']]);
        $this->middleware('can:edit-product', ['only' => ['edit', 'update']]);
        $this->middleware('can:delete-product', ['only' => ['destroy']]);
    }

    public function index()
    {
        $products = Product::with(['category', 'brand', 'images' => function($q) {
            $q->where('is_main', 1);
        }])->latest()->paginate(20);
        
        return view('backend.pages.product.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('active_status', 1)->get();
        $brands = Brand::where('active_status', 1)->get();
        $sizes = ProductSize::where('active_status', 1)->get();
        $colors = ProductColor::where('active_status', 1)->get();

        return view('backend.pages.product.create', compact('categories', 'brands', 'sizes', 'colors'));
    }

    public function getSubcategories($categoryId)
    {
        $subcategories = SubCategory::where('category_id', $categoryId)->where('active_status', 1)->get();
        return response()->json($subcategories);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'sale_price' => 'required|numeric|min:0',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
            'variations' => 'nullable|array',
            'variations.*.sku' => 'required_with:variations|string|distinct|unique:product_variations,sku',
        ];

        if (!$request->has('variations')) {
            $rules['sku'] = 'required|string|unique:product_variations,sku';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            toast($validator->errors()->first(), 'error');
            return back()->withInput();
        }

        $uploadedFiles = [];

        try {
            DB::beginTransaction();

            $slug = Str::slug($request->name);
            // Ensure unique slug
            $count = Product::where('slug', 'LIKE', "{$slug}%")->count();
            if ($count > 0) {
                $slug = $slug . '-' . ($count + 1);
            }

            $product = new Product();
            $product->category_id = $request->category_id;
            $product->sub_category_id = $request->sub_category_id;
            $product->brand_id = $request->brand_id;
            $product->name = $request->name;
            $product->slug = $slug;
            $product->short_description = $request->short_description;
            $product->description = $request->description;
            $product->purchase_price = $request->purchase_price;
            $product->sale_price = $request->sale_price;
            $product->seo_title = $request->seo_title;
            $product->seo_description = $request->seo_description;
            $product->seo_tags = $request->seo_tags;
            $product->active_status = $request->has('active_status') ? 1 : 0;
            $product->created_by = auth()->id();
            $product->save();

            // Handle Images
            if ($request->hasFile('images')) {
                $sortOrder = json_decode($request->image_sort_order, true) ?? [];
                foreach ($request->file('images') as $file) {
                    $originalName = $file->getClientOriginalName();
                    
                    $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('backend/images/products'), $filename);
                    $uploadedFiles[] = public_path('backend/images/products/' . $filename);
                    
                    $isMain = ($request->main_image_name == $originalName) ? 1 : 0;
                    $colorId = $request->image_colors[$originalName] ?? null;
                    $orderIndex = array_search($originalName, $sortOrder);

                    ProductImage::create([
                        'product_id' => $product->id,
                        'product_color_id' => $colorId,
                        'image_path' => 'backend/images/products/' . $filename,
                        'is_main' => $isMain,
                        'sort_order' => $orderIndex !== false ? $orderIndex : 0,
                    ]);
                }
            }

            // Handle Variations
            if ($request->has('variations')) {
                foreach ($request->variations as $var) {
                    if (!empty($var['sku'])) {
                        ProductVariation::create([
                            'product_id' => $product->id,
                            'product_size_id' => $var['size_id'] ?? null,
                            'product_color_id' => $var['color_id'] ?? null,
                            'sku' => $var['sku'],
                            'sale_price' => $var['price'] ?? $product->sale_price,
                            'purchase_price' => $product->purchase_price,
                            'stock_quantity' => $var['stock'] ?? 0,
                            'active_status' => 1,
                            'created_by' => auth()->id(),
                        ]);
                    }
                }
            } else {
                // Create a default variation if no variations exist
                ProductVariation::create([
                    'product_id' => $product->id,
                    'sku' => $request->sku,
                    'sale_price' => $product->sale_price,
                    'purchase_price' => $product->purchase_price,
                    'stock_quantity' => $request->stock_quantity ?? 0,
                    'active_status' => 1,
                    'created_by' => auth()->id(),
                ]);
            }

            DB::commit();
            toast('Product created successfully!', 'success');
            return redirect()->route('products.index');

        } catch (\Exception $e) {
            DB::rollBack();
            foreach ($uploadedFiles as $filepath) {
                if (File::exists($filepath)) {
                    File::delete($filepath);
                }
            }
            toast($e->getMessage(), 'error');
            return back()->withInput();
        }
    }

    public function edit($id)
    {
        $product = Product::with(['images', 'variations'])->findOrFail($id);
        $categories = Category::where('active_status', 1)->get();
        $subCategories = SubCategory::where('category_id', $product->category_id)->where('active_status', 1)->get();
        $brands = Brand::where('active_status', 1)->get();
        $sizes = ProductSize::where('active_status', 1)->get();
        $colors = ProductColor::where('active_status', 1)->get();

        return view('backend.pages.product.edit', compact('product', 'categories', 'subCategories', 'brands', 'sizes', 'colors'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'sale_price' => 'required|numeric|min:0',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
            'variations' => 'nullable|array',
        ];

        // Ensure unique SKUs in variations table, ignoring current variant IDs if they are being updated
        if ($request->has('variations')) {
            foreach ($request->variations as $index => $var) {
                $varId = $var['id'] ?? null;
                $rules["variations.{$index}.sku"] = 'required|string|distinct|unique:product_variations,sku' . ($varId ? ",{$varId}" : '');
            }
        } else {
            // For simple product (no variations from frontend matrix)
            $firstVar = ProductVariation::where('product_id', $id)->first();
            $varId = $firstVar ? $firstVar->id : '';
            $rules['sku'] = 'required|string|unique:product_variations,sku' . ($varId ? ",{$varId}" : '');
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            toast($validator->errors()->first(), 'error');
            return back()->withInput();
        }

        $uploadedFiles = [];

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($id);
            
            $slug = Str::slug($request->name);
            if ($product->name != $request->name) {
                $count = Product::where('slug', 'LIKE', "{$slug}%")->where('id', '!=', $id)->count();
                if ($count > 0) {
                    $slug = $slug . '-' . ($count + 1);
                }
            } else {
                $slug = $product->slug;
            }

            $product->category_id = $request->category_id;
            $product->sub_category_id = $request->sub_category_id;
            $product->brand_id = $request->brand_id;
            $product->name = $request->name;
            $product->slug = $slug;
            $product->short_description = $request->short_description;
            $product->description = $request->description;
            $product->purchase_price = $request->purchase_price;
            $product->sale_price = $request->sale_price;
            $product->seo_title = $request->seo_title;
            $product->seo_description = $request->seo_description;
            $product->seo_tags = $request->seo_tags;
            $product->active_status = $request->has('active_status') ? 1 : 0;
            $product->updated_by = auth()->id();
            $product->save();

            // Handle Image Deletions
            if ($request->has('deleted_images')) {
                $deletedImageIds = json_decode($request->deleted_images, true) ?? [];
                foreach ($deletedImageIds as $imgId) {
                    $img = ProductImage::find($imgId);
                    if ($img) {
                        if (File::exists(public_path($img->image_path))) {
                            File::delete(public_path($img->image_path));
                        }
                        $img->delete();
                    }
                }
            }

            // Handle New Images
            if ($request->hasFile('images')) {
                $sortOrder = json_decode($request->image_sort_order, true) ?? [];
                foreach ($request->file('images') as $file) {
                    $originalName = $file->getClientOriginalName();
                    
                    $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('backend/images/products'), $filename);
                    $uploadedFiles[] = public_path('backend/images/products/' . $filename);
                    
                    $isMain = ($request->main_image_name == $originalName) ? 1 : 0;
                    $colorId = $request->image_colors[$originalName] ?? null;
                    $orderIndex = array_search($originalName, $sortOrder);

                    ProductImage::create([
                        'product_id' => $product->id,
                        'product_color_id' => $colorId,
                        'image_path' => 'backend/images/products/' . $filename,
                        'is_main' => $isMain,
                        'sort_order' => $orderIndex !== false ? $orderIndex : 0,
                    ]);
                }
            }
            
            // Update Existing Image Metadata (Main, Colors)
            if ($request->has('existing_images')) {
                ProductImage::where('product_id', $product->id)->update(['is_main' => 0]); // Reset main
                
                foreach ($request->existing_images as $imgId => $data) {
                    $img = ProductImage::find($imgId);
                    if ($img) {
                        $img->product_color_id = $data['color_id'] ?? null;
                        if ($request->main_image_name == 'existing_'.$imgId) {
                            $img->is_main = 1;
                        }
                        $img->save();
                    }
                }
            }

            // Handle Variations
            if ($request->has('variations')) {
                $submittedVarIds = [];
                
                foreach ($request->variations as $var) {
                    if (!empty($var['sku'])) {
                        if (isset($var['id']) && !empty($var['id'])) {
                            // Update existing
                            $variation = ProductVariation::find($var['id']);
                            if ($variation) {
                                $variation->update([
                                    'product_size_id' => $var['size_id'] ?? null,
                                    'product_color_id' => $var['color_id'] ?? null,
                                    'sku' => $var['sku'],
                                    'sale_price' => $var['price'] ?? $product->sale_price,
                                    'stock_quantity' => $var['stock'] ?? 0,
                                    'updated_by' => auth()->id(),
                                ]);
                                $submittedVarIds[] = $variation->id;
                            }
                        } else {
                            // Create new
                            $newVar = ProductVariation::create([
                                'product_id' => $product->id,
                                'product_size_id' => $var['size_id'] ?? null,
                                'product_color_id' => $var['color_id'] ?? null,
                                'sku' => $var['sku'],
                                'sale_price' => $var['price'] ?? $product->sale_price,
                                'purchase_price' => $product->purchase_price,
                                'stock_quantity' => $var['stock'] ?? 0,
                                'active_status' => 1,
                                'created_by' => auth()->id(),
                            ]);
                            $submittedVarIds[] = $newVar->id;
                        }
                    }
                }
                
                // Delete variations that were removed from the matrix
                ProductVariation::where('product_id', $product->id)
                    ->whereNotIn('id', $submittedVarIds)
                    ->delete();
                    
            } else {
                $firstVar = ProductVariation::where('product_id', $product->id)->first();
                if ($firstVar) {
                    $firstVar->update([
                        'sku' => $request->sku,
                        'sale_price' => $product->sale_price,
                        'stock_quantity' => $request->stock_quantity ?? 0,
                    ]);
                    ProductVariation::where('product_id', $product->id)->where('id', '!=', $firstVar->id)->delete();
                } else {
                    ProductVariation::create([
                        'product_id' => $product->id,
                        'sku' => $request->sku,
                        'sale_price' => $product->sale_price,
                        'purchase_price' => $product->purchase_price,
                        'stock_quantity' => $request->stock_quantity ?? 0,
                        'active_status' => 1,
                        'created_by' => auth()->id(),
                    ]);
                }
            }

            DB::commit();
            toast('Product updated successfully!', 'success');
            return redirect()->route('products.index');

        } catch (\Exception $e) {
            DB::rollBack();
            foreach ($uploadedFiles as $filepath) {
                if (File::exists($filepath)) {
                    File::delete($filepath);
                }
            }
            toast($e->getMessage(), 'error');
            return back()->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $product = Product::findOrFail($id);
            
            // Delete images from local storage
            foreach($product->images as $img) {
                if(File::exists(public_path($img->image_path))) {
                    File::delete(public_path($img->image_path));
                }
            }
            
            $product->delete(); // This will cascade delete images and variations in DB
            
            DB::commit();
            toast('Product deleted successfully!', 'success');
            return back();
        } catch (\Exception $e) {
            DB::rollBack();
            toast($e->getMessage(), 'error');
            return back();
        }
    }
}
