<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resultado;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ResultadosController extends Controller
{
    
    public function lost_battles()
    {
        $user = Auth::guard('api')->user();
        if ($user) 
        {
            
        $id = $user->id;
        $perdedor_battles = DB::table('resultados')
            ->where('perdedor_id', $id)
            ->join('users as perdedor', 'resultados.perdedor_id', '=', 'perdedor.id')
            ->join('users as ganador', 'resultados.ganador_id', '=', 'ganador.id')
            ->join('partidas', 'resultados.partida_id', '=', 'partidas.id')
            ->select('resultados.id', 'perdedor.name as perdedor', 'ganador.name as ganador')
            ->get();


        return response()->json([
            "partidas" => $perdedor_battles
        ], 200);
        }
        return response()->json([
        ], 404);
    
    }

    public function won_battles()
    {
        $user = Auth::guard('api')->user();
        if ($user) 
        {
            
        $id = $user->id;
        $ganador_battles = DB::table('resultados')
            ->where('ganador_id', $id)
            ->join('users as perdedor', 'resultados.perdedor_id', '=', 'perdedor.id')
            ->join('users as ganador', 'resultados.ganador_id', '=', 'ganador.id')
            ->join('partidas', 'resultados.partida_id', '=', 'partidas.id')
            ->select('resultados.id', 'perdedor.name as perdedor', 'ganador.name as ganador')
            ->get();


        return response()->json([
            "partidas" => $ganador_battles
        ], 200);
        }
        return response()->json([
        ], 401);
    }
}
