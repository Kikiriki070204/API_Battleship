<?php

namespace App\Http\Controllers;

use App\Events\MyEvent;
use App\Events\PartidasEvent;
use App\Models\Lobby;
use Illuminate\Http\Request;
use App\Models\Partida;
use Illuminate\Support\Facades\Log;
use Validator;

class PartidasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function store(Request $request)
    {
        $user = auth()->user();
        if($user)
        {
            $usuario = $user->id;

            $lobby = new Lobby();
            $lobby->jugador1 = $usuario;


            $collection1 = collect([]);
            for ($i = 0; $i < 25; $i++)
            {
                $collection1 = $collection1->push(0);
            } 
            for ($i = 25; $i < 40; $i++)
            {
                $collection1 = $collection1->push(1);
            } 
            $collection1 = $collection1->shuffle();
            $collection1_final = $collection1->split(5); 

            $lobby->tablero1 = $collection1_final->toArray();

            $collection2 = collect([]);
            for ($i = 0; $i < 25; $i++)
            {
                $collection2 = $collection2->push(0);
            } 
            for ($i = 25; $i < 40; $i++)
            {
                $collection2 = $collection2->push(1);
            } 
            $collection2 = $collection2->shuffle();
            $collection2_final = $collection2->split(5);
            //for ($i = 0; $i < 5; $i++)
            //{
            //    $collection2_slice = $collection1->slice($i*8, 8);
            //    $collection2_final->push($collection2_slice->toArray());
            //} 

            $lobby->tablero2 = $collection2_final->toArray();

            $lobby->save();

            Log::debug($lobby->_id);
            
            $partida = new Partida();
            $partida->usuario_id = $usuario; 
            $partida->lobby_id = $lobby->_id;
            $partida->save();

            event(new PartidasEvent());

        return response()->json($partida,200);
    }
    return response()->json([ "msg"=>"No estás autorizado"],401);
    }

 
    public function join(Request $request, int $id)
    {
        $user = auth()->user();
        if($user)
        {
            $user_id = $user->id;
    
            $partida = Partida::find($id);
    
            if($partida)
            {
                $lobby = Lobby::where('_id', $partida->lobby_id)->get()->first();
    
                // Restablecer el estado del lobby
                $lobby->jugador2 = $user_id;
                $lobby->turno = $user_id;
                $lobby->puntos2 = 15;
                $lobby->puntos1 = 15;
                // Asegúrate de restablecer cualquier otro estado necesario
    
                $lobby->save();
    
                // Restablecer el resultado de la partida
                $partida->resultado = null;
                $partida->save();
    
                event(new MyEvent("sasa"));
    
                return response()->json([ "msg"=>"Has entrado a la partida"],200);
            }
            return response()->json([ "msg"=>"Partida no encontrada"],404);
    
        }
        return response()->json([ "msg"=>"Usuario no autorizado"],404);
    }


    public function disponibles()
    {
        $user = auth()->user();
        if($user)
        {
        $partidas =  Partida::where('estado_id',1)->get();
        return response()->json($partidas,200);
        }
    }
}
