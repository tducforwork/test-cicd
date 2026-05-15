<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RealEstateConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configs = [
            ['type' => 'direction', 'name' => 'Đông'],
            ['type' => 'direction', 'name' => 'Tây'],
            ['type' => 'direction', 'name' => 'Nam'],
            ['type' => 'direction', 'name' => 'Bắc'],
            ['type' => 'direction', 'name' => 'Đông Bắc'],
            ['type' => 'direction', 'name' => 'Đông Nam'],
            ['type' => 'direction', 'name' => 'Tây Bắc'],
            ['type' => 'direction', 'name' => 'Tây Nam'],

            ['type' => 'legal_status', 'name' => 'Sổ đỏ/ Sổ hồng'],
            ['type' => 'legal_status', 'name' => 'Giấy tờ hợp lệ'],
            ['type' => 'legal_status', 'name' => 'Đang chờ sổ'],
            ['type' => 'legal_status', 'name' => 'Hợp đồng mua bán'],

            ['type' => 'property_type', 'name' => 'Căn hộ'],
            ['type' => 'property_type', 'name' => 'Biệt thự'],
            ['type' => 'property_type', 'name' => 'Đất nền'],
            ['type' => 'property_type', 'name' => 'Văn phòng'],
            ['type' => 'property_type', 'name' => 'Phòng trọ'],
            ['type' => 'property_type', 'name' => 'Shophouse'],

            ['type' => 'transaction_type', 'name' => 'Thuê theo tháng'],
            ['type' => 'transaction_type', 'name' => 'Thuê trọn gói'],
            ['type' => 'transaction_type', 'name' => 'Giá tốt'],
            ['type' => 'transaction_type', 'name' => 'Ngắn hạn'],

            ['type' => 'transaction_method', 'name' => 'Nguyên căn'],
            ['type' => 'transaction_method', 'name' => 'Tin rao bán trực tiếp'],
            ['type' => 'transaction_method', 'name' => 'Tin rao từ môi giới'],

            ['type' => 'floor_category', 'name' => 'Bán hầm'],
            ['type' => 'floor_category', 'name' => 'Tầng 1'],
            ['type' => 'floor_category', 'name' => 'Tầng 2 - 5'],
            ['type' => 'floor_category', 'name' => 'Tầng 6 - 9'],
            ['type' => 'floor_category', 'name' => 'Tầng 10 trở lên'],

            ['type' => 'listing_condition', 'name' => 'Có chỗ để xe'],
            ['type' => 'listing_condition', 'name' => 'Thang máy'],
            ['type' => 'listing_condition', 'name' => 'Sân thượng'],
            ['type' => 'listing_condition', 'name' => 'Duplex / Thông tầng'],
            ['type' => 'listing_condition', 'name' => 'Hồ bơi'],
        ];

        foreach ($configs as $config) {
            \App\Models\RealEstateConfig::updateOrCreate(
                ['type' => $config['type'], 'name' => $config['name']],
                ['status' => 1]
            );
        }

        // Provinces & Wards
        $provinces = ['Hà Nội', 'TP. Hồ Chí Minh', 'Đà Nẵng'];
        foreach ($provinces as $pName) {
            $province = \App\Models\RealEstateConfig::updateOrCreate(
                ['type' => 'province', 'name' => $pName],
                ['status' => 1]
            );

            if ($pName == 'Hà Nội') {
                $wards = ['Quận Ba Đình', 'Quận Hoàn Kiếm', 'Quận Tây Hồ'];
            } elseif ($pName == 'TP. Hồ Chí Minh') {
                $wards = ['Quận 1', 'Quận 3', 'Quận 7'];
            } else {
                $wards = ['Quận Hải Châu', 'Quận Thanh Khê'];
            }

            foreach ($wards as $wName) {
                \App\Models\RealEstateConfig::updateOrCreate(
                    ['type' => 'ward', 'name' => $wName, 'parent_id' => $province->id],
                    ['status' => 1]
                );
            }
        }
    }
}
