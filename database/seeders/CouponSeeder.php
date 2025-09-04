<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('coupons')->insert([
            'name' => 'Extra 10% Off On First Order',
            'discount_type' => 'percentage',
            'code' => 'PROMO',
            'is_first_order_coupon' => 1,
            'no_expiry' => 1,
            'amount' => 10.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
