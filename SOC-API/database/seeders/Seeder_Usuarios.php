<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Seeder_Usuarios extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Usuarios SINART

        //Prueba de usuarios-------------------------------
        DB::table('usuario_conta')->insert([
            'CEDULA' => '111111111',
            'NOMBRE' => 'Alfredo',
            'PRIMER_APELLIDO' => 'Salazar',
            'SEGUNDO_APELLIDO' => 'Zamora',
            'CORREO' => 'admin@example.com',
            'TELEFONO' => '11111111',
            'CONTRASENIA' => Hash::make('password'), 
            'HABILITADO' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('usuario_conta')->insert([
            'CEDULA' => '222222222',
            'NOMBRE' => 'Esteban',
            'PRIMER_APELLIDO' => 'Ureña',
            'SEGUNDO_APELLIDO' => 'apellido2',
            'CORREO' => 'contable@example.com',
            'TELEFONO' => '22222222',
            'CONTRASENIA' => Hash::make('password'), 
            'HABILITADO' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('usuario_conta')->insert([
            'CEDULA' => '333333333',
            'NOMBRE' => 'Ingrid',
            'PRIMER_APELLIDO' => 'Contreras',
            'SEGUNDO_APELLIDO' => 'Bonilla',
            'CORREO' => 'supervision@example.com',
            'TELEFONO' => '33333333',
            'CONTRASENIA' => Hash::make('password'), 
            'HABILITADO' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('usuario_conta')->insert([
            'CEDULA' => '444444444',
            'NOMBRE' => 'Xinia',
            'PRIMER_APELLIDO' => 'Gomez',
            'SEGUNDO_APELLIDO' => 'apellido2',
            'CORREO' => 'presupuesto@example.com',
            'TELEFONO' => '44444444',
            'CONTRASENIA' => Hash::make('password'), 
            'HABILITADO' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('usuario_conta')->insert([
            'CEDULA' => '555555555',
            'NOMBRE' => 'Yorleni',
            'PRIMER_APELLIDO' => 'Vargas',
            'SEGUNDO_APELLIDO' => 'apellido2',
            'CORREO' => 'validacion@example.com',
            'TELEFONO' => '55555555',
            'CONTRASENIA' => Hash::make('password'), 
            'HABILITADO' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('usuario_conta')->insert([
            'CEDULA' => '666666666',
            'NOMBRE' => 'Equipo14Admin',
            'PRIMER_APELLIDO' => 'apellido1',
            'SEGUNDO_APELLIDO' => 'apellido2',
            'CORREO' => 'equipo14@example.com',
            'TELEFONO' => '66666666',
            'CONTRASENIA' => Hash::make('password'), 
            'HABILITADO' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}