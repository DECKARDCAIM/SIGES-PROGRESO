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
        Schema::create('appraisals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained('inventory')->onDelete('cascade'); // Bien
            $table->string('appraisal_number')->unique(); // Número de dictamen
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending'); // Estado
            $table->text('description')->nullable(); // Descripción del dictamen
            $table->text('conclusion')->nullable(); // Conclusión
            $table->decimal('estimated_value', 10, 2)->nullable(); // Valor estimado
            $table->foreignId('appraiser_id')->nullable()->constrained('users')->onDelete('set null'); // Perito
            $table->date('appraisal_date')->nullable(); // Fecha del dictamen
            $table->date('completion_date')->nullable(); // Fecha de completado
            $table->string('document_path')->nullable(); // Ruta del documento PDF
            $table->text('notes')->nullable(); // Notas adicionales
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appraisals');
    }
};
