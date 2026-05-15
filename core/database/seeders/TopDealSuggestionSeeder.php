<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class TopDealSuggestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Lấy tất cả sản phẩm
        $products = Product::all();
        
        foreach ($products as $product) {
            // Random gán Top Deal (khoảng 50% sản phẩm)
            if (rand(1, 100) <= 50) {
                $product->is_topdeal = 1;
            } else {
                $product->is_topdeal = 0;
            }
            
            // Random gán Gợi ý (khoảng 90% sản phẩm vì đây là grid lớn)
            if (rand(1, 100) <= 90) {
                $product->is_suggestion = 1;
            } else {
                $product->is_suggestion = 0;
            }
            
            $product->save();
        }

        echo "Đã gán random trạng thái Top Deal cho " . Product::where('is_topdeal', 1)->count() . " sản phẩm!\n";
        echo "Đã gán random trạng thái Gợi ý cho " . Product::where('is_suggestion', 1)->count() . " sản phẩm!\n";
    }
}
