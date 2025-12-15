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
            $table->decimal('total_weight', 8, 2);
            $table->decimal('total_income', 15, 2);
            $table->string('buyer')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('hidden_by_user')->default(false);
            $table->boolean('hidden_by_admin')->default(false);
            $table->softDeletes();
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
