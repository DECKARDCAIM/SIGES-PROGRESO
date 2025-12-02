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
        Schema::create('responsibility_card_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('responsibility_card_id')->constrained('responsibility_cards')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('assigned_date'); // Fecha de asignación
            $table->date('removed_date')->nullable(); // Fecha de remoción
            $table->enum('status', ['active', 'transferred', 'removed'])->default('active'); // Estado
            $table->text('notes')->nullable(); // Notas
            $table->timestamps();
            
            // Evitar duplicados activos
            $table->unique(['responsibility_card_id', 'user_id'], 'card_user_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responsibility_card_user');
    }
};
