<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class FlashSaleProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Đảm bảo có một số Tag mẫu nếu chưa có
        if (Tag::count() == 0) {
            $tags = [
                ['name' => 'Deal sốc', 'type' => 'red'],
                ['name' => 'Freeship', 'type' => 'green'],
                ['name' => 'Bán chạy', 'type' => 'orange'],
                ['name' => 'Mới về', 'type' => 'purple'],
                ['name' => 'Yêu thích', 'type' => 'orange'],
                ['name' => 'Hàng hiệu', 'type' => 'purple'],
            ];

            foreach ($tags as $tag) {
                Tag::create($tag);
            }
        }

        $allTags = Tag::all();

        // 2. Cấu hình random cho tất cả sản phẩm
        Product::chunk(100, function ($products) use ($allTags) {
            foreach ($products as $product) {
                // Gán random 1-2 tag (Ưu tiên 1 tag cho đẹp như user yêu cầu)
                $randomTags = $allTags->random(rand(1, 2))->pluck('id')->toArray();
                $product->tags()->sync($randomTags);

                // Cấu hình Flash Sale bar
                $percentage = rand(20, 95);
                $product->flash_percentage = $percentage;
                $product->flash_text = "Đã bán " . $percentage . "%";
                $product->save();
            }
        });

        echo "Seed Flash Sale data thành công cho " . Product::count() . " sản phẩm!\n";
    }
}
