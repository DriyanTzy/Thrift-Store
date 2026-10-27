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
        Schema::table('users', function (Blueprint $table) {
            // User / Buyer Profile Fields
            $table->string('phone')->nullable()->after('player_tag');
            $table->string('avatar')->nullable()->after('phone');
            $table->text('address')->nullable()->after('avatar');
            $table->text('bio')->nullable()->after('address');

            // Store / Merchant Profile Fields (Distinct from personal profile, under 1 email)
            $table->string('store_name')->nullable()->after('bio');
            $table->string('store_slogan')->nullable()->after('store_name');
            $table->text('store_description')->nullable()->after('store_slogan');
            $table->string('store_logo')->nullable()->after('store_description');
            $table->string('store_banner')->nullable()->after('store_logo');
            $table->string('store_city')->nullable()->after('store_banner');
            $table->string('store_phone')->nullable()->after('store_city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'avatar',
                'address',
                'bio',
                'store_name',
                'store_slogan',
                'store_description',
                'store_logo',
                'store_banner',
                'store_city',
                'store_phone',
            ]);
        });
    }
};
