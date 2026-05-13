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
            // Add vehicle relationship if not existing
            if (!Schema::hasColumn('service_requests', 'vehicle_id')) {
                $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->onDelete('set null')->after('customer_id');
            }
            
            // Ensure request_date has proper name
            if (Schema::hasColumn('service_requests', 'requested_date') && !Schema::hasColumn('service_requests', 'request_date')) {
                // Keep requested_date, it's fine
            }
            
            // Add request details fields
            if (!Schema::hasColumn('service_requests', 'request_type')) {
                $table->enum('request_type', ['drop-off', 'pickup'])->default('drop-off')->after('status');
            }
            
            if (!Schema::hasColumn('service_requests', 'address')) {
                $table->text('address')->nullable()->after('request_type');
            }
            
            // Ensure payment fields exist
            if (!Schema::hasColumn('service_requests', 'total_amount')) {
                $table->decimal('total_amount', 12, 2)->default(0)->after('address');
            }
            
            if (!Schema::hasColumn('service_requests', 'downpayment_amount')) {
                $table->decimal('downpayment_amount', 12, 2)->nullable()->after('total_amount');
            }
            
            if (!Schema::hasColumn('service_requests', 'remaining_balance')) {
                $table->decimal('remaining_balance', 12, 2)->nullable()->after('downpayment_amount');
            }
            
            if (!Schema::hasColumn('service_requests', 'downpayment_percentage')) {
                $table->integer('downpayment_percentage')->nullable()->after('remaining_balance');
            }
            
            if (!Schema::hasColumn('service_requests', 'proof_of_payment')) {
                $table->string('proof_of_payment')->nullable()->after('downpayment_percentage');
            }
            
            // Add soft deletes for data recovery
            if (!Schema::hasColumn('service_requests', 'deleted_at')) {
                $table->softDeletes()->after('proof_of_payment');
            }

            // Add indexes
            if (!Schema::hasColumn('service_requests', 'vehicle_id')) {
                // Already indexed by foreignId
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            // Drop foreign key constraint safely
            try {
                if (Schema::hasColumn('service_requests', 'vehicle_id')) {
                    $table->dropForeign(['vehicle_id']);
                }
            } catch (\Exception $e) {
                // Foreign key doesn't exist, ignore
            }
            
            $columns = [
                'vehicle_id',
                'request_type',
                'address',
                'total_amount',
                'downpayment_amount',
                'remaining_balance',
                'downpayment_percentage',
                'proof_of_payment',
                'deleted_at'
            ];
            
            $columnsToDelete = [];
            foreach ($columns as $column) {
                if (Schema::hasColumn('service_requests', $column)) {
                    $columnsToDelete[] = $column;
                }
            }
            
            if (!empty($columnsToDelete)) {
                $table->dropColumn($columnsToDelete);
            }
        });
    }
};
