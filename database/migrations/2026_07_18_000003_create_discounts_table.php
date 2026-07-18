<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->comment('Descriptive label e.g. Eid Mega Sale 2026');
            $table->enum('level', ['category', 'product', 'variation'])
                  ->default('product')
                  ->index()
                  ->comment('Hierarchy level: category → all products → all variations; product → all its variations; variation → single SKU');
            $table->unsignedBigInteger('category_id')->nullable()->index()->comment('Set when level = category');
            $table->unsignedBigInteger('product_id')->nullable()->index()->comment('Set when level = product or variation');
            $table->json('variation_ids')->nullable()->comment('Array of variation IDs when level = variation');
            $table->decimal('discount_percentage', 5, 2)->comment('e.g. 15.00 means 15% off');
            $table->date('start_date')->comment('Session start — required');
            $table->date('end_date')->comment('Session end — required');
            $table->boolean('active_status')->default(true)->index();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
