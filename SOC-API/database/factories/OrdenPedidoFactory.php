<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrdenPedido>
 */
class OrdenPedidoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'op' => $this->faker->unique()->numerify('###'),
            'fecha_creacion' => $this->faker->date(),
            'fecha_aprobacion' => $this->faker->date(),
            'nombre_proveedor' => $this->faker->company,
            'nombre_cliente' => $this->faker->company,
            'nombre_campania' => $this->faker->word,
            'nombre_factura' => $this->faker->word,
            'costo_pauta' => $this->faker->randomFloat(2, 1000, 50000),
            'descuento' => 0.0,
            'subtotal' => $this->faker->randomFloat(2, 1000, 50000),
            'tcp_1%' => $this->faker->randomFloat(2, 100, 1000),
            'iva_13%' => $this->faker->randomFloat(2, 100, 1000),
            'total' => $this->faker->randomFloat(2, 1000, 100000),
            'numero_reserva' => $this->faker->numberBetween(1, 1000),
            'comision' => $this->faker->randomFloat(2, 100, 5000),
            'utilidad' => $this->faker->randomFloat(2, 100, 10000),
            'otros' => $this->faker->randomFloat(2, 0, 1000),
        ];
    }
}
