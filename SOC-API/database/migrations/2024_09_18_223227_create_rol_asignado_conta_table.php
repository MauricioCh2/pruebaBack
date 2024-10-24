<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rol_asignado_conta', function (Blueprint $table) {
            $table->string('CEDULA', 40);
            $table->unsignedBigInteger('CODIGO_ROL');

            $table->primary(['CEDULA', 'CODIGO_ROL']);

            $table->foreign('CEDULA')
                  ->references('CEDULA')
                  ->on('usuario_conta')
                  ->onDelete('cascade');

            $table->foreign('CODIGO_ROL')
                  ->references('pk_codigo_rol')
                  ->on('rol')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rol_asignado_conta');
    }
};