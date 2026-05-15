<?php

namespace Database\Seeders;

use App\Models\MenuGroup;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run()
    {
        $topBar = MenuGroup::updateOrCreate(
            ['location' => 'top_bar'],
            ['name' => 'Top Bar Menu', 'status' => 1]
        );

        $items = [
            ['title' => 'Hướng dẫn mua hàng', 'url' => '#', 'order' => 1],
            ['title' => 'Chính sách giao hàng', 'url' => '#', 'order' => 2],
        ];

        foreach ($items as $item) {
            MenuItem::updateOrCreate(
                ['menu_group_id' => $topBar->id, 'title' => $item['title']],
                ['url' => $item['url'], 'order' => $item['order'], 'status' => 1, 'parent_id' => 0]
            );
        }
    }
}
