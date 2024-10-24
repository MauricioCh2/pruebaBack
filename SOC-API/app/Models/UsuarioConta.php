<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Este modelo corresponde a la tabla usuario_conta en la BD
// UsuarioContaController lo utilizará
// para hacer, por ejemplo, operaciones CRUD
// llamando los métodos que UsuarioConta heredó de Model
class UsuarioConta extends Model
{
    use HasFactory;

    protected $table = 'usuario_conta';
    protected $primaryKey = 'CEDULA';
    
    //Atributos que el Controller tiene permitido manipular
    protected $fillable = ['CEDULA','NOMBRE', 'PRIMER_APELLIDO', 'SEGUNDO_APELLIDO', 'CORREO', 'TELEFONO', 'CONTRASENIA'];

    //Con esta función, Laravel consulta en base de datos y retorna los roles asignados a un usuario.
    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'rol_asignado_conta', 'CEDULA', 'CODIGO_ROL');
    }
}
