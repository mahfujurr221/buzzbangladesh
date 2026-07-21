<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSize;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::with('images')->findOrFail($request->product_id);

        $colorId = $request->color_id ?? null;
        $sizeId  = $request->size_id  ?? null;

        $color = $colorId ? ProductColor::find($colorId) : null;
        $size  = $sizeId  ? ProductSize::find($sizeId)   : null;

        // Cart key is unique per product + color + size combination
        $cartKey = $product->id . '-' . ($colorId ?? '0') . '-' . ($sizeId ?? '0');

        $cart = session()->get('cart', []);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $request->quantity;
        } else {
            $image = $product->images->first();

            // Determine the sale price from the product (variation price may override this)
            $salePrice     = $product->sale_price     ?? $product->purchase_price ?? 0;
            $purchasePrice = $product->purchase_price ?? 0;

            $cart[$cartKey] = [
                'id'             => $product->id,
                'name'           => $product->name,
                'slug'           => $product->slug ?? $product->id,
                'quantity'       => $request->quantity,
                'price'          => (float) $salePrice,
                'purchase_price' => (float) $purchasePrice,
                'color_id'       => $colorId,
                'color_name'     => $color ? $color->name : null,
                'size_id'        => $sizeId,
                'size_name'      => $size  ? $size->name  : null,
                'image'          => $image ? $image->image_path : null,
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'status'     => 'success',
            'message'    => 'Product added to cart successfully!',
            'cart_count' => $this->getCartCount(),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'cart_key' => 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$request->cart_key])) {
            $cart[$request->cart_key]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            return response()->json(['status' => 'success', 'cart_count' => $this->getCartCount()]);
        }

        return response()->json(['status' => 'error', 'message' => 'Item not found in cart.'], 404);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'cart_key' => 'required',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$request->cart_key])) {
            unset($cart[$request->cart_key]);
            session()->put('cart', $cart);
            return response()->json([
                'status'     => 'success',
                'cart_count' => $this->getCartCount(),
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Item not found in cart.'], 404);
    }

    /**
     * Render the cart HTML partial for the side cart modal.
     */
    public function render()
    {
        $cart  = session()->get('cart', []);
        $total = $this->calculateTotal($cart);

        $html = view('frontend.partials.cart-items', compact('cart', 'total'))->render();

        return response()->json([
            'html'  => $html,
            'total' => $total,
            'count' => $this->getCartCount(),
        ]);
    }

    /**
     * Return cart data as JSON for the checkout page.
     */
    public function getCartData()
    {
        $cart  = session()->get('cart', []);
        $total = $this->calculateTotal($cart);

        $items = [];
        foreach ($cart as $key => $item) {
            $imageUrl = $item['image']
                ? asset($item['image'])
                : asset('backend/images/products/placeholder.png');

            $items[] = [
                'key'        => $key,
                'id'         => $item['id'],
                'name'       => $item['name'],
                'slug'       => $item['slug'],
                'quantity'   => $item['quantity'],
                'price'      => $item['price'],
                'color_name' => $item['color_name'] ?? null,
                'size_name'  => $item['size_name']  ?? null,
                'image'      => $imageUrl,
                'subtotal'   => $item['price'] * $item['quantity'],
            ];
        }

        return response()->json([
            'items' => $items,
            'total' => $total,
            'count' => count($items),
        ]);
    }

    public function getCount()
    {
        return response()->json(['count' => $this->getCartCount()]);
    }

    private function getCartCount(): int
    {
        return count(session()->get('cart', []));
    }

    private function calculateTotal(array $cart): float
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
}
