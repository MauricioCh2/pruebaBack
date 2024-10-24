<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class Seeder_Roles extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rol')->insert([
            ['nombre_rol' => 'Administrador'],
            ['nombre_rol' => 'Registro'],
            ['nombre_rol' => 'Supervision'],
            ['nombre_rol' => 'Presupuestario'],
            ['nombre_rol' => 'Validacion'],
        ]);
    }
}