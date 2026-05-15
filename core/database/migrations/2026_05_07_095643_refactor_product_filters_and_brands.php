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
        // Update brands table
        Schema::table('brands', function (Blueprint $table) {
            if (!Schema::hasColumn('brands', 'slug')) {
                $table->string('slug')->nullable()->after('name');
            }
            if (!Schema::hasColumn('brands', 'icon')) {
                $table->string('icon')->nullable()->after('logo');
            }
        });

        // Create product_types table
        Schema::create('product_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // Create pivot tables
        Schema::create('product_brands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('brand_id')->constrained()->onDelete('cascade');
        });

        Schema::create('product_product_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_type_id')->constrained()->onDelete('cascade');
        });

        // Migrate existing brand data from products table to pivot table
        if (Schema::hasColumn('products', 'brand_id')) {
            $productsWithBrands = DB::table('products')->where('brand_id', '>', 0)->get();
            foreach ($productsWithBrands as $product) {
                DB::table('product_brands')->insert([
                    'product_id' => $product->id,
                    'brand_id' => $product->brand_id,
                ]);
            }
        }

        // Drop old filter tables
        Schema::dropIfExists('product_filter_values');
        Schema::dropIfExists('filter_options');
        Schema::dropIfExists('filter_groups');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_product_types');
        Schema::dropIfExists('product_brands');
        Schema::dropIfExists('product_types');
        
        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn(['slug', 'icon']);
        });

        // Note: Recreating dropped tables is complex and data is lost, so we leave it as is or implement if critical.
    }
};
