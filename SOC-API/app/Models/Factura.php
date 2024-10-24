<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Este modelo corresponde a la tabla factura en la BD
// FacturaController lo utilizará
// para hacer, por ejemplo, operaciones CRUD
// llamando los métodos que Factura heredó de Model
class Factura extends Model
{
    use HasFactory;

    protected $table = 'factura';
    protected $primaryKey = 'id_factura';

    //Atributos que el Controller tiene permitido manipular
    protected $fillable = [
        'fecha_factura',
        'fecha_registro_factura',
        'factura_validada',
        'factura_aprobada',
        'factura_con_presupuesto_asignado',
        'factura_con_presupuesto_aprobado',
        'presupuesto',
        'cedula_juridica_proveedor',
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
        'orden_asociada'
    ];
}