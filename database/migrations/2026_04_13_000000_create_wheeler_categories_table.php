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
        Schema::create('wheeler_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 2-Wheelers, 3-Wheelers, 4-Wheelers, Heavy Vehicles
            $table->text('description')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wheeler_categories');
    }
};
