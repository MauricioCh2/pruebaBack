<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class RolesAsignados_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rol_asignado_conta')->insert([
            ['CEDULA' => '111111111', 'CODIGO_ROL' => 1], // Asignar Administrador
            ['CEDULA' => '222222222', 'CODIGO_ROL' => 2], // Asignar Contable
            ['CEDULA' => '333333333', 'CODIGO_ROL' => 3], // Asignar Supervisión
            ['CEDULA' => '444444444', 'CODIGO_ROL' => 4], // Asignar Presupuesto
            ['CEDULA' => '555555555', 'CODIGO_ROL' => 5], // Asignar Validación
            ['CEDULA' => '666666666', 'CODIGO_ROL' => 1], // Asignar Administrador
            ['CEDULA' => '666666666', 'CODIGO_ROL' => 2], // Asignar Administrador
        ]);
    }
}
