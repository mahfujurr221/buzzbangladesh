<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique();
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('order_status_id')->constrained('order_statuses');
            $table->decimal('total_amount', 12, 2);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('total_purchase_cost', 12, 2)->default(0);
            $table->decimal('net_profit', 12, 2)->default(0);
            $table->string('city', 100)->nullable();
            $table->string('thana', 100)->nullable();
            $table->text('shipping_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
