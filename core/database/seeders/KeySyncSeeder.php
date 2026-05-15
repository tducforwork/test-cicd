<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Key;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Database\Seeder;

class KeySyncSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Sync Brands
        $brands = Brand::all();
        foreach ($brands as $brand) {
            $existing = Key::where('slug', $brand->slug)->first();
            if (!$existing) {
                Key::create([
                    'type' => Key::TYPE_BRAND,
                    'key_id' => $brand->id,
                    'slug' => $brand->slug
                ]);
            } elseif ($existing->type == Key::TYPE_BRAND && $existing->key_id == $brand->id) {
                // Already synced
            } else {
                $this->command->warn("Slug '{$brand->slug}' for Brand ID {$brand->id} is already taken by type '{$existing->type}' ID {$existing->key_id}");
            }
        }

        // Sync Product Types
        $types = ProductType::all();
        foreach ($types as $type) {
            $existing = Key::where('slug', $type->slug)->first();
            if (!$existing) {
                Key::create([
                    'type' => Key::TYPE_PRODUCT_TYPE,
                    'key_id' => $type->id,
                    'slug' => $type->slug
                ]);
            } elseif ($existing->type == Key::TYPE_PRODUCT_TYPE && $existing->key_id == $type->id) {
                // Already synced
            } else {
                $this->command->warn("Slug '{$type->slug}' for ProductType ID {$type->id} is already taken by type '{$existing->type}' ID {$existing->key_id}");
            }
        }

        // Sync Categories (in case any missing)
        $categories = Category::all();
        foreach ($categories as $category) {
            $existing = Key::where('slug', $category->slug)->first();
            if (!$existing) {
                Key::create([
                    'type' => Key::TYPE_CATEGORY,
                    'key_id' => $category->id,
                    'slug' => $category->slug
                ]);
            }
        }

        // Sync Products (in case any missing)
        $products = Product::all();
        foreach ($products as $product) {
            $existing = Key::where('slug', $product->slug)->first();
            if (!$existing) {
                Key::create([
                    'type' => Key::TYPE_PRODUCT,
                    'key_id' => $product->id,
                    'slug' => $product->slug
                ]);
            }
        }

        $this->command->info('Key synchronization completed.');
    }
}
