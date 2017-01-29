<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    public $timestamps = false;
    protected $table="usuario";
    protected $primaryKey = "id_usuario";
    protected $fillable = ['username', 'password','status'];
    protected $casts = ['id_rol' => 'integer'];
    protected $guarded = ['id_usuario','id_rol'];
    protected $hidden = ['password', 'remember_token'];

    public function muestra()
    {
        return $this->hasMany(Muestra::class,'id_muestra','id_usuario');
    }

    public function rol()
    {
        return $this->belongsTo(RolUsuario::class,'id_rol','id_usuario');
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class,'id_persona','id_usuario');
    }
}
