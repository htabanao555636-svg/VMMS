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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_request_id')->constrained('service_requests')->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->enum('payment_type', ['downpayment', 'full', 'remaining'])->default('downpayment');
            $table->enum('status', ['pending', 'verified', 'rejected', 'completed'])->default('pending');
            $table->string('proof_image')->nullable(); // Path to uploaded proof file
            $table->text('proof_notes')->nullable(); // Notes from admin review
            $table->string('payment_method')->nullable(); // cash, transfer, check, etc.
            $table->string('reference_number')->nullable(); // Transaction reference
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            // Indexes for better query performance
            $table->index('service_request_id');
            $table->index('status');
            $table->index('payment_type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
