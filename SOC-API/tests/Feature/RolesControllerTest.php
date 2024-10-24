<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\Seeder_Roles;

class RolesControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Ejecutar el seeder en cada prueba
        $this->seed(Seeder_Roles::class);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function list_all_roles()
    {
        // Hacer una solicitud GET a /roles
        $response = $this->get('/api/roles');

        // Verificar que la respuesta tenga un código 200
        $response->assertStatus(200);

        // Verificar que la respuesta tenga exactamente 5 roles (según el seeder)
        $response->assertJsonCount(5);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function shows_message_if_no_roles_exist()
    {
        // Omitir el seeding para este caso
        $this->artisan('migrate:fresh');

        // Hacer una solicitud GET a /roles
        $response = $this->get('/api/roles');

        // Verificar que la respuesta tenga un código 404
        $response->assertStatus(404);

        // Verificar que el mensaje devuelto sea el correcto
        $response->assertJson([
            'message' => 'No hay roles registrados',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function create_a_new_role()
    {
        // Datos del nuevo rol
        $data = [
            'nombre_rol' => 'Operador',
        ];

        // Hacer una solicitud POST a /roles
        $response = $this->postJson('/api/roles', $data);

        // Verificar que la respuesta tenga un código 201 (creado)
        $response->assertStatus(201);

        // Verificar que el rol se haya creado correctamente en la base de datos
        $this->assertDatabaseHas('rol', ['nombre_rol' => 'Operador']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function show_a_specific_role()
    {
        // Obtener un rol del seeder
        $rol = \app\Models\Rol::where('nombre_rol', 'Administrador')->first();

        // Hacer una solicitud GET a /roles/{id}
        $response = $this->get('/api/roles/' . $rol->id);

        // Verificar que la respuesta tenga un código 200
        $response->assertStatus(200);

        // Verificar que los datos del rol sean los correctos
        $response->assertJsonFragment([
            'nombre_rol' => 'Administrador',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function update_a_role()
    {
            // Obtener un rol del seeder
        $rol = \app\Models\Rol::where('nombre_rol', 'Registro')->first();

        // Nuevos datos para actualizar el rol
        $data = [
            'nombre_rol' => 'Registro'
        ];

        // Hacer una solicitud PUT a /roles/{id}
        $response = $this->putJson('/api/roles/' . $rol->pk_codigo_rol, $data);

        // Verificar que la respuesta tenga un código 200 (actualizado correctamente)
        $response->assertStatus(200);

        // Verificar que el mensaje sea el correcto
        $response->assertJsonFragment([
            'message' => 'Rol actualizado correctamente',
        ]);

        // Verificar que los datos del rol se hayan actualizado en la base de datos
        $this->assertDatabaseHas('rol', [
            'pk_codigo_rol' => $rol->pk_codigo_rol,
            'nombre_rol' => 'Registro'
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function shows_error_if_role_not_found_for_update()
    {
        // Hacer una solicitud PUT a /roles/{id} con un ID que no existe
        $response = $this->putJson('/api/roles/999', [
            'nombre_rol' => 'Administrador',
        ]);

        // Verificar que la respuesta tenga un código 404 (no encontrado)
        $response->assertStatus(404);

        // Verificar que el mensaje sea el correcto
        $response->assertJsonFragment([
            'message' => 'Rol no encontrado',
        ]);
    }
}
