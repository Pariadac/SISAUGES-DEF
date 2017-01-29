<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    public $timestamps = false;
    protected  $table = "departamento";
    protected  $primaryKey = "id_departamento";
    protected $fillable = ['descripcion_departamento','status'];
    protected $guarded = ['id_departamento','id_institucion'];


    public function institucion()
    {
        return $this->belongsTo(Institucion::class,'id_institucion','id_departamento');
    }

    public function tutor()
    {
        return $this->hasMany(Tutor::class,'id_tutor','id_departamento');
    }
}
