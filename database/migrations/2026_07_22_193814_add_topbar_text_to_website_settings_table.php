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
            $table->string('top_bar_text')->nullable()->after('site_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('setting_website', function (Blueprint $table) {
            $table->dropColumn('top_bar_text');
        });
    }
};
