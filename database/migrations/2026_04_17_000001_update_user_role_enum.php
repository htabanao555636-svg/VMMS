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
        // Update existing 'user' role values to 'customer'
        DB::table('users')
            ->where('role', 'user')
            ->update(['role' => 'customer']);

        // Modify the enum column to replace 'user' with 'customer'
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'staff', 'customer'])
                ->default('customer')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Update 'customer' role values back to 'user'
        DB::table('users')
            ->where('role', 'customer')
            ->update(['role' => 'user']);

        // Modify the enum column back to the original
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'staff', 'user'])
                ->default('user')
                ->change();
        });
    }
};
