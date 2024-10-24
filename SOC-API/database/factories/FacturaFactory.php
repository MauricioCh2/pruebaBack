<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Factura>
 */
class FacturaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fecha_factura' => $this->faker->date(),
            'fecha_registro_factura' => $this->faker->dateTime(),
            'factura_validada' => $this->faker->boolean(),
            'factura_aprobada' => $this->faker->boolean(),
            'factura_con_presupuesto_asignado' => $this->faker->boolean(),
            'factura_con_presupuesto_aprobado' => $this->faker->boolean(),
            'presupuesto' => $this->faker->randomFloat(2, 0, 10000), // Valor aleatorio para presupuesto
            'cedula_juridica_proveedor' => $this->faker->numerify('#########'),
            'nombre_proveedor' => $this->faker->company(),
            'nombre_cliente' => $this->faker->name(),
            'nombre_factura' => $this->faker->sentence(3),
            'nombre_campania' => $this->faker->sentence(3),
            'costo_pauta' => $this->faker->randomFloat(2, 0, 10000),
            'descuento' => $this->faker->randomFloat(2, 0, 1000),
            'subtotal' => $this->faker->randomFloat(2, 0, 10000),
            'tcp_1%' => $this->faker->randomFloat(2, 0, 1000),
            'iva_13%' => $this->faker->randomFloat(2, 0, 1000),
            'total' => $this->faker->randomFloat(2, 0, 10000),
            'numero_reserva' => $this->faker->randomNumber(),
            'comision' => $this->faker->randomFloat(2, 0, 1000),
            'utilidad' => $this->faker->randomFloat(2, 0, 1000),
            'otros' => $this->faker->randomFloat(2, 0, 1000),
            'orden_asociada' => $this->faker->randomNumber(), // Asegúrate de que este número corresponda a un OP válido en tus pruebas
        ];
    }
}
