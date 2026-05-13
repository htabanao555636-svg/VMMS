<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add payment_status column to service_requests if not exists
        Schema::table('service_requests', function (Blueprint $table) {
            $table->string('payment_status')->default('unpaid')->after('proof_of_payment');
        });

        // Create billings table
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('downpayment_amount', 10, 2)->default(0);
            $table->decimal('remaining_balance', 10, 2)->default(0);
            $table->enum('payment_status', [
                'unpaid',
                'downpayment_pending',
                'downpayment_verified',
                'rejected',
                'fully_paid',
            ])->default('unpaid');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('collected_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('collected_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billings');

        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropColumn('payment_status');
        });
    }
};