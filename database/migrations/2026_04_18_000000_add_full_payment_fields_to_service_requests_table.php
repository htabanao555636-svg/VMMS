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
            $table->enum('payment_type', ['downpayment', 'full'])
                  ->default('downpayment')
                  ->after('downpayment_percentage');
            $table->string('full_payment_proof')
                  ->nullable()
                  ->after('proof_of_payment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropColumn(['payment_type', 'full_payment_proof']);
        });
    }
};
