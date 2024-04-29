<?php
namespace App\Http\Controllers;
use App\Events\MyEvent;
use App\Events\BatallonEvento;
use App\Events\PartidasEvent;
use App\Models\Gato;
use App\Models\Partida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class PartidasGatoController extends Controller
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


            $gato = new Gato();
            $gato->jugadorO = $usuario;
            $collection1 = collect([]);
            for ($i = 0; $i < 9; $i++)
            {
                $collection1 = $collection1->push(0);
            } 
            
            $collection1_final = $collection1->split(3); 

            $gato->tablero1 = $collection1_final->toArray();


            $gato->save();

            Log::debug($gato->_id);
            
            $partida = new Partida();
            $partida->usuario_id = $usuario; 
            $partida->gato_id = $gato->_id;
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
                $gato = Gato::where('_id', $partida->gato_id)->get()->first();
    
                $gato->jugadorX = $user_id;
                $gato->turno = $user_id;
                
    
                $gato->save();

                $partida->invitado_id = $user_id;
                $partida->estado_id = 2;
                $partida->save();
    
                event(new MyEvent("alguien se une", $partida->id));
    
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
