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
        Schema::table('service_requests', function (Blueprint $table) {
            // Change the payment_status column to include 'balance_pending'
            DB::statement("ALTER TABLE service_requests MODIFY payment_status ENUM(
                'unpaid',
                'downpayment_pending',
                'downpayment_verified',
                'balance_pending',
                'fully_paid',
                'rejected'
            ) DEFAULT 'downpayment_pending'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            // Revert to previous enum values (remove balance_pending)
            DB::statement("ALTER TABLE service_requests MODIFY payment_status ENUM(
                'unpaid',
                'downpayment_pending',
                'downpayment_verified',
                'fully_paid',
                'rejected'
            ) DEFAULT 'downpayment_pending'");
        });
    }
};
