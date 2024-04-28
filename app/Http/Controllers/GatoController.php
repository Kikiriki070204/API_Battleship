<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Events\MyEvent;
use App\Models\Gato;
use App\Models\Partida;
use App\Models\Resultado;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class GatoController extends Controller
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
        $gato = Gato::where('_id', $partida->gato_id)->get();
        if ($partida && $gato) {
            return response()->json($gato->first(), 200);
        }
        return response()->json([], 404);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            "rowIndex" => "required|integer|min:0|max:2",
            "colIndex" => "required|integer|min:0|max:2"
        ]);
        $partida = Partida::find($id);
                $gato = Gato::where('_id', $partida->gato_id)->get()->first();
        if (!$gato) {
            return response()->json(["msg" => "Usuario no autorizado"], 404);
        }
        $user = Auth::guard('api')->user();
        if ($user && $gato->turno == $user->id) {
            $tablero = $gato->tablero1;

            if ($tablero[$request->rowIndex][$request->colIndex] == 0) {
                $jugadorActual = $gato->turno == $gato->jugadorO ? 1 : 2;
                $tablero[$request->rowIndex][$request->colIndex] = $jugadorActual;

                $gato->tablero = $tablero;
                $gato->turno = $gato->turno == $gato->jugadorO ? $gato->jugadorX : $gato->jugadorO;

                $gato->save();
                event(new MyEvent("ataque"));
                return response()->json($gato, 200);
            }
        }
        return response()->json(["msg" => "Usuario no autorizado"], 401);
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