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
            // Add assigned_by field after mechanic_id
            $table->foreignId('assigned_by')->nullable()->after('mechanic_id');
            
            // Add assignment timestamp
            $table->timestamp('assigned_at')->nullable()->after('assigned_by');
            
            // Add staff notes
            $table->text('staff_notes')->nullable()->after('notes');
            
            // Add indexes for performance
            $table->index('assigned_by');
            $table->index('assigned_at');
            
            // Add foreign key constraint
            $table->foreign('assigned_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropForeign(['assigned_by']);
            $table->dropIndex(['assigned_by']);
            $table->dropIndex(['assigned_at']);
            $table->dropColumn(['assigned_by', 'assigned_at', 'staff_notes']);
        });
    }
};
