<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_seller')->default(0)->after('lastname');
            $table->boolean('seller_active')->default(0)->after('is_seller');
            $table->timestamp('seller_activated_at')->nullable()->after('seller_active');
            $table->decimal('balance', 28, 8)->default(0)->after('mobile');
            $table->text('kyc_data')->nullable()->after('address');
            $table->text('kyc_rejection_reason')->nullable()->after('kyc_data');
            $table->tinyInteger('kv')->default(0)->after('kyc_rejection_reason')->comment('0: unverified, 1: pending, 2: verified');
            $table->tinyInteger('featured')->default(0)->after('kv');
        });

        // Migrate Sellers to Users
        $sellers = DB::table('sellers')->get();
        foreach ($sellers as $seller) {
            $existingUser = DB::table('users')->where('email', $seller->email)->first();

            if ($existingUser) {
                // Update existing user with seller data
                DB::table('users')->where('id', $existingUser->id)->update([
                    'is_seller' => 1,
                    'seller_active' => 1,
                    'seller_activated_at' => $seller->created_at,
                    'balance' => $seller->balance,
                    'kyc_data' => $seller->kyc_data,
                    'kyc_rejection_reason' => $seller->kyc_rejection_reason,
                    'kv' => $seller->kv,
                    'featured' => $seller->featured,
                ]);
                $newUserId = $existingUser->id;
            } else {
                // Create new user for this seller
                $newUserId = DB::table('users')->insertGetId([
                    'firstname' => $seller->firstname,
                    'lastname' => $seller->lastname,
                    'username' => $seller->username,
                    'email' => $seller->email,
                    'dial_code' => $seller->dial_code,
                    'mobile' => $seller->mobile,
                    'password' => $seller->password,
                    'country_name' => $seller->country_name,
                    'country_code' => $seller->country_code,
                    'city' => $seller->city,
                    'state' => $seller->state,
                    'zip' => $seller->zip,
                    'address' => $seller->address,
                    'image' => $seller->image,
                    'status' => $seller->status,
                    'ev' => $seller->ev,
                    'sv' => $seller->sv,
                    'profile_complete' => $seller->profile_complete,
                    'ver_code' => $seller->ver_code,
                    'ver_code_send_at' => $seller->ver_code_send_at,
                    'ts' => $seller->ts,
                    'tv' => $seller->tv,
                    'tsc' => $seller->tsc,
                    'created_at' => $seller->created_at,
                    'updated_at' => $seller->updated_at,
                    'is_seller' => 1,
                    'seller_active' => 1,
                    'seller_activated_at' => $seller->created_at,
                    'balance' => $seller->balance,
                    'kyc_data' => $seller->kyc_data,
                    'kyc_rejection_reason' => $seller->kyc_rejection_reason,
                    'kv' => $seller->kv,
                    'featured' => $seller->featured,
                ]);
            }

            // Update references in other tables
            $tablesToUpdate = ['products', 'shops', 'withdrawals', 'transactions', 'sell_logs', 'sub_orders', 'user_logins'];
            foreach ($tablesToUpdate as $tableName) {
                if (Schema::hasColumn($tableName, 'seller_id')) {
                    DB::table($tableName)->where('seller_id', $seller->id)->update(['seller_id' => $newUserId]);
                }
            }
        }

        // Rename columns from seller_id to user_id (optional but recommended for consistency)
        // For now, let's keep them as seller_id to avoid breaking many things at once, 
        // OR rename if we are confident.
        // Actually, many relations expect 'user_id' for customer and 'seller_id' for seller even if it's the same table.
        // Let's stick with seller_id as the "owner_id" for seller contexts for now.
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_seller', 'seller_active', 'seller_activated_at', 'balance', 'kyc_data', 'kyc_rejection_reason', 'kv', 'featured']);
        });
    }
};
