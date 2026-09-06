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
        Schema::create('delivery_consignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->unique()->index();

            // ── Provider ────────────────────────────────────────────────────
            $table->string('provider', 30)->default('steadfast')->index();
            $table->enum('provider_status', [
                'not_dispatched', 'dispatched', 'picked',
                'in_transit', 'delivered', 'cancelled', 'returned'
            ])->default('not_dispatched')->index();

            // ── Courier Consignment IDs (returned by API) ───────────────────
            $table->string('consignment_id', 100)->nullable()->index();
            $table->string('tracking_code', 100)->nullable()->index();

            // ── Parcel Info (sent TO courier API) ──────────────────────────
            $table->string('delivery_type', 20)->default('normal');
            $table->string('item_type', 20)->default('parcel');
            $table->decimal('item_weight', 8, 2)->default(0.50);
            $table->integer('item_quantity')->default(1);
            $table->string('item_description', 255)->nullable();
            $table->string('special_instruction', 255)->nullable();

            // ── COD (Cash on Delivery) ──────────────────────────────────────
            $table->boolean('is_cod')->default(0)->index();
            $table->decimal('amount_to_collect', 14, 2)->default(0);

            // ── Provider-Specific Area/Zone IDs ─────────────────────────────
            $table->unsignedInteger('provider_city_id')->nullable();
            $table->unsignedInteger('provider_zone_id')->nullable();
            $table->unsignedInteger('provider_area_id')->nullable();

            // ── Recipient ───────────────────────────
            $table->string('recipient_name', 100)->nullable();
            $table->string('recipient_phone', 20)->nullable();
            $table->text('recipient_address')->nullable();

            // ── Raw API Responses ───────────────────────────────────────────
            $table->json('api_response')->nullable();
            $table->json('webhook_payload')->nullable();

            // ── Key Timestamps ──────────────────────────────────────────────
            $table->timestamp('dispatched_at')->nullable();
            $table->timestamp('picked_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_consignments');
    }
};
