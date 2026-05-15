<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RemoveSidenavMenuItems extends Command
{
    protected $signature = 'sidenav:cleanup';

    protected $description = 'Remove old real estate config menu items from sidenav';

    public function handle()
    {
        $setting = DB::table('general_settings')->first();
        if (!$setting || !$setting->sidenav) {
            $this->error('Không tìm thấy general_settings');
            return;
        }

        $sidenav = json_decode($setting->sidenav, true);
        if (!is_array($sidenav)) {
            $this->error('Sidenav không hợp lệ');
            return;
        }

        $removed = [];
        $sidenav = array_values(array_map(function ($group) use (&$removed) {
            if (!isset($group['submenu'])) return $group;

            $group['submenu'] = array_values(array_filter($group['submenu'], function ($menu) use (&$removed) {
                if (isset($menu['route_name'])) {
                    if (str_contains($menu['route_name'], 'directions')
                        || str_contains($menu['route_name'], 'legal_statuses')
                        || str_contains($menu['route_name'], 'property_types')
                    ) {
                        $removed[] = $menu['route_name'];
                        return false;
                    }
                }
                return true;
            }));

            return $group;
        }, $sidenav));

        if (empty($removed)) {
            $this->info('Không tìm thấy menu cần xóa');
            return;
        }

        DB::table('general_settings')->update(['sidenav' => json_encode($sidenav)]);
        foreach ($removed as $r) {
            $this->line("  Đã xóa: $r");
        }
        $this->info('Cập nhật sidenav thành công!');
    }
}
