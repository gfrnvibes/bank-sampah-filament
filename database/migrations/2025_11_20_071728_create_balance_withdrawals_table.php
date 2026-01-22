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
        Schema::create('balance_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2); // Nominal penarikan
            $table->enum('status', ['pending', 'accepted', 'rejected', 'completed'])->default('pending');
            $table->boolean('hidden_by_user')->default(false);
            $table->boolean('hidden_by_admin')->default(false);
            $table->string('receipt')->nullable(); // Bukti penarikan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balance_withdrawals');
    }
};
