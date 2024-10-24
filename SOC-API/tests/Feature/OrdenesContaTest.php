<?php
    namespace Tests\Feature;

    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Tests\TestCase;
    use App\Models\OrdenPedido;
use Database\Seeders\OrdenPedidoSeeder;

    class OrdenesContaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(OrdenPedidoSeeder::class);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function list_all_orders()
    {
        // Hacer una solicitud GET a /ordenes_pedido
        $response = $this->get('/api/ordenes_pedido');

        // Verificar que la respuesta tenga un código 200
        $response->assertStatus(200);

        // Verificar que la respuesta tenga exactamente 3 órdenes de pedido
        $response->assertJsonCount(3);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function shows_message_if_no_orders_exist()
    {
        // Omitir el seeding para este caso
        $this->artisan('migrate:fresh');

        // Hacer una solicitud GET a /ordenes_pedido cuando no hay datos
        $response = $this->get('/api/ordenes_pedido');

        // Verificar que la respuesta tenga un código 404
        $response->assertStatus(404);

        // Verificar que el mensaje devuelto sea el correcto
        $response->assertJson([
            'message' => 'No hay órdenes de pedido registradas',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function search_order_by_date()
    {
        // Crear una orden de pedido
        $orden = OrdenPedido::factory()->create(['fecha_creacion' => '2024-01-01']);

        // Hacer una solicitud GET a /ordenpedido/date con la fecha
        $response = $this->get('/api/ordenpedido/date?FECHA=2024-01-01');

        // Verificar que la respuesta tenga un código 200
        $response->assertStatus(200);

        // Verificar que los datos de la orden sean los correctos
        $response->assertJsonFragment([
            'fecha_creacion' => '2024-01-01',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function shows_error_if_order_not_found_by_date()
    {
        // Hacer una solicitud GET a /ordenpedido/date con una fecha inexistente
        $response = $this->get('/api/ordenpedido/date?FECHA=2024-12-31');

        // Verificar que la respuesta tenga un código 404
        $response->assertStatus(404);

        // Verificar que el mensaje sea el correcto
        $response->assertJson([
            'message' => 'Orden de pedido no encontrada',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function shows_error_if_order_not_found_by_op()
    {
        // Hacer una solicitud GET a /ordenpedido/OP con un OP inexistente
        $response = $this->get('/api/ordenpedido/OP?OP=OP99999'); // Usamos un OP que seguramente no existe
    
        // Verificar que la respuesta tenga un código 404
        $response->assertStatus(404);
    
        // Verificar que el mensaje sea el correcto
        $response->assertJson([
            'message' => 'Orden de pedido no encontrada',
        ]);
    }

}
?>