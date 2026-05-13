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
            if (!Schema::hasColumn('service_requests', 'total_amount')) {
                $table->decimal('total_amount', 10, 2)->default(0)->after('notes');
            }
            if (!Schema::hasColumn('service_requests', 'downpayment_amount')) {
                $table->decimal('downpayment_amount', 10, 2)->nullable()->after('total_amount');
            }
            if (!Schema::hasColumn('service_requests', 'remaining_balance')) {
                $table->decimal('remaining_balance', 10, 2)->nullable()->after('downpayment_amount');
            }
            if (!Schema::hasColumn('service_requests', 'downpayment_percentage')) {
                $table->integer('downpayment_percentage')->nullable()->after('remaining_balance');
            }
            if (!Schema::hasColumn('service_requests', 'proof_of_payment')) {
                $table->string('proof_of_payment')->nullable()->after('downpayment_percentage');
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
                'total_amount',
                'downpayment_amount',
                'remaining_balance',
                'downpayment_percentage',
                'proof_of_payment'
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
