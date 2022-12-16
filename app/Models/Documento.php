<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'archivo',
        'url_archivo',
        'tamano',
        'firmado',
        'archivo_firmado'

    ];


    // /**
    //  * Un usuario tiene muchos documentos asignados.
    //  */
    // public function asigndocumentos()
    // {
    //     return $this->belongsToMany(AsignaDocumento::class, 'user_id', 'id');
    // }
    /**
     * Obtiene los documentos del usuario.
     */
    public function asigndocumentos()
    {
        return $this->hasMany(AsignaDocumento::class, 'documento_id', 'id');
    }
}
