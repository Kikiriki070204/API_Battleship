<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Partida;
use App\Models\Estado_Resultado;

class Resultado extends Model
{
    use HasFactory;

    protected $table = 'resultados';
    protected $fillable = [
        'partida_id',
        'ganador_id',
        'perdedor_id'
    ];

    public function partida()
    {
        return $this->belongsTo(Partida::class,'partida_id');
    }

    public function ganador()
    {
        return $this->belongsTo(User::class,'ganador_id');
    }
    public function perdedor()
    {
        return $this->belongsTo(User::class,'perdedor_id');
    }

}
