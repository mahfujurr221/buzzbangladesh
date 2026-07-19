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
        Schema::create('setting_website', function (Blueprint $table) {
            $table->id();
            // Essential Website Info (Compacted for High Traffic)
            $table->string('site_name', 50)->nullable()->index();
            $table->string('site_title', 100)->nullable();
            $table->string('site_title_bn', 150)->nullable();
            $table->string('favicon', 150)->nullable();
            $table->string('logo', 150)->nullable();
            $table->text('address')->nullable();
            $table->text('address_bn')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 100)->nullable();
            
            // Social Media Identifiers & Links
            $table->string('facebook', 200)->nullable();
            $table->string('twitter', 200)->nullable();
            $table->string('instagram', 200)->nullable();
            $table->string('youtube', 200)->nullable();
            $table->string('linkedin', 200)->nullable();
            $table->string('pinterest', 200)->nullable();
            $table->string('whatsapp_number', 20)->nullable();
            
            // Layout & Content Display
            $table->string('footer_text', 200)->nullable();
            $table->string('footer_text_bn', 250)->nullable();
            $table->string('newsletter_text', 200)->nullable();
            $table->string('newsletter_text_bn', 250)->nullable();
            $table->text('headline')->nullable();
            $table->text('headline_bn')->nullable();
            $table->text('google_map')->nullable();
            
            // High-Performance SEO & Discovery
            $table->string('meta_title', 100)->nullable();
            $table->string('meta_title_bn', 150)->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_description_bn')->nullable();
            $table->json('meta_keywords')->nullable();
            $table->json('meta_keywords_bn')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_website');
    }
};
