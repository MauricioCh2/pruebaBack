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
        Schema::create('orden_pedido', function (Blueprint $table)
        {
            $table->integer('op')->autoIncrement();
            $table->primary('op');
            $table->date('fecha_creacion');
            $table->date('fecha_aprobacion');
            $table->string('nombre_proveedor', 255); //Medio
            $table->string('nombre_cliente', 255); //Anunciante
            $table->string('nombre_factura', 255); //Razón social
            $table->string('nombre_campania', 60);
            $table->decimal('costo_pauta', 10, 2); //Tarifa | presupuesto_x_pedido -> tarifa en BD soc_2023
            $table->decimal('descuento', 10, 2)->nullable();
            $table->decimal('subtotal', 10, 2); // subtotal en BD soc_2023
            $table->decimal('tcp_1%', 10, 2);
            $table->decimal('iva_13%', 10, 2);
            $table->decimal('total', 10, 2); // subtotal2 en BD soc_2023
            $table->string('numero_reserva',19);
            $table->decimal('comision', 10, 2)->nullable();
            $table->decimal('utilidad',10, 2)->nullable();
            $table->decimal('otros', 10, 2)->nullable();
            $table->timestamps(); // Esto crea las columnas created_at y updated_at automáticamente
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_pedido');
    }
};
