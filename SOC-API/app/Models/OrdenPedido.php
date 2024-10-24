<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Este modelo corresponde a la tabla orden_pedido en la BD
// OrdenPedidoController lo utilizará
// para hacer, por ejemplo, operaciones CRUD
// llamando los métodos que OrdenPedido heredó de Model
class OrdenPedido extends Model
{
    use HasFactory;

    protected $table = 'orden_pedido'; // Especificamos el nombre de la tabla si no sigue el formato plural por defecto
    protected $primaryKey = 'op';

    //Atributos que el Controller tiene permitido manipular
    protected $fillable = [
        'fecha_creacion',
        'fecha_aprobacion',
        'nombre_proveedor',
        'nombre_cliente',
        'nombre_factura',
        'nombre_campania',
        'costo_pauta',
        'descuento',
        'subtotal',
        'tcp_1%',
        'iva_13%',
        'total',
        'numero_reserva',
        'comision',
        'utilidad',
        'otros',
    ];
    
    //Desactiva las marcas de tiempo automáticas si no deseas utilizar created_at y updated_at.
    public $timestamps = true;
}