<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OrdenPedido;

class OrdenPedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear datos de ejemplo para la tabla orden_pedido
        OrdenPedido::create([
            'op'=> '100',
            'fecha_creacion' => '2024-10-06',
            'fecha_aprobacion' => '2024-10-06',
            'nombre_proveedor' => 'Radio Best',
            'nombre_cliente' => 'Coca Cola',
            'nombre_campania' => 'Festinl',
            'nombre_factura' => 'Festinl',
            'costo_pauta' => 23800,
            'descuento' => 0.0,
            'subtotal' => 261800.00,
            'tcp_1%' => 261800.00,
            'iva_13%' => 261800.00,
            'total' => 295834.00,
            'numero_reserva' => 654,
            'comision' => 52360.00,
            'utilidad' => 34360,
            'otros' => 0.0,
        ]);

        OrdenPedido::create([
            'op'=> '101',
            'fecha_creacion' => '2024-10-07',
            'fecha_aprobacion' => '2024-10-07',
            'nombre_proveedor' => 'RADIO DISNEY',
            'nombre_cliente' => 'El Rey',
            'nombre_campania' => 'Festinl',
            'nombre_factura' => 'Festinl',
            'costo_pauta' => 34300.00,
            'descuento' => 0.00,
            'subtotal' => 411600.00,
            'total' => 465108.00,
            'tcp_1%' => 250800.00,
            'iva_13%' => 250800.00,
            'numero_reserva' => 655,
            'comision' => 250.00,
            'utilidad' => 700,
            'otros' => 0.00,
        ]);

        OrdenPedido::create([
            'op'=> '102',
            'fecha_creacion' => '2024-10-08',
            'fecha_aprobacion' => '2024-10-08',
            'nombre_proveedor' => 'CONNECTIA',
            'nombre_cliente' => 'Importadora Monge',
            'nombre_campania' => 'Festinl',
            'nombre_factura' => 'Festinl',
            'costo_pauta' => 3500.00,
            'descuento' => 400.00,
            'subtotal' => 793450.00,
            'tcp_1%' => 150800.00,
            'iva_13%' => 150800.00,
            'total' => 896598.50,
            'numero_reserva' => 657,
            'comision' => 119017.50,
            'utilidad' => 103148.50,
            'otros' => 200.00,
        ]);
    }
}
