<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resultado;
use App\Models\User;

class ResultadosController extends Controller
{
    
    public function lost_battles(int $id)
    {
        $user = auth()->user();
        if($user)
        {
        $lost_battles = Resultado::where('perdedor_id',$id)->load('ganador_id','perdedor_id','partida_id');

        return response()->json([
            "partidas" => $lost_battles
        ]);
        }

    }

    public function won_battles(int $id)
    {

        $user = auth()->user();
        if($user)
        {
        $lost_battles = Resultado::where('ganador_id',$id)->load('pededor_id','ganador_id','partida_id');

        return response()->json([
            "partidas" => $lost_battles
        ]);
        }

    }
}
