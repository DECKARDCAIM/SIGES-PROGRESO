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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_number')->unique(); // Número de traspaso
            $table->foreignId('inventory_id')->constrained('inventory')->onDelete('cascade'); // Bien
            
            // Tarjeta de origen y destino
            $table->foreignId('from_card_id')->constrained('responsibility_cards')->onDelete('cascade'); // Tarjeta origen
            $table->foreignId('to_card_id')->constrained('responsibility_cards')->onDelete('cascade'); // Tarjeta destino
            
            // Usuarios que entregan y reciben
            $table->foreignId('delivered_by_user_id')->constrained('users')->onDelete('cascade'); // Quien entrega
            $table->foreignId('received_by_user_id')->constrained('users')->onDelete('cascade'); // Quien recibe
            
            $table->date('transfer_date'); // Fecha del traspaso
            $table->enum('status', ['pending', 'in_transit', 'completed'])->default('pending'); // Estado
            $table->text('reason')->nullable(); // Motivo del traspaso
            $table->text('notes')->nullable(); // Notas adicionales
            
            // Documentos
            $table->string('format_path')->nullable(); // Formato imprimible para firma
            $table->string('signed_document_path')->nullable(); // Documento firmado escaneado
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
