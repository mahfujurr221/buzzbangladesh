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
        Schema::create('flash_modals', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150)->nullable();
            $table->string('image')->comment('Path to the banner image');
            $table->string('link')->nullable()->comment('URL when clicked');
            $table->datetime('start_date')->comment('When modal becomes active');
            $table->datetime('end_date')->comment('When modal expires');
            $table->integer('delay_seconds')->default(3)->comment('Seconds to wait before showing');
            $table->boolean('active_status')->default(true)->index();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flash_modals');
    }
};
