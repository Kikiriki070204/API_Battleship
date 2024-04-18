<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Estado;
use App\Models\User;
use App\Models\Resultado;
use App\Models\Ataque;
class Partida extends Model
{
    use HasFactory;

    protected $table = 'partidas';
    protected $fillable = [
        'usuario_id',
        'invitado_id',
        'estado_id'
    ];
    public function usuario()
    {
        return $this->belongsTo(User::class,'usuario_id');
    }

    public function invitado()
    {
        return $this->belongsTo(User::class,'invitado_id');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class,'estado_id');
    }

    public function resultado()
    {
        return $this->hasMany(Resultado::class,'partida_id');
    }

    public function ataque()
    {
        return $this->hasMany(Ataque::class,'partida_id');
    }
}