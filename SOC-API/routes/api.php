<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioContaController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\OrdenPedidoController;
use App\Http\Controllers\FacturaController;

/*---------[USUARIOS]---------*/

// Ruta para listar todos los usuarios
Route::get('/usuarios', [UsuarioContaController::class, 'index']);

Route::get('/usuarios-sin-roles', [UsuarioContaController::class, 'indexUsuarios']);

// Ruta para listar todos los usuarios con roles
Route::get('/usuarios/con-roles', [UsuarioContaController::class, 'indexWithRoles']);

// Ruta para obtener un usuario específico por su ID
Route::get('/usuarios/{id}', [UsuarioContaController::class, 'show']);

// Ruta para obtener usuarios cuyo nombre coincida parcial o enteramente con el string proporcionado
Route::get('/usuarios/nombre/{nombre}', [UsuarioContaController::class, 'showUsersByName']);

// Ruta para crear un nuevo usuario
Route::post('/usuarios', [UsuarioContaController::class, 'store']);

// Ruta para actualizar un usuario completo
Route::put('/usuarios/{id}', [UsuarioContaController::class, 'update']);

// Ruta para actualizar parcialmente un usuario
Route::patch('/usuarios/{id}', [UsuarioContaController::class, 'partialUpdate']);

// Ruta para asignar rol a un usuario
Route::post('/usuarios/{cedula}/roles', [UsuarioContaController::class, 'assignRole']);

// Ruta para inicio de sesión
Route::post('/usuarios/login', [UsuarioContaController::class, 'login']);


/*---------[ROLES]---------*/

// Ruta para listar todos los roles
Route::get('/roles', [RolController::class, 'index']);

// Ruta para obtener un rol específico por su ID
Route::get('/roles/{id}', [RolController::class, 'show']);

// Ruta para crear un nuevo rol
Route::post('/roles', [RolController::class, 'store']);

// Ruta para actualizar un rol completo
Route::put('/roles/{id}', [RolController::class, 'update']);


/*---------[ÓRDENES DE PEDIDO]---------*/

// Ruta para obtener todas las órdenes de pedido
Route::get('/ordenes_pedido', [OrdenPedidoController::class, 'index']);

// Ruta para obtener una orden de pedido por su ID
Route::get('/ordenpedido/OP', [OrdenPedidoController::class, 'searchByOP']);

// Ruta para buscar ordenes de pedido por fecha
Route::get('/ordenpedido/date', [OrdenPedidoController::class, 'searchByDate']);

Route::patch('/Update/OrdenPedido', [OrdenPedidoController::class, 'partialUpdate']);

/*---------[FACTURA]---------*/
// Ruta para obtener una factura dada su OP asignada
Route::get('/factura/OP', [FacturaController::class, 'obtenerFacturaPorOP']);

// Ruta para adjuntar una factura a una OP enviada en el request
Route::post('/AdjuntarFactura', [FacturaController::class, 'adjuntarFactura']);

// Ruta para validar una factura en sus booleanos.
Route::put('/ValidarFactura', [FacturaController::class, 'validarFactura']);
