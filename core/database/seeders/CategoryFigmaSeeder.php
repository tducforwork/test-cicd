<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryFigmaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Delete all existing categories and relations
        DB::table('products_categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Category::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Delete all category keys to prevent duplicate slug error
        DB::table('keys')->where('type', 'category')->delete();

        $categories = [
            ['name' => 'Thực phẩm', 'bg_color' => '#f0fdf4', 'icon' => 'fa-solid fa-utensils', 'icon_color' => '#22c55e'],
            ['name' => 'Máy móc', 'bg_color' => '#f1f5f9', 'icon' => 'fa-solid fa-gears', 'icon_color' => '#64748b'],
            ['name' => 'Thời trang', 'bg_color' => '#e0f2fe', 'icon' => 'fa-solid fa-shirt', 'icon_color' => '#0ea5e9'],
            ['name' => 'Điện gia dụng', 'bg_color' => '#fef2f2', 'icon' => 'fa-solid fa-blender', 'icon_color' => '#ef4444'],
            ['name' => 'Điện thoại', 'bg_color' => '#f5f3ff', 'icon' => 'fa-solid fa-mobile-screen-button', 'icon_color' => '#8b5cf6'],
            ['name' => 'Sức khỏe', 'bg_color' => '#fff1f2', 'icon' => 'fa-solid fa-heart-pulse', 'icon_color' => '#f43f5e'],
            ['name' => 'Làm đẹp', 'bg_color' => '#fdf2f8', 'icon' => 'fa-solid fa-wand-magic-sparkles', 'icon_color' => '#ec4899'],
            ['name' => 'Mẹ & Bé', 'bg_color' => '#fff7ed', 'icon' => 'fa-solid fa-baby', 'icon_color' => '#f97316'],
            ['name' => 'Công nghiệp', 'bg_color' => '#ecfeff', 'icon' => 'fa-solid fa-industry', 'icon_color' => '#0891b2'],
            ['name' => 'Tiêu dùng', 'bg_color' => '#f0f9ff', 'icon' => 'fa-solid fa-cart-shopping', 'icon_color' => '#0ea5e9'],
            ['name' => 'Nội thất', 'bg_color' => '#fefce8', 'icon' => 'fa-solid fa-couch', 'icon_color' => '#ca8a04'],
            ['name' => 'Thể thao', 'bg_color' => '#f5f3ff', 'icon' => 'fa-solid fa-volleyball', 'icon_color' => '#8b5cf6'],
            ['name' => 'Đồng hồ', 'bg_color' => '#fafaf9', 'icon' => 'fa-solid fa-clock', 'icon_color' => '#78716c'],
            ['name' => 'Túi xách', 'bg_color' => '#fff1f2', 'icon' => 'fa-solid fa-bag-shopping', 'icon_color' => '#f43f5e'],
            ['name' => 'Du lịch', 'bg_color' => '#eff6ff', 'icon' => 'fa-solid fa-plane', 'icon_color' => '#3b82f6'],
            ['name' => 'Sách', 'bg_color' => '#eff6ff', 'icon' => 'fa-solid fa-book', 'icon_color' => '#3b82f6'],
            ['name' => 'Thú cưng', 'bg_color' => '#fdf2f8', 'icon' => 'fa-solid fa-paw', 'icon_color' => '#ec4899'],
            ['name' => 'Nhà bếp', 'bg_color' => '#fff7ed', 'icon' => 'fa-solid fa-fire-burner', 'icon_color' => '#f97316'],
            ['name' => 'Văn phòng', 'bg_color' => '#f0fdfa', 'icon' => 'fa-solid fa-briefcase', 'icon_color' => '#14b8a6'],
            ['name' => 'Sách & VP', 'bg_color' => '#fff7ed', 'icon' => 'fa-solid fa-book', 'icon_color' => '#f97316'],
            ['name' => 'Văn phòng phẩm', 'bg_color' => '#f0fdf4', 'icon' => 'fa-solid fa-pen-nib', 'icon_color' => '#22c55e'],
            ['name' => 'Linh kiện PC', 'bg_color' => '#eff6ff', 'icon' => 'fa-solid fa-microchip', 'icon_color' => '#3b82f6'],
            ['name' => 'Phụ kiện Game', 'bg_color' => '#fdf2f8', 'icon' => 'fa-solid fa-gamepad', 'icon_color' => '#ec4899'],
            ['name' => 'Giày dép', 'bg_color' => '#fafaf9', 'icon' => 'fa-solid fa-shoe-prints', 'icon_color' => '#78716c'],
        ];

        foreach ($categories as $index => $cat) {
            Category::create([
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                'icon' => $cat['icon'],
                'icon_color' => $cat['icon_color'],
                'bg_color' => $cat['bg_color'],
                'is_top' => 1,
                'is_special' => 0,
                'show_on_home' => 1,
            ]);
        }
    }
}
