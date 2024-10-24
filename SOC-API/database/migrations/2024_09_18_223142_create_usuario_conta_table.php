<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('usuario_conta', function (Blueprint $table) {
            $table->string('CEDULA', 40)->primary();
            $table->string('NOMBRE', 30);
            $table->string('PRIMER_APELLIDO', 40);
            $table->string('SEGUNDO_APELLIDO', 40)->nullable();
            $table->string('CORREO', 100)->nullable();
            $table->string('TELEFONO', 10)->nullable();
            $table->boolean('HABILITADO')->default(false);
            $table->string('CONTRASENIA', 255);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('usuario_conta');
    }
};