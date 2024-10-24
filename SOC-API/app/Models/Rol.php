<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


// Este modelo corresponde a la tabla rol en la BD
// RolController lo utilizará
// para hacer, por ejemplo, operaciones CRUD
// llamando los métodos que Rol heredó de Model
class Rol extends Model
{
    use HasFactory;
    
    protected $table = 'rol';
    protected $primaryKey = 'pk_codigo_rol';

    //Atributos que el Controller tiene permitido manipular
    protected $fillable = ['nombre_rol'];

    public function usuarios()
    {
        return $this->belongsToMany(UsuarioConta::class, 'rol_asignado_conta', 'CODIGO_ROL', 'CEDULA');
    }
}
