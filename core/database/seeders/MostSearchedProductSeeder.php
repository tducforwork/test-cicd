<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class MostSearchedProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Lấy tất cả sản phẩm và gán is_search = 1 cho khoảng 80% sản phẩm để dữ liệu demo phong phú
        $products = Product::all();
        foreach ($products as $product) {
            if (rand(1, 100) <= 80) {
                $product->is_search = 1;
                $product->save();
            }
        }

        echo "Đã gán random trạng thái 'Tìm kiếm nhiều nhất' cho " . Product::where('is_search', 1)->count() . " sản phẩm!\n";
    }
}
