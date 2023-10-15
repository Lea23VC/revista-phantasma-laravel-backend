<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Category;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            //
            $table->string('slug')->after('name')->unique()->nullable();
        });

        $categories = Category::all();

        foreach ($categories as $category) {
            $slug = Str::slug($category->name, '-');
            $count = Category::where('slug', 'like', $slug . '%')->count();
            $category->update(['slug' => $count > 0 ? $slug . '-' . $count : $slug]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            //
            $table->dropColumn('slug');
        });
    }
};
