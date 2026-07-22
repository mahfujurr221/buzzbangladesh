<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug', 120)->nullable()->unique()->after('name');
        });

        // Populate slugs for existing categories
        DB::table('categories')->get()->each(function ($category) {
            $slug = Str::slug($category->name);
            $original = $slug;
            $count = 1;
            while (DB::table('categories')->where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $original . '-' . $count++;
            }
            DB::table('categories')->where('id', $category->id)->update(['slug' => $slug]);
        });

        // Make slug not nullable after population
        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug', 120)->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
