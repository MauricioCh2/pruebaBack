<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\Seeder_Usuarios;
use Database\Seeders\Seeder_Roles;
use App\Models\UsuarioConta;

class UsuariosControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Ejecutar el seeder de usuarios en cada prueba
        $this->seed(Seeder_Usuarios::class);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function list_all_users()
    {
        // Hacer una solicitud GET a /usuarios
        $response = $this->get('/api/usuarios-sin-roles');

        // Verificar que la respuesta tenga un código 200
        $response->assertStatus(200);

        // Verificar que se devuelven todos los usuarios esperados del seeder (asumimos 5 usuarios)
        $response->assertJsonCount(6);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function show_a_specific_user()
    {
        // Obtener un usuario del seeder
        $usuario = UsuarioConta::where('CEDULA', '222222222')->first();

        // Hacer una solicitud GET a /usuarios/{id}
        $response = $this->get('/api/usuarios/' . $usuario->CEDULA);

        // Verificar que la respuesta tenga un código 200
        $response->assertStatus(200);

        // Verificar que los datos del usuario son correctos
        $response->assertJsonFragment([
            'CEDULA' => 222222222,
            'NOMBRE' => $usuario->NOMBRE,
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function shows_message_if_no_users_exist()
    {
        // Omitir el seeding para este caso
        $this->artisan('migrate:fresh');

        // Hacer una solicitud GET a /usuarios
        $response = $this->get('/api/usuarios');

        // Verificar que la respuesta tenga un código 404
        $response->assertStatus(404);

        // Verificar que el mensaje devuelto sea el correcto
        $response->assertJson([
            'message' => 'No hay usuarios registrados',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function create_a_new_user()
    {
        // Datos del nuevo usuario
        $data = [
            'CEDULA' => '0987654321',
            'NOMBRE' => 'Juan',
            'PRIMER_APELLIDO' => 'Pérez',
            'SEGUNDO_APELLIDO' => 'López',
            'CORREO' => 'juanperez@example.com',
            'TELEFONO' => '1234567890',
            'CONTRASENIA' => 'password123'
        ];

        // Hacer una solicitud POST a /usuarios
        $response = $this->postJson('/api/usuarios', $data);

        // Verificar que la respuesta tenga un código 201 (creado)
        $response->assertStatus(201);

        // Verificar que el usuario se haya creado correctamente en la base de datos
        $this->assertDatabaseHas('usuario_conta', ['CEDULA' => '0987654321']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function update_a_user()
    {
        // Obtener un usuario del seeder
        $usuario = UsuarioConta::where('CEDULA', '222222222')->first();

        // Nuevos datos para actualizar el usuario
        $data = [
            'NOMBRE' => 'Nombre Actualizado',
            'PRIMER_APELLIDO' => 'Apellido Actualizado'
        ];

        // Hacer una solicitud PUT a /usuarios/{id}
        $response = $this->patchJson('/api/usuarios/' . $usuario->CEDULA, $data);

        // Verificar que la respuesta tenga un código 200 (actualizado correctamente)
        $response->assertStatus(200);

        // Verificar que el mensaje sea el correcto
        $response->assertJsonFragment([
            'message' => 'Usuario actualizado correctamente',
        ]);

        // Verificar que los datos del usuario se hayan actualizado en la base de datos
        $this->assertDatabaseHas('usuario_conta', [
            'CEDULA' => $usuario->CEDULA,
            'NOMBRE' => 'Nombre Actualizado',
            'PRIMER_APELLIDO' => 'Apellido Actualizado',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function shows_error_if_user_not_found_for_update()
    {
        // Hacer una solicitud PUT a /usuarios/{id} con un ID que no existe
        $response = $this->putJson('/api/usuarios/999', [
            'NOMBRE' => 'Nombre No Existente',
        ]);

        // Verificar que la respuesta tenga un código 404 (no encontrado)
        $response->assertStatus(404);

        // Verificar que el mensaje sea el correcto
        $response->assertJsonFragment([
            'message' => 'Usuario no encontrado',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function assign_role_to_user()
    {
        $this->seed(Seeder_Roles::class);
        // Obtener un usuario del seeder
        $usuario = UsuarioConta::where('CEDULA', '222222222')->first();

        // Asignar un rol al usuario
        $data = [
            'roles' => [2] // Suponiendo que el rol 1 es 'Administrador'
        ];

        // Hacer una solicitud POST a /usuarios/{cedula}/roles
        $response = $this->postJson('/api/usuarios/' . $usuario->CEDULA . '/roles', $data);

        // Verificar que la respuesta tenga un código 200
        $response->assertStatus(200);

        // Verificar que el usuario tiene asignado el rol 'Administrador'
        $this->assertDatabaseHas('rol_asignado_conta', [
            'CEDULA' => $usuario->CEDULA,
            'CODIGO_ROL' => 2, // Suponiendo que el código del rol es 1
        ]);
    }
}