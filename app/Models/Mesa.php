<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    protected $fillable = [
        'nombre',
        'zona',
        'capacidad',
        'estado',
    ];

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }
}
