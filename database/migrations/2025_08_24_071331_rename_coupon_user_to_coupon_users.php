<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('coupon_user', 'coupon_users');
    }

    public function down(): void
    {
        Schema::rename('coupon_users', 'coupon_user');
    }
};
