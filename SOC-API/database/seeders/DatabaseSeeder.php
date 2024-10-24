<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        //Llamamos a los datos de prueba que creamos en los seeders
        $this->call(Seeder_Roles::class);
        $this->call(Seeder_Usuarios::class);
        $this->call(RolesAsignados_Seeder::class);
        $this->call(OrdenPedidoSeeder::class);

    }
}
