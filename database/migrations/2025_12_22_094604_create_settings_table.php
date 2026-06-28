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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->nullable();
            $table->string('site_title')->nullable();
            $table->string('favicon')->nullable();
            $table->string('logo')->nullable();
            $table->string('social_links')->nullable();
            $table->string('address')->nullable();
            $table->string('address2')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('footer_text')->nullable();
            $table->string('newslatter_text')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            $table->string('youtube')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('pinterest')->nullable();
            // google_map
            $table->text('google_map')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('headline')->nullable();
            $table->longText('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();
            $table->longText('terms_and_conditions')->nullable();
            $table->longText('privacy_policy')->nullable();
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
