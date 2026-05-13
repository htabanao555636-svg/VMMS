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
        Schema::table('services', function (Blueprint $table) {
            // Add wheeler_category_id column
            $table->foreignId('wheeler_category_id')
                ->nullable()
                ->after('category_id')
                ->constrained('wheeler_categories')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            // Drop the foreign key and column
            $table->dropForeignIdFor(\App\Models\WheelerCategory::class);
            $table->dropColumn('wheeler_category_id');
        });
    }
};
