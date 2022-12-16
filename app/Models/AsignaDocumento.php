<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignaDocumento extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'documento_id',
        'name_user',
        'email_destinatario',
        'notificado',
    ];


    /**
     * Obtiene el usuario al que pertenece el documento.
     */
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    /**
     * Obtiene el usuario al que pertenece el documento.
     */
    public function documento()
    {
        return $this->belongsTo(Documento::class, 'documento_id', 'id');
    }
}
