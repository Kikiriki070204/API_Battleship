<?php

namespace App\Http\Controllers;

use App\Events\MyEvent;
use App\Models\Lobby;
use App\Models\Partida;
use App\Models\Resultado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LobbiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //$request->validate([
        //    "jugador1" => "required|exists:App\Models\User,id"
        //]);
//
        //$lobby = new Lobby();
        //$lobby->jugador1 = $request->jugador1;
//
//
        //$collection1 = collect([]);
        //for ($i = 0; $i < 25; $i++)
        //{
        //    $collection1 = $collection1->push(0);
        //} 
        //for ($i = 25; $i < 40; $i++)
        //{
        //    $collection1 = $collection1->push(1);
        //} 
        //$collection1 = $collection1->shuffle();
        //$collection1_final = collect([]);
        //for ($i = 0; $i < 5; $i++)
        //{
        //    $collection1_slice = $collection1->slice($i*8, 8);
        //    $collection1_final->push($collection1_slice);
        //} 
        //$lobby->tablero1 = $collection1_final->toArray();
//
        //$collection2 = collect([]);
        //for ($i = 0; $i < 25; $i++)
        //{
        //    $collection2 = $collection2->push(0);
        //} 
        //for ($i = 25; $i < 40; $i++)
        //{
        //    $collection2 = $collection2->push(1);
        //} 
        //$collection2 = $collection1->shuffle();
        //$collection2_final = collect([]);
        //for ($i = 0; $i < 5; $i++)
        //{
        //    $collection2_slice = $collection1->slice($i*8, 8);
        //    $collection2_final->push($collection2_slice);
        //} 
//
        //$lobby->tablero2 = $collection2_final->toArray();
//
        //$lobby->save();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $partida = Partida::find($id);
        $lobby = Lobby::where('_id', $partida->lobby_id)->get();

        if ($partida && $lobby) {
            return response()->json($lobby->first(), 200);
        }
        return response()->json([], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            "rowIndex" => "required|integer|min:0|max:4",
            "colIndex" => "required|integer|min:0|max:7"
        ]);

        $partida = Partida::find($id);
        $lobby = Lobby::where('_id', $partida->lobby_id)->get()->first();
        if (!$lobby) {
            return response()->json([ "msg"=>"Usuario no autorizado"],404);
        }

        $user = Auth::guard('api')->user();
        if ($user && $lobby->turno == $user->id) {
            if ($lobby->turno == $lobby->jugador1) {

                $newtablero2 = $lobby->tablero2;
                switch ($newtablero2[$request->rowIndex][$request->colIndex]) {
                    case 1:
                        $newtablero2[$request->rowIndex][$request->colIndex] = 2;
                        $lobby->puntos2 -= 1;
                        if ($lobby->puntos2 <= 0) {
                            $lobby->ganador = $lobby->jugador1;

                            $resultados = Resultado::create([
                                'partida_id'=>$id,
                                'ganador_id'=>$lobby->jugador1,
                                'perdedor_id'=>$lobby->jugador2
                            ]);
                        }
                    break;
                }
                $lobby->tablero2 = $newtablero2;

                $lobby->turno = $lobby->jugador2;
            } else {

                $newtablero1 = $lobby->tablero1;
                switch ($newtablero1[$request->rowIndex][$request->colIndex]) {
                    case 1:
                        $newtablero1[$request->rowIndex][$request->colIndex] = 2;
                        $lobby->puntos1 -= 1;
                        if ($lobby->puntos1 <= 0) {
                            $lobby->ganador = $lobby->jugador2;
                            $resultados = Resultado::create([
                                'partida_id'=>$id,
                                'ganador_id'=>$lobby->jugador2,
                                'perdedor_id'=>$lobby->jugador1
                            ]);
                        }
                    break;
                }
                $lobby->tablero1= $newtablero1;

                $lobby->turno = $lobby->jugador1;
            }
            $lobby->save();
            event(new MyEvent("sasa"));
            return response()->json($lobby,200);
        }
        return response()->json([ "msg"=>"Usuario no autorizado"],401);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
