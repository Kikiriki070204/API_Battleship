<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Partida;

class Ataque extends Model
{
    use HasFactory;
    protected $table ='ataques';

    protected $fillable =[
        'x','y','hitter_id','target_id','partida_id'
    ];

    public function hitter()
    {
        return $this->belongsTo(User::class,'hitter_id');
    }
    public function target()
    {
        return $this->belongsTo(User::class,'target_id');
    }
    public function partida()
    {
        return $this->belongsTo(Partida::class,'partida_id');
    }
}
