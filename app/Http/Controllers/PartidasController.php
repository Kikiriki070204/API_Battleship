<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partida;
use Validator;

class PartidasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function store(Request $request, int $id)
    {
        $user = auth()->user();
        if($user)
        {
        $usuario = $id;
        $partida = Partida::create([
            'usuario_id'=>$usuario
        ]);

        return response()->json([ "partida"=>$partida],200);
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
                $partida_user = $partida->usuario_id;
                $partida_invitado = $partida->invitado_id;
                if($user_id == $partida_user && $partida_invitado == null)
                {
                    return response()->json([ "msg"=>"Espera que alguien se una a la partida"],401);
                }
                
                $partida->update(['invitado_id'=>$user_id]);
                $partida->update(['estado_id'=> 2]);

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
        $partidas =  Partida::all()->where('estado_id',1);
        return response()->json([ "partidas"=>$partidas],200);
        }
    }
}
