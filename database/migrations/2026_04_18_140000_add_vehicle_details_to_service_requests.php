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
        Schema::table('service_requests', function (Blueprint $table) {
            // Add vehicle detail columns if they don't exist
            if (!Schema::hasColumn('service_requests', 'vehicle_name')) {
                $table->string('vehicle_name')->nullable()->after('vehicle_id');
            }
            
            if (!Schema::hasColumn('service_requests', 'vehicle_model')) {
                $table->string('vehicle_model')->nullable()->after('vehicle_name');
            }
            
            if (!Schema::hasColumn('service_requests', 'vehicle_registration')) {
                $table->string('vehicle_registration')->nullable()->after('vehicle_model');
            }
            
            if (!Schema::hasColumn('service_requests', 'vehicle_type')) {
                $table->string('vehicle_type')->nullable()->after('vehicle_registration');
            }
            
            // Add payment_status if it doesn't exist
            if (!Schema::hasColumn('service_requests', 'payment_status')) {
                $table->string('payment_status')->default('unpaid')->after('proof_of_payment');
            }
            
            // Add payment_type if it doesn't exist
            if (!Schema::hasColumn('service_requests', 'payment_type')) {
                $table->string('payment_type')->nullable()->after('payment_status');
            }
            
            // Add full_payment_proof if it doesn't exist
            if (!Schema::hasColumn('service_requests', 'full_payment_proof')) {
                $table->string('full_payment_proof')->nullable()->after('payment_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $columns = [
                'vehicle_name',
                'vehicle_model',
                'vehicle_registration',
                'vehicle_type',
                'payment_status',
                'payment_type',
                'full_payment_proof',
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('service_requests', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
