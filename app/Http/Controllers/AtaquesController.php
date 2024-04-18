<?php

namespace App\Http\Controllers;

use App\Models\Ataque;
use Illuminate\Http\Request;
use App\Models\Partida;
use Validator;
class AtaquesController extends Controller
{
    public function atacar(int $id, Request $request)
    {
        $user= auth()->user();

        if($user)
        {
            
            $user_id = $user->id;

            $partida = Partida::find($id);

            if($partida)
            {
                $validate = Validator::make($request->all(),[
                    'x'=>'required',
                    'y'=>'required'
                ]);
                if($validate->fails())
                {
                    return response()->json(["errors"=>$validate->errors(),
                    "msg"=>"Errores de validación"],422);
                }
        
                $partida_user = $partida->usuario_id;
                if($user_id == $partida_user)
                {
                   $ataque = Ataque::create([
                    'x'=>$request->x,
                    'y'=>$request->y,
                    'partida_id'=>$partida->id,
                    'hitter_id'=>$partida->usuario_id,
                    'target_id'=>$partida->invitado_id
                   ]);
                   return response()->json([ "ataque"=>$ataque],201);
                }
                $ataque = Ataque::create([
                    'x'=>$request->x,
                    'y'=>$request->y,
                    'partida_id'=>$partida->id,
                    'hitter_id'=>$partida->invitado_id,
                    'target_id'=>$partida->usuario_id
                   ]);
                   return response()->json([ "ataque"=>$ataque],201);
            }
            return response()->json([ "msg"=>"Partida no encontrada"],404);

        }

    }
}
