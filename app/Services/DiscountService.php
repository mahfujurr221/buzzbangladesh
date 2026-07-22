<?php

namespace App\Services;

use App\Models\Discount;
use App\Models\Product;
use App\Models\ProductVariation;

class DiscountService
{
    /**
     * Get the best active discount for a product+variation combo.
     * Priority: variation > product > category
     */
    public function getActiveDiscount(Product $product, ?ProductVariation $variation = null): ?Discount
    {
        $today = now()->toDateString();

        // 1. Variation-level discount
        if ($variation) {
            $variationDiscount = Discount::active()
                ->where('level', 'variation')
                ->get()
                ->first(function ($d) use ($variation) {
                    return in_array($variation->id, $d->variation_ids ?? []);
                });

            if ($variationDiscount) {
                return $variationDiscount;
            }
        }

        // 2. Product-level discount
        $productDiscount = Discount::active()
            ->where('level', 'product')
            ->where('product_id', $product->id)
            ->first();

        if ($productDiscount) {
            return $productDiscount;
        }

        // 3. Category-level discount
        if ($product->category_id) {
            $categoryDiscount = Discount::active()
                ->where('level', 'category')
                ->where('category_id', $product->category_id)
                ->first();

            if ($categoryDiscount) {
                return $categoryDiscount;
            }
        }

        return null;
    }

    /**
     * Resolve discounted price info for a product+variation.
     * Returns array: [original_price, discounted_price, discount_pct, has_discount]
     */
    public function resolvePrice(Product $product, ?ProductVariation $variation = null): array
    {
        // Base price: variation price takes priority over product price
        if ($variation && $variation->sale_price) {
            $originalPrice = (float) $variation->sale_price;
        } else {
            $originalPrice = (float) ($product->sale_price ?? $product->purchase_price ?? 0);
        }

        $discount = $this->getActiveDiscount($product, $variation);

        if (!$discount) {
            return [
                'original_price'    => $originalPrice,
                'discounted_price'  => $originalPrice,
                'discount_pct'      => 0,
                'has_discount'      => false,
                'discount_id'       => null,
            ];
        }

        $pct = (float) $discount->discount_percentage;
        $discountedPrice = round($originalPrice * (1 - $pct / 100), 2);

        return [
            'original_price'    => $originalPrice,
            'discounted_price'  => $discountedPrice,
            'discount_pct'      => $pct,
            'has_discount'      => true,
            'discount_id'       => $discount->id,
        ];
    }

    /**
     * Check if any discount is currently active (for Hot Deals nav).
     */
    public function hasActiveDeals(): bool
    {
        return Discount::active()->exists();
    }

    /**
     * Get all product IDs that are currently discounted (for Hot Deals filter).
     */
    public function getDiscountedProductIds(): array
    {
        $activeDiscounts = Discount::active()->get();
        $productIds = collect();

        foreach ($activeDiscounts as $discount) {
            if ($discount->level === 'product' && $discount->product_id) {
                $productIds->push($discount->product_id);
            }

            if ($discount->level === 'category' && $discount->category_id) {
                $ids = \App\Models\Product::where('category_id', $discount->category_id)
                    ->where('active_status', 1)
                    ->pluck('id');
                $productIds = $productIds->merge($ids);
            }

            if ($discount->level === 'variation' && !empty($discount->variation_ids)) {
                $ids = \App\Models\ProductVariation::whereIn('id', $discount->variation_ids)
                    ->pluck('product_id');
                $productIds = $productIds->merge($ids);
            }
        }

        return $productIds->unique()->values()->toArray();
    }
}
