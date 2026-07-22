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
        Schema::table('setting_website', function (Blueprint $table) {
            $table->string('shop_bg')->nullable()->after('promo_banner_2_link');
            $table->string('about_bg')->nullable()->after('shop_bg');
            $table->string('contact_bg')->nullable()->after('about_bg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('setting_website', function (Blueprint $table) {
            $table->dropColumn(['shop_bg', 'about_bg', 'contact_bg']);
        });
    }
};
