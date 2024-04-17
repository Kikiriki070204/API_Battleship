<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Partida;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\Resultado;

class User extends Authenticatable implements JWTSubject
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
        'is_active',
        'is_verified'
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

    public function getJWTIdentifier(){
        return $this->getKey();
    }

    public function getJWTCustomClaims(){
        return [];
    }

    public function partida_usuario()
    {
        return $this->hasMany(Partida::class,'usuario_id');
    }

    public function partida_invitado()
    {
        return $this->hasMany(Partida::class,'invitado_id');
    }

    public function resultado_ganador()
    {
        return $this->hasMany(Resultado::class,'ganador_id');
    }
    public function resultado_perdedor()
    {
        return $this->hasMany(Resultado::class,'perdedor_id');
    }
}
