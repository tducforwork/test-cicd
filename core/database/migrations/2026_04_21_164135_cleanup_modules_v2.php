<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        // Drop tables if they exist
        $tables = [
            'jobs',
            'job_applications',
            'job_images',
            'job_industries',
            'job_levels',
            'real_estate_configs',
            'provinces',
            'wards',
            'property_types',
            'transaction_types',
            'transaction_methods',
            'floor_categories',
            'listing_conditions'
        ];

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }

        // Remove columns from products table
        Schema::table('products', function (Blueprint $table) {
            $columns = [
                're_province_id',
                're_ward_id',
                're_address',
                're_type',
                're_rent_period',
                're_bedrooms',
                're_bathrooms',
                're_floor',
                're_area',
                're_project_name',
                're_latitude',
                're_longitude',
                're_map_embed',
                're_property_type',
                're_transaction_type',
                're_transaction_method',
                're_listing_condition',
                'is_real_estate',
                'extra_descriptions'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('products', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        // Remove columns from users table
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'province_id')) {
                 $table->dropForeign(['province_id']);
                 $table->dropColumn('province_id');
            }
            if (Schema::hasColumn('users', 'ward_id')) {
                 $table->dropForeign(['ward_id']);
                 $table->dropColumn('ward_id');
            }
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No rollback needed for a cleanup migration
    }
};
