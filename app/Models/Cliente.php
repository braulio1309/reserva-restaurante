<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'tipo',
        'notas',
        'visitas',
        'ultima_visita',
    ];

    protected $casts = [
        'ultima_visita' => 'datetime',
    ];

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }
}
