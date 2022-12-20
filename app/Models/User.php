<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Obtiene el rol del usuario.
     */
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id', 'id');
    }


    /**
     * Obtiene los documentos del usuario.
     */
    public function asigndocumentos()
    {
        return $this->hasMany(AsignaDocumento::class, 'user_id', 'id');
    }

    /**
     * Obtiene las notificaciones.
     */
    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'user_id', 'id');
    }
    // /**
    //  * The roles that belong to the user.
    //  */
    // public function asigndocumentos()
    // {
    //     return $this->belongsToMany(AsignaDocumento::class, 'user_id', 'id');
    // }
}
