<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add payment method and notes to orders
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method', 50)->default('cod')->after('shipping_address');
            $table->text('notes')->nullable()->after('payment_method');
        });

        // Add product snapshot columns to order_items
        // These snapshot values preserve product info even if the product is later deleted
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('product_id')->nullable()->after('order_id')
                  ->constrained('products')->nullOnDelete();
            $table->string('product_name', 255)->nullable()->after('product_id');
            $table->string('color_name', 100)->nullable()->after('product_name');
            $table->string('size_name', 100)->nullable()->after('color_name');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'notes']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn(['product_id', 'product_name', 'color_name', 'size_name']);
        });
    }
};
