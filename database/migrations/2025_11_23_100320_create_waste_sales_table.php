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
        Schema::create('waste_sales', function (Blueprint $table) {
            $table->id();
            $table->json('waste_items'); // jenis, berat, harga jual per jenis
            $table->decimal('total_weight', 8, 2);
            $table->decimal('total_income', 15, 2); // pendapatan hasil penjualan
            $table->text('buyer')->nullable(); // pengepul siapa (opsional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waste_sales');
    }
};
