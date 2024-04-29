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

                $gato->tablero1 = $tablero;
                $gato->turno = $gato->turno == $gato->jugadorO ? $gato->jugadorX : $gato->jugadorO;
                $empate = false;
                $gato->empate = $empate;

                $ganador = null;

                //horizontales
            if ($tablero[0][0] == $jugadorActual && $tablero[0][1] == $jugadorActual && $tablero[0][2] == $jugadorActual) {
                $ganador = $jugadorActual == 2 ? $gato->jugadorX : $gato->jugadorO;
            } else
            if ($tablero[1][0] == $jugadorActual && $tablero[1][1] == $jugadorActual && $tablero[1][2] == $jugadorActual) {
                $ganador = $jugadorActual == 2 ? $gato->jugadorX : $gato->jugadorO;
            } else
            if ($tablero[2][0] == $jugadorActual && $tablero[2][1] == $jugadorActual && $tablero[2][2] == $jugadorActual) {
                $ganador = $jugadorActual == 2 ? $gato->jugadorX : $gato->jugadorO;
            } else //verticales
            if ($tablero[0][0] == $jugadorActual && $tablero[1][0] == $jugadorActual && $tablero[2][0] == $jugadorActual)
            {
                $ganador = $jugadorActual == 2 ? $gato->jugadorX : $gato->jugadorO;
            } else 
            if ($tablero[0][1] == $jugadorActual && $tablero[1][1] == $jugadorActual && $tablero[2][1] == $jugadorActual)
            {
                $ganador = $jugadorActual == 2 ? $gato->jugadorX : $gato->jugadorO;
            } else 
            if ($tablero[0][2] == $jugadorActual && $tablero[1][2] == $jugadorActual && $tablero[2][2] == $jugadorActual)
            {
                $ganador = $jugadorActual == 2 ? $gato->jugadorX : $gato->jugadorO;
            } else //diagonles
            if ($tablero[0][0] == $jugadorActual && $tablero[1][1] == $jugadorActual && $tablero[2][2] == $jugadorActual)
            {
                $ganador = $jugadorActual == 2 ? $gato->jugadorX : $gato->jugadorO;
            } else 
            if ($tablero[0][2] == $jugadorActual && $tablero[1][1] == $jugadorActual && $tablero[2][0] == $jugadorActual)
            {
                $ganador = $jugadorActual == 2 ? $gato->jugadorX : $gato->jugadorO;
            }
            
            

            if ($ganador !== null) {
                $gato->ganador = $ganador;
            }else
            {

            }

                $gato->save();
                event(new MyEvent("ataque",$partida->id));
                if($gato->ganador == null)
                {
                   $tableroFull = true;
                    for ($i = 0; $i < 3; $i++) {
                        for ($j = 0; $j < 3; $j++) {
                            if ($tablero[$i][$j] == 0) {
                                $tableroFull = false;
                                break;
                            }
                        }
                    }

                    if ($tableroFull) {
                        $gato->empate = true;
                        $gato->save();
                    }
                }
            }
            if($gato->ganador != null)
            {
            $resultados = new Resultado();
            if ($gato->ganador == $gato->jugadorO)
            {
            $resultados->partida_id = $partida->id;
            $resultados->ganador_id = $gato->jugadorO;
            $resultados->perdedor_id = $gato->jugadorX;  
            }
            else
            {
                $resultados->partida_id = $partida->id;
                $resultados->ganador_id = $gato->jugadorX;
                $resultados->perdedor_id = $gato->jugadorO;
            }

             $resultados->save();
            }


            return response()->json($gato,200);
    }
        return response()->json(["msg" => "Nop"], 401);
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