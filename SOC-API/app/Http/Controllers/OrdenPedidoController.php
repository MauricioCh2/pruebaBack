<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrdenPedido; // Importar el modelo
use Illuminate\Support\Facades\Validator;

class OrdenPedidoController extends Controller
{
    /**
     * Devuelve todas las órdenes de pedido en formato JSON.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(){
         // Obtener todas las órdenes de pedido
        $ordenes = OrdenPedido::all();

        // Verificar si viene vacío o no
        if($ordenes->isEmpty()) {
            return response()->json(['message' => 'No hay órdenes de pedido registradas'], 404);
        }

        // Transformar los datos para devolver solo los campos deseados
        $ordenesTransformadas = $ordenes->map(function($orden) {
            return [
                'op' => $orden->op,
                'numero_reserva'=> $orden->numero_reserva,
                'nombre_factura'=> $orden->nombre_factura,
                'nombre_proveedor' => $orden->nombre_proveedor,
                'nombre_cliente' => $orden->nombre_cliente,
                'nombre_campania' => $orden->nombre_campania,
                'fecha_creacion' => $orden->fecha_creacion,
                'fecha_aprobacion' => $orden->fecha_aprobacion,
                'total' => $orden->total,
            ];


        });

        // Retornar en formato JSON
        return response()->json($ordenesTransformadas, 200);
    }

    public function searchByOP(Request $request){
            // Validar los datos de entrada
        $validator = Validator::make($request->all(), [
            'OP' => 'required|string|max:30',
        ]);

        // Comprobar si la validación falla
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Extraer el valor de OP
        $opValue = $request->input('OP');

        // Buscar el registro en la base de datos
        $ordenPedido = OrdenPedido::where('OP', $opValue)->first();

        // Comprobar si se encontró el registro
        if (!$ordenPedido) {
            return response()->json(['message' => 'Orden de pedido no encontrada'], 404);
        }

        // Si se encontró, puedes devolver los datos o procesarlos como necesites
        return response()->json($ordenPedido, 200);

    }

    public function searchByDate(Request $request){
        // Validar los datos de entrada
        $validator = Validator::make($request->all(), [
            'FECHA' => 'date_format:Y-m-d',
        ]);

        // Comprobar si la validación falla
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Buscar el registro en la base de datos
        $ordenPedido = OrdenPedido::where('fecha_creacion', $request->input('FECHA'))->first();

        // Comprobar si se encontró el registro
        if (!$ordenPedido) {
            return response()->json(['message' => 'Orden de pedido no encontrada'], 404);
        }

        // Si se encontró, puedes devolver los datos o procesarlos como necesites
        return response()->json($ordenPedido, 200);

    }

    public function partialUpdate(Request $request) {
        // Definir las reglas de validación
        $validator = Validator::make($request->all(), [
            'OP' => 'required|string|max:30', // Requerido para la búsqueda
            'fecha_creacion' => 'date_format:Y-m-d|nullable',
            'fecha_aprobacion' => 'date_format:Y-m-d|nullable',
            'nombre_proveedor' => 'string|max:40|nullable',
            'nombre_cliente' => 'string|max:40|nullable',
            'nombre_factura' => 'string|max:40|nullable',
            'nombre_campania' => 'string|max:40|nullable',
            'costo_pauta' => 'numeric|min:0|nullable', 
            'descuento' => 'numeric|min:0|nullable', 
            'subtotal' => 'numeric|min:0|nullable', 
            'tcp_1%' => 'numeric|min:0|nullable',
            'iva_13%' => 'numeric|min:0|nullable',
            'total' => 'numeric|min:0|nullable', 
            'numero_reserva' => 'integer|min:1|nullable', 
            'comision' => 'numeric|min:0|nullable', 
            'utilidad' => 'numeric|min:0|nullable', 
            'otros' => 'numeric|min:0|nullable',
        ]);
    
        // Comprobar si la validación falla
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        // Buscar la orden de pedido por el OP
        $ordenPedido = OrdenPedido::where('op', $request->input('OP'))->first();
    
        // Verificar si la orden de pedido existe
        if (!$ordenPedido) {
            return response()->json(['error' => '(!) No se encontró la orden de pedido con el OP especificado.'], 404);
        }
    
        // Filtrar los campos que tienen un valor válido
        $data = $request->only([
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
            'otros'
        ]);
    
        // Asignar solo los campos no nulos al modelo
        $ordenPedido->fill(array_filter($data));
    
        // Guardar los cambios en la base de datos
        try {
            $ordenPedido->save();
        } catch (\Exception $e) {
            return response()->json(['error' => '(!) No se pudo actualizar la orden de pedido: ' . $e->getMessage()], 500);
        }
    
        // Devuelve un mensaje de éxito
        return response()->json(['success' => 'Orden de pedido actualizada con éxito.', 'data' => $ordenPedido], 200);
    }
    
    


}
