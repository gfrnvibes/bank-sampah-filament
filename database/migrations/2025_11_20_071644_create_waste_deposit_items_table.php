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
        Schema::create('waste_deposit_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('waste_deposit_id')->constrained('waste_deposits')->onDelete('cascade');
            $table->foreignId('waste_type_id')->constrained('waste_types');
            $table->decimal('weight_kg', 10, 2); 
            $table->decimal('price_per_kg', 10, 2); 
            $table->decimal('subtotal', 15, 2); 
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waste_deposit_items');
    }
};
