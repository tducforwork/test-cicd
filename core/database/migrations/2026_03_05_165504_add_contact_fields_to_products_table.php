<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('re_contact_name')->nullable()->after('re_address');
            $table->string('re_contact_email')->nullable()->after('re_contact_name');
            $table->string('re_contact_phone')->nullable()->after('re_contact_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['re_contact_name', 're_contact_email', 're_contact_phone']);
        });
    }
};
