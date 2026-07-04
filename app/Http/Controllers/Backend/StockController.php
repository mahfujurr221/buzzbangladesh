<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\StockLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function __construct()
    {
        // Add middleware if needed, e.g.
        // $this->middleware('can:manage-stock');
    }

    public function index()
    {
        $products = Product::with('variations')->latest()->paginate(20);
        return view('backend.pages.stock.index', compact('products'));
    }

    public function manage($id)
    {
        $product = Product::with(['variations.size', 'variations.color'])->findOrFail($id);
        return view('backend.pages.stock.manage', compact('product'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'variations' => 'required|array',
            'variations.*.id' => 'required|exists:product_variations,id',
            'variations.*.add_quantity' => 'nullable|integer|not_in:0',
            'variations.*.purchase_price' => 'nullable|numeric|min:0',
            'variations.*.sale_price' => 'nullable|numeric|min:0',
            'note' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($id);
            $hasEntries = false;

            foreach ($request->variations as $varData) {
                $variation = ProductVariation::where('id', $varData['id'])->where('product_id', $product->id)->firstOrFail();
                
                $addQty = !empty($varData['add_quantity']) ? (int)$varData['add_quantity'] : 0;
                $newPurchasePrice = isset($varData['purchase_price']) ? (float)$varData['purchase_price'] : (float)$variation->purchase_price;
                $newSalePrice = isset($varData['sale_price']) ? (float)$varData['sale_price'] : (float)$variation->sale_price;

                $priceChanged = ($newPurchasePrice != (float)$variation->purchase_price) || ($newSalePrice != (float)$variation->sale_price);

                if ($addQty !== 0 || $priceChanged) {
                    $hasEntries = true;

                    if ($addQty !== 0) {
                        if ($variation->stock_quantity + $addQty < 0) {
                            throw new \Exception("Stock for SKU {$variation->sku} cannot drop below zero.");
                        }

                        // Create Ledger Entry
                        StockLedger::create([
                            'product_id' => $product->id,
                            'product_variation_id' => $variation->id,
                            'quantity_added' => $addQty,
                            'purchase_price' => $newPurchasePrice,
                            'note' => $request->note,
                            'created_by' => auth()->id(),
                        ]);
                        
                        $variation->stock_quantity += $addQty;
                    }

                    $variation->purchase_price = $newPurchasePrice;
                    $variation->sale_price = $newSalePrice;
                    $variation->save();
                }
            }

            if (!$hasEntries) {
                return back()->with('warning', 'No stock or price changes were detected.');
            }

            DB::commit();
            toast('Stock updated successfully!', 'success');
            return redirect()->route('stocks.index');

        } catch (\Exception $e) {
            DB::rollBack();
            toast($e->getMessage(), 'error');
            return back()->withInput();
        }
    }

    public function ledger()
    {
        $ledgers = StockLedger::with(['product', 'variation.size', 'variation.color', 'creator'])->latest()->paginate(50);
        return view('backend.pages.stock.ledger', compact('ledgers'));
    }
}
