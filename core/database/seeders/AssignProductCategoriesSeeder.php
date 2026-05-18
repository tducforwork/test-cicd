<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AssignProductCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Lấy danh sách ảnh từ thư mục (loại bỏ ảnh bắt đầu bằng thumb_)
        $imagePath = public_path('assets/images/product');
        $allImages = glob($imagePath . '/*.*');
        $validImages = [];
        foreach ($allImages as $img) {
            $filename = basename($img);
            if (!str_starts_with($filename, 'thumb_')) {
                $validImages[] = $filename;
            }
        }
        
        if (empty($validImages)) {
            $validImages = ['default.png']; // Fallback
        }

        // 2. Chỉnh lại sản phẩm cũ: mỗi sản phẩm chỉ có ĐÚNG 1 danh mục
        $products = Product::all();
        $categories = Category::all();
        $categoryIds = $categories->pluck('id')->toArray();

        if (empty($categoryIds)) {
            $this->command->info('No categories found. Please run CategoryFigmaSeeder first.');
            return;
        }

        DB::table('products_categories')->truncate();
        
        // Randomly assign 1 category to each existing product
        foreach ($products as $product) {
            $randomCatId = $categoryIds[array_rand($categoryIds)];
            $product->categories()->attach($randomCatId);
        }

        $brands = \App\Models\Brand::pluck('id')->toArray();
        $productTypes = \App\Models\ProductType::pluck('id')->toArray();
        $tags = \App\Models\Tag::pluck('id')->toArray();

        // 3. Fill để mỗi danh mục đều có ít nhất 5-8 sản phẩm
        foreach ($categories as $category) {
            $currentCount = DB::table('products_categories')->where('category_id', $category->id)->count();
            $targetCount = rand(5, 8);
            
            if ($currentCount < $targetCount) {
                $needed = $targetCount - $currentCount;
                
                for ($i = 0; $i < $needed; $i++) {
                    $randomName = 'Sản phẩm ' . $category->name . ' ' . Str::random(5);
                    $basePrice = rand(100, 500) * 1000;
                    $flashPercentage = rand(10, 90);
                    
                    $newProduct = Product::create([
                        'name' => $randomName,
                        'slug' => Str::slug($randomName),
                        'sku' => strtoupper(Str::random(8)),
                        'base_price' => $basePrice,
                        'discount_price' => $basePrice * 0.9,
                        'description' => 'Mô tả chi tiết cho ' . $randomName,
                        'summary' => 'Mô tả ngắn gọn cho ' . $randomName,
                        'status' => 1,
                        'show_in_frontend' => 1,
                        'main_image' => $validImages[array_rand($validImages)],
                        'seller_id' => 0, // Admin product
                        'is_search' => rand(0, 1),
                        'is_topdeal' => rand(0, 1),
                        'is_suggestion' => rand(0, 1),
                        'brand_id' => !empty($brands) ? $brands[array_rand($brands)] : 0,
                        'flash_percentage' => $flashPercentage,
                        'flash_text' => "Đã bán {$flashPercentage}%"
                    ]);
                    
                    // Attach category
                    $newProduct->categories()->attach($category->id);
                    
                    // Attach ProductType
                    if (!empty($productTypes)) {
                        $newProduct->productType()->attach($productTypes[array_rand($productTypes)]);
                    }
                    
                    // Attach Tag
                    if (!empty($tags)) {
                        $newProduct->tags()->attach($tags[array_rand($tags)]);
                    }
                    
                    // Add stock 10-20
                    \App\Models\ProductStock::create([
                        'product_id' => $newProduct->id,
                        'quantity' => rand(10, 20)
                    ]);
                    
                    // Add 2nd image
                    \App\Models\ProductImage::create([
                        'product_id' => $newProduct->id,
                        'image' => $validImages[array_rand($validImages)]
                    ]);
                }
            }
        }
        
        $this->command->info('Updated products and filled categories to have 5-8 products each.');
    }
}
