<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Factura;
use App\Models\OrdenPedido;
use Database\Seeders\OrdenPedidoSeeder;
use Database\factories\UserFactory;

class FacturasTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Ejecutar el seeder de OrdenPedido si es necesario
        $this->seed(OrdenPedidoSeeder::class);
    }


    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_no_content_when_invoice_not_found_by_op()
    {
        // Hacer la solicitud GET para obtener la factura por OP que no existe
        $response = $this->get('/api/factura/OP?op=99999');

        // Verificar que la respuesta sea 204 No Content
        $response->assertStatus(204);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_404_when_invoice_not_found()
    {
        // Hacer la solicitud PUT para validar una factura que no existe
        $response = $this->put('/api/ValidarFactura', [
            'id_factura' => 99999,
        ]);

        // Verificar que la respuesta sea 404
        $response->assertStatus(404);
        $response->assertJson(['error' => '(!) No se encontró la factura con el ID especificado.']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_attach_and_save_invoice()
    {
        // Crear una orden de pedido
        $orden = OrdenPedido::factory()->create([
            'op' => 12345,
            'nombre_proveedor' => 'Proveedor Test',
            'nombre_cliente' => 'Cliente Test',
            'nombre_factura' => 'Factura Test',
            'nombre_campania' => 'Campaña Test',
            'costo_pauta' => 1000,
            'descuento' => 100,
            'subtotal' => 900,
            'tcp_1%' => 9,
            'iva_13%' => 117,
            'total' => 1016,
            'numero_reserva' => 1,
            'comision' => 50,
            'utilidad' => 50,
            'otros' => 10,
        ]);

         // Datos de factura simulados que se adjuntarán
        $data = [
            'orden_asociada' => 12345,
            'fecha_factura' => '2024-10-19',
            'cedula_juridica_proveedor' => '123456789',
            'otros' => 100, // Puedes ajustar este valor si se requiere con decimales en la petición
            'nombre_proveedor' => 'Proveedor Ejemplo',
            'nombre_cliente' => 'Cliente Ejemplo',
            'nombre_factura' => 'Factura Ejemplo',
            'nombre_campania' => 'Campaña Ejemplo',
            'costo_pauta' => 1000,  // Puedes ajustar este valor si se requiere con decimales en la petición
            'descuento' => 10,      // Puedes ajustar este valor si se requiere con decimales en la petición
            'subtotal' => 990,      // Puedes ajustar este valor si se requiere con decimales en la petición
            'tcp' => 9.90,
            'iva' => 128.70,
            'total' => 1128.60,
            'numero_reserva' => 'R-12345',
            'comision' => 50,
            'utilidad' => 80,
        ];

        // Simular una petición POST para adjuntar la factura
    $response = $this->postJson('/api/AdjuntarFactura', $data);

    // Verificar que la respuesta sea exitosa
    $response->assertStatus(201);
    $response->assertJson(['success' => 'Factura ha sido adjunta']);

    // Verificar que la factura se haya guardado en la base de datos
    $this->assertDatabaseHas('factura', [
        'orden_asociada' => $orden->op,
        'fecha_factura' => '2024-10-19',
        'cedula_juridica_proveedor' => '123456789',
        'otros' => '100.00',  // Ajustado a formato decimal
        'nombre_proveedor' => 'Proveedor Ejemplo',  // Asegúrate de que coincida con la base de datos
        'nombre_cliente' => 'Cliente Ejemplo',      // Asegúrate de que coincida con la base de datos
        'nombre_factura' => 'Factura Ejemplo',      // Asegúrate de que coincida con la base de datos
        'nombre_campania' => 'Campaña Ejemplo',     // Asegúrate de que coincida con la base de datos
        'costo_pauta' => '1000.00',                 // Ajustado a formato decimal
        'descuento' => '10.00',                     // Ajustado a formato decimal
        'subtotal' => '990.00',                     // Ajustado a formato decimal
        'tcp_1%' => '9.90',                         // Ajustado a formato decimal
        'iva_13%' => '128.70',                      // Ajustado a formato decimal
        'total' => '1128.60',                       // Ajustado a formato decimal
        'numero_reserva' => 'R-12345',              // Asegúrate de que coincida con la base de datos
        'comision' => '50.00',                      // Ajustado a formato decimal
        'utilidad' => '80.00',                      // Ajustado a formato decimal
    ]);
    }

    public function test_validation_errors_when_fields_are_missing()
    {
        $data = [
            'orden_asociada' => 12345,
            'fecha_factura' => '2024-10-19',
            // Falta tcp e iva
        ];

        // Simular la petición POST con datos incompletos
        $response = $this->postJson('/api/AdjuntarFactura', $data);

        // Verificar que el estado sea 422 (Unprocessable Entity)
        $response->assertStatus(422);

        // Verificar que los mensajes de error sean los correctos
        $response->assertJsonValidationErrors(['tcp', 'iva']);
    }

}