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
        Schema::create('responsibility_cards', function (Blueprint $table) {
            $table->id();
            $table->string('card_number')->unique(); // Número de tarjeta
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade'); // Departamento
            $table->date('issue_date'); // Fecha de emisión
            $table->date('expiry_date')->nullable(); // Fecha de vencimiento
            $table->enum('status', ['active', 'inactive', 'transferred'])->default('active'); // Estado
            $table->text('notes')->nullable(); // Notas
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responsibility_cards');
    }
};
