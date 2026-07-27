<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('settings');
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            // essential settings (Compacted for High Performance)
            $table->string('site_name', 50)->nullable();
            $table->string('site_title', 100)->nullable();
            $table->string('site_title_bn', 150)->nullable();
            $table->string('top_bar_text')->nullable();
            $table->string('favicon', 150)->nullable();
            $table->string('logo', 150)->nullable();
            $table->text('address')->nullable();
            $table->text('address_bn')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 100)->nullable();

            // pos settings
            $table->string('currency_name', 50)->default('Taka');
            $table->string('currency_symbol', 10)->default('৳');
            $table->string('currency_code', 10)->default('BDT');
            $table->enum('currency_position', ['prefix', 'suffix'])->default('prefix');
            $table->enum('pos_receipt_type', ['a4', 'pos'])->default('pos');
            $table->enum('purchase_receipt_type', ['a4', 'pos'])->default('a4');
            $table->enum('payment_receipt_type', ['a4', 'pos'])->default('pos');
            $table->enum('invoice_view_type', ['logo_only', 'text_only', 'both'])->default('both');
            $table->integer('low_stock_limit')->default(10);
            $table->decimal('default_vat', 8, 2)->default(5.00);
            $table->boolean('dark_mode')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
