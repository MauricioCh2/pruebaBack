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
        Schema::create('factura', function (Blueprint $table)
        {
            $table->integer('id_factura')->autoIncrement();
            $table->primary('id_factura');
            $table->date('fecha_factura');
            $table->date('fecha_registro_factura');
            $table->boolean('factura_validada')->default(false);
            $table->boolean('factura_aprobada')->default(false);
            $table->boolean('factura_con_presupuesto_asignado')->default(false);
            $table->boolean('factura_con_presupuesto_aprobado')->default(false);
            $table->decimal('presupuesto',10, 2); //Visible solo para Presupuesto 
            $table->string('cedula_juridica_proveedor', 255);
            $table->string('nombre_proveedor', 255); //Medio
            $table->string('nombre_cliente', 255); //Anunciante
            $table->string('nombre_factura', 255); //Razón social
            $table->string('nombre_campania', 60);
            $table->decimal('costo_pauta', 10, 2);
            $table->decimal('descuento', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tcp_1%', 10, 2);
            $table->decimal('iva_13%', 10, 2);
            $table->decimal('total', 10, 2);
            $table->string('numero_reserva',19);
            $table->decimal('comision', 10, 2);
            $table->decimal('utilidad',10, 2);
            $table->decimal('otros', 10, 2);
            $table->timestamps(); // Esto crea las columnas created_at y updated_at automáticamente
            
            // Llave foránea: relación 1 a 1
            $table->integer('orden_asociada')->unique();
            $table->foreign('orden_asociada')
            ->references('op')->on('orden_pedido')
            ->onDelete('cascade'); // Eliminar la factura si su orden desaparece
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factura');
    }
};