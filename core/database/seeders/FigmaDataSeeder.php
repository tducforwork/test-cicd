<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FigmaDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Random dữ liệu cho Categories
        $icons = ['fa-solid fa-utensils', 'fa-solid fa-gears', 'fa-solid fa-shirt', 'fa-solid fa-blender', 'fa-solid fa-mobile-screen-button', 'fa-solid fa-heart-pulse', 'fa-solid fa-wand-magic-sparkles', 'fa-solid fa-baby', 'fa-solid fa-industry', 'fa-solid fa-cart-shopping', 'fa-solid fa-couch', 'fa-solid fa-volleyball', 'fa-solid fa-clock', 'fa-solid fa-bag-shopping', 'fa-solid fa-plane', 'fa-solid fa-book', 'fa-solid fa-paw', 'fa-solid fa-fire-burner', 'fa-solid fa-briefcase', 'fa-solid fa-pen-nib', 'fa-solid fa-microchip', 'fa-solid fa-gamepad', 'fa-solid fa-shoe-prints'];
        $colors = ['#22c55e', '#64748b', '#0ea5e9', '#ef4444', '#8b5cf6', '#f43f5e', '#ec4899', '#f97316', '#0891b2', '#ca8a04', '#78716c', '#3b82f6', '#14b8a6'];
        $bgColors = ['#f0fdf4', '#f1f5f9', '#e0f2fe', '#fef2f2', '#f5f3ff', '#fff1f2', '#fdf2f8', '#fff7ed', '#ecfeff', '#f0f9ff', '#fefce8', '#fafaf9', '#eff6ff', '#f0fdfa'];

        $categories = \App\Models\Category::all();
        foreach ($categories as $category) {
            $category->update([
                'icon' => $icons[array_rand($icons)],
                'icon_color' => $colors[array_rand($colors)],
                'bg_color' => $bgColors[array_rand($bgColors)],
            ]);
        }

        // Random dữ liệu cho Products
        $products = \App\Models\Product::all();
        foreach ($products as $product) {
            $product->update([
                'is_search' => rand(0, 1),
                'is_topdeal' => rand(0, 1),
                'is_suggestion' => rand(0, 1),
            ]);
        }
    }
}
