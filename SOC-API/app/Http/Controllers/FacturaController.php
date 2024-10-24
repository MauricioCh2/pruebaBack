<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Factura; // Importar el modelo
use App\Models\OrdenPedido; // Importar el modelo


class FacturaController extends Controller
{
    /**
     * Obtener factura por OP
     * @param Request $request
     */
    public function obtenerFacturaPorOP(Request $request)
    {
        // Validamos que 'op' sea un entero y que no esté vacío
        $request->validate(['op' => 'required|integer',],
        [
            'op.required' => '(!) Debe especificar una orden de pedido (OP).',
            'op.integer' => '(!) La orden de pedido (OP) debe ser un número entero.',
        ]);
        
        // Extraemos el 'op' del request
        $op = $request->input('op');
    
        // Buscamos la factura que tenga la 'op' especificada en la BD
        $factura = Factura::where('orden_asociada', $op)->first(); // SELECT * FROM `factura` WHERE `orden_asociada` = $op LIMIT 1;
    
        // Si la factura no ha sido adjunta
        if (!$factura)
        {
            return response()->noContent(); // HTTP-> 204 No Content
        }
    
        // Enviamos la factura si existe
        return response()->json($factura);
    }
    
    /**
     * Crear una nueva factura
     * @param Request $request
     */
    public function adjuntarFactura(Request $request){
        // Validamos los campos de la factura (solo los necesarios)
        $request->validate([
            'fecha_factura' => 'required|date',
            'cedula_juridica_proveedor' => 'required|string',
            'nombre_proveedor' => 'required|string',
            'nombre_cliente' => 'required|string',
            'nombre_factura' => 'required|string',
            'nombre_campania' => 'required|string',
            'costo_pauta' => 'required|numeric',
            'descuento' => 'required|numeric',
            'tcp' => 'required|numeric',
            'iva' => 'required|numeric',
            'total' => 'required|numeric',
            'numero_reserva' => 'required|string',
            'comision' => 'required|numeric',
            'utilidad' => 'required|numeric',
            'otros' => 'required|numeric',
            'orden_asociada' => 'required|integer|exists:orden_pedido,op', // Verificamos que exista la orden
        ]);

        // Creamos una nueva factura utilizando los datos recibidos
        $factura = new Factura();
        $factura->fecha_factura = $request->input('fecha_factura');
        $factura->cedula_juridica_proveedor = $request->input('cedula_juridica_proveedor');
        $factura->nombre_proveedor = $request->input('nombre_proveedor');
        $factura->nombre_cliente = $request->input('nombre_cliente');
        $factura->nombre_factura = $request->input('nombre_factura');
        $factura->nombre_campania = $request->input('nombre_campania');
        $factura->costo_pauta = $request->input('costo_pauta');
        $factura->descuento = $request->input('descuento');
        $factura->subtotal = $factura->costo_pauta - $factura->descuento; // Calculamos el subtotal
        $factura->{'tcp_1%'} = $factura->subtotal * 0.01; // Calculamos TCP
        $factura->{'iva_13%'} = $factura->subtotal * 0.13; // Calculamos IVA
        $factura->total = $factura->subtotal + $factura->{'tcp_1%'} + $factura->{'iva_13%'}; // Calculamos total
        $factura->numero_reserva = $request->input('numero_reserva');
        $factura->comision = $request->input('comision');
        $factura->utilidad = $request->input('utilidad');
        $factura->otros = $request->input('otros');
        $factura->orden_asociada = $request->input('orden_asociada');
        $factura->fecha_registro_factura = now(); // Asignar fecha de registro automáticamente
        $factura->presupuesto = 0; // O cualquier valor que corresponda

        // Guardamos la factura
        try {
            $factura->save();
        } catch (\Exception $e) {
            return response()->json(['error' => '(!) No se pudo adjuntar la factura: ' . $e->getMessage()], 500);
        }

        return response()->json(['success' => 'Factura ha sido adjunta', 'data' => $factura], 201);
    }

    //Esta función envía la factura a Supervisión manipulando booleanos
    public function validarFactura(Request $request)
    {
        // Verificamos que la factura tiene ID y esté bien formulada
        $request->validate(
            [
                'id_factura' => 'required|integer'
            ],
            [
                'id_factura.required' => '(!) Debe especificar el ID de la factura.',
                'id_factura.integer' => '(!) El ID de la factura debe ser un número entero.'
            ]
        );

        // Extraemos el ID de la factura del request
        $id_factura = $request->input('id_factura');

        // Traemos la factura en el model desde la BD
        $factura = Factura::find($id_factura);

        // Verificamos que la factura existe en la BD
        if(!$factura)
        {
            return response()->json(['error' => '(!) No se encontró la factura con el ID especificado.'], 404);
        }

        // Actualizamos el campo 'factura_validada' a true
        $factura->factura_validada = true;

        // Guardamos los cambios en la BD
        $factura->save();
        
        //Devuelve mensaje de éxito
        return response()->json(['success' => 'Factura validada con éxito.'], 200);
    }
}