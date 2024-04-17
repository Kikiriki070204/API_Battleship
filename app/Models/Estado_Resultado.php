<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Resultado;

class Estado_Resultado extends Model
{
    use HasFactory;
    protected $table = 'estado_resultado';

    protected $fillable = [
        'nombre'
    ];

}
