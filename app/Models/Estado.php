<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Partida;

class Estado extends Model
{
    use HasFactory;

    protected $table = 'estado_partida';

    protected $fillable = [
        'nombre'
    ];

    public function partida()
    {
        return $this->hasMany(Partida::class,'estado_id');
    }
}
