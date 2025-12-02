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
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre del bien
            $table->string('asset_number')->unique(); // Número único del bien
            $table->string('sku')->nullable(); // SKU
            $table->text('description')->nullable(); // Descripción
            $table->string('type')->nullable(); // Tipo de bien
            $table->string('brand')->nullable(); // Marca
            $table->string('model')->nullable(); // Modelo
            $table->string('serial_number')->nullable(); // Número de serie
            $table->decimal('purchase_price', 10, 2)->nullable(); // Precio de compra
            $table->date('purchase_date')->nullable(); // Fecha de compra
            $table->string('vendor')->nullable(); // Proveedor
            $table->integer('quantity')->default(1); // Cantidad
            $table->string('unit')->default('pza'); // Unidad (pieza, kg, etc)
            $table->enum('status', ['available', 'assigned', 'maintenance', 'retired'])->default('available'); // Estado
            $table->string('condition')->nullable(); // Condición (nuevo, usado, etc)
            $table->text('notes')->nullable(); // Notas adicionales
            $table->json('images')->nullable(); // Imágenes del bien
            $table->string('qr_path')->nullable(); // Ruta del código QR
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null'); // Departamento asignado
            $table->timestamps();
            $table->softDeletes(); // Para borrado suave
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
