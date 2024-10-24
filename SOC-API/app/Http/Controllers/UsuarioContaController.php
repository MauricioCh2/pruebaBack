<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UsuarioConta;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Event\Telemetry\System;

class UsuarioContaController extends Controller
{
    public function index(){
        // Cargar todos los usuarios junto con sus roles
        $usuarios = UsuarioConta::with('roles')->get();
        return $usuarios->isEmpty() ? response()->json(['message' => 'No hay usuarios registrados'], 404) : response()->json($usuarios, 200);
    }

    public function indexUsuarios()
    {
        // Obtén solo los usuarios sin incluir roles
        $usuarios = UsuarioConta::all(['CEDULA', 'NOMBRE', 'PRIMER_APELLIDO', 'SEGUNDO_APELLIDO', 'CORREO', 'TELEFONO', 'HABILITADO']);

        return response()->json($usuarios);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'CEDULA' => 'required|string|max:12|unique:usuario_conta,CEDULA', 
            'NOMBRE' => 'required|string|max:30',
            'PRIMER_APELLIDO' => 'required|string|max:40',
            'SEGUNDO_APELLIDO' => 'string|max:40|nullable',
            'CORREO' => 'required|email|max:100|unique:usuario_conta,CORREO',
            'TELEFONO' => 'string|max:10|nullable',
            'CONTRASENIA' => 'required|string|min:8|max:255', // Cambiado a mínimo 8 caracteres
        ], [
            'CEDULA.unique' => 'La cédula ya está registrada en el sistema.',
            'CORREO.unique' => 'El correo electrónico ya está registrado en el sistema.',
            'required' => 'El campo :attribute es obligatorio.',
            'max' => 'El campo :attribute no debe exceder los :max caracteres.',
            'min' => 'El campo :attribute debe tener al menos :min caracteres.',
            'email' => 'El campo :attribute debe ser una dirección de correo electrónico válida.',
        ]);

        if ($validator->fails()) {
            // Obtener el primer error para construir un mensaje resumido
            $firstError = $validator->errors()->first(); 
        
            return response()->json([
                'message' => 'Error en la validación: ' . $firstError, // Mensaje resumido con el primer error
                'errors' => $validator->errors() // Lista de errores detallados
            ], 400);
        }
        
        $usuario = UsuarioConta::create([
            'CEDULA' => $request->CEDULA,  
            'NOMBRE' => $request->NOMBRE,
            'PRIMER_APELLIDO' => $request->PRIMER_APELLIDO,
            'SEGUNDO_APELLIDO' => $request->SEGUNDO_APELLIDO,
            'CORREO' => $request->CORREO,
            'TELEFONO' => $request->TELEFONO,
            'CONTRASENIA' => Hash::make($request->CONTRASENIA), // Hashear la contraseña antes de guardar
        ]);
        
        UsuarioContaController::assignRole($request, $request->CEDULA);

        return $usuario 
            ? response()->json($usuario, 201) 
            : response()->json(['message' => 'Error al crear el usuario'], 500);
    }

    public function show($id){
        $usuario = UsuarioConta::with('roles')->find($id);

        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        return response()->json($usuario, 200);
    }

    public function showUsersByName($name)
    {
        $usuarios = UsuarioConta::with('roles')->where('NOMBRE', 'like', $name . '%')->get();
    
        if ($usuarios->isEmpty()) {
            return response()->json(['message' => 'No se hallaron resultados'], 404);
        }
    
        return response()->json($usuarios, 200);
    }

    public function destroy($id){
        return UsuarioConta::destroy($id) 
            ? response()->json(['message' => 'Usuario eliminado correctamente'], 200) 
            : response()->json(['message' => 'Usuario no encontrado'], 404);
    }

    public function update(Request $request, $id){
        $usuario = UsuarioConta::find($id);

        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'CEDULA' => 'required|string|max:12|unique:usuario_conta,CEDULA,' . $usuario->CEDULA . ',CEDULA',
            'NOMBRE' => 'required|string|max:30',
            'PRIMER_APELLIDO' => 'required|string|max:40',
            'SEGUNDO_APELLIDO' => 'string|max:40|nullable',
            'CORREO' => 'email|max:100|nullable',
            'TELEFONO' => 'string|max:10|nullable',
            'CONTRASENIA' => 'nullable|string|min:8|max:255', // Cambiado a nullable para no requerir siempre
            'roles' => 'array|nullable',
            'roles.*' => 'exists:rol,pk_codigo_rol',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Error en la validación de datos', 'errors' => $validator->errors()], 400);
        }

        // Si la contraseña se actualiza, hashearla
        $data = $request->only(['CEDULA', 'NOMBRE', 'PRIMER_APELLIDO', 'SEGUNDO_APELLIDO', 'CORREO', 'TELEFONO']);
        if ($request->filled('CONTRASENIA')) {
            $data['CONTRASENIA'] = Hash::make($request->CONTRASENIA); // Hashear la nueva contraseña
        }

        // Si se proporciona una nueva contraseña, hashearla
        if ($request->has('CONTRASENIA')) {
            $usuario->CONTRASENIA = Hash::make($request->CONTRASENIA);
            $usuario->save(); // Guardar la actualización de la contraseña
        }

        // Si se proporcionan roles, sincronizarlos
        if ($request->has('roles')) {
            $usuario->roles()->sync($request->roles);
        }

        // Convertir HABILITADO a un valor compatible con tinyint(1)
        if ($request->has('HABILITADO')) {
            $usuario->HABILITADO = $request->HABILITADO ? 1 : 0;
            $usuario->save(); // Guardar la actualización del estado habilitado
        }
        
        return $usuario->update($data)
            ? response()->json(['message' => 'Usuario actualizado correctamente', 'usuario' => $usuario], 200)
            : response()->json(['message' => 'Error al actualizar el usuario'], 500);
    }

    public function partialUpdate(Request $request, $id) {
                // Buscar el usuario por ID
        $usuario = UsuarioConta::find($id);

        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        // Definir las reglas de validación
        $validator = Validator::make($request->all(), [
            'CEDULA' => 'string|max:12|unique:usuario_conta,CEDULA,' . $usuario->CEDULA, 
            'NOMBRE' => 'string|max:30',
            'PRIMER_APELLIDO' => 'string|max:40',
            'SEGUNDO_APELLIDO' => 'string|max:40|nullable',
            'CORREO' => 'email|max:100|nullable',
            'TELEFONO' => 'string|max:10|nullable',
            'CONTRASENIA' => 'string|min:8|max:255|nullable', // No es obligatorio en un update
            'HABILITADO' => 'boolean|nullable', // Validación para el campo HABILITADO
            'roles' => 'array|nullable',
            'roles.*' => 'exists:rol,pk_codigo_rol',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Error en la validación de datos', 'errors' => $validator->errors()], 400);
        }

        // Actualizar solo los campos que están presentes en la solicitud
        $usuario->update(array_filter($request->only([
            'CEDULA',
            'NOMBRE',
            'PRIMER_APELLIDO',
            'SEGUNDO_APELLIDO',
            'CORREO',
            'TELEFONO',
            'HABILITADO', // HABILITADO se incluirá en los campos a actualizar
        ]), function ($value) {
            return !is_null($value);
        }));

        // Si se proporciona una nueva contraseña, hashearla
        if ($request->has('CONTRASENIA')) {
            $usuario->CONTRASENIA = Hash::make($request->CONTRASENIA);
            $usuario->save(); // Guardar la actualización de la contraseña
        }

        // Si se proporcionan roles, sincronizarlos
        if ($request->has('roles')) {
            $usuario->roles()->sync($request->roles);
        }

        // Convertir HABILITADO a un valor compatible con tinyint(1)
        if ($request->has('HABILITADO')) {
            $usuario->HABILITADO = $request->HABILITADO ? 1 : 0;
            $usuario->save(); // Guardar la actualización del estado habilitado
        }

        return response()->json(['message' => 'Usuario actualizado correctamente', 'usuario' => $usuario], 200);
    }
    

    public function assignRole(Request $request, $cedula){
        $validator = Validator::make($request->all(), [
            'roles' => 'required|array',
            'roles.*' => 'exists:rol,pk_codigo_rol',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Error en la validación de roles', 'errors' => $validator->errors()], 400);
        }

        $usuario = UsuarioConta::find($cedula);

        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $usuario->roles()->sync($request->roles);

        return response()->json(['message' => 'Roles asignados correctamente', 'roles' => $usuario->roles], 200);
    }

    public function login(Request $request) {
        $user = UsuarioConta::where('CEDULA', $request->CEDULA)->first();
        
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 401);
        }
        
        if (!Hash::check($request->CONTRASENIA, $user->CONTRASENIA)) {
            return response()->json(['message' => 'Contraseña incorrecta'], 401);
        }
    
        // Todo está bien, continuar con la generación de token
        $token = 'fake-jwt-token'; 
        $role = $user->roles()->pluck('nombre_rol'); 
    
        return response()->json([
            'token' => $token,
            'role' => $role,
            'User' => $user
        ], 200);
    }
    
}