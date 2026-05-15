<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySlugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = \App\Models\Category::all();
        foreach ($categories as $category) {
            $category->slug = \Illuminate\Support\Str::slug($category->name);
            $category->save();
        }
        echo "Updated " . $categories->count() . " categories with slugs.\n";
    }
}
