<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\Seeder_Roles;
use Database\Seeders\Seeder_Usuarios;
use Illuminate\Support\Facades\Hash;


class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Ejecutar el seeder de usuarios en cada prueba
        $this->seed(Seeder_Usuarios::class);
        $this->seed(Seeder_Roles::class);
    }


    #[\PHPUnit\Framework\Attributes\Test]
    public function login_successful_with_valid_credentials()
    {
        // Datos de inicio de sesión
        $data = [
            'CEDULA' => '222222222', // Usuario existente
            'CONTRASENIA' => 'password', // Contraseña correcta
        ];

        // Hacer una solicitud POST al endpoint de login
        $response = $this->postJson('/api/usuarios/login', $data);

        // Verificar que la respuesta tenga un código 200
        $response->assertStatus(200);

        // Verificar que el token esté presente en la respuesta
        $response->assertJsonStructure([
            'token',
            'role',
            'User' => [
                'CEDULA',
                'NOMBRE',
                'PRIMER_APELLIDO',
                'SEGUNDO_APELLIDO',
                'CORREO',
                'TELEFONO',
                'HABILITADO',
                'created_at',
                'updated_at',
            ]
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function login_fails_with_invalid_credentials()
    {
        // Datos de inicio de sesión con contraseña incorrecta
        $data = [
            'CEDULA' => '222222222', // Usuario existente
            'CONTRASENIA' => 'wrongpassword', // Contraseña incorrecta
        ];

        // Hacer una solicitud POST al endpoint de login
        $response = $this->postJson('/api/usuarios/login', $data);

        // Verificar que la respuesta tenga un código 401
        $response->assertStatus(401);
        $response->assertJson(['message' => 'Contraseña incorrecta']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function login_fails_with_non_existent_user()
    {
        // Datos de inicio de sesión para un usuario que no existe
        $data = [
            'CEDULA' => '999999999', // Cédula no existente
            'CONTRASENIA' => 'password',
        ];

        // Hacer una solicitud POST al endpoint de login
        $response = $this->postJson('/api/usuarios/login', $data);

        // Verificar que la respuesta tenga un código 401
        $response->assertStatus(401);
        $response->assertJson(['message' => 'Usuario no encontrado']);
    }
}
