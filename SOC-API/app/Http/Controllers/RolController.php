<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rol;
use Illuminate\Support\Facades\Validator;

class RolController extends Controller
{
    public function index()
    {
        return Rol::all()->isEmpty() 
            ? response()->json(['message' => 'No hay roles registrados'], 404) 
            : response()->json(Rol::all(), 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_rol' => 'required|string|max:30',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Error en la validación de datos', 'errors' => $validator->errors()], 400);
        }

        $rol = Rol::create([
            'nombre_rol' => $request->nombre_rol,
        ]);

        return $rol 
            ? response()->json($rol, 201) 
            : response()->json(['message' => 'Error al crear el rol'], 500);
    }

    public function show($id)
    {
        $rol = Rol::find($id);

        return $rol 
            ? response()->json($rol, 200) 
            : response()->json(['message' => 'Rol no encontrado'], 404);
    }

    public function destroy($id)
    {
        return Rol::destroy($id) 
            ? response()->json(['message' => 'Rol eliminado correctamente'], 200) 
            : response()->json(['message' => 'Rol no encontrado'], 404);
    }

    public function update(Request $request, $id)
    {
        $rol = Rol::find($id);

        if (!$rol) {
            return response()->json(['message' => 'Rol no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre_rol' => 'required|string|max:30',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Error en la validación de datos', 'errors' => $validator->errors()], 400);
        }

        return $rol->update($request->only('nombre_rol'))
            ? response()->json(['message' => 'Rol actualizado correctamente', 'rol' => $rol], 200)
            : response()->json(['message' => 'Error al actualizar el rol'], 500);
    }
}