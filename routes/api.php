<?php

use App\Http\Controllers\GatoController;
use App\Http\Controllers\LobbiesController;
use App\Http\Controllers\PartidasController;
use App\Http\Controllers\PartidasGatoController;
use App\Http\Controllers\ResultadosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Auth
Route::post('register',[AuthController::class,'register']);
Route::get('activate/{user}',[AuthController::class,'activate'])->name('activate')->middleware('signed');

Route::post('login',[AuthController::class,'login'])->name('login');
Route::post('me',[AuthController::class,'me'])->name('me');
Route::post('verify',[AuthController::class,'verify'])->name('verify');
Route::post('logout',[AuthController::class,'logout'])->name('logout');

//Partidas
Route::post('partida',[PartidasController::class,'store'])->name('nueva_partida');
Route::put('/partida/join/{id}',[PartidasController::class,'join'])->name('unirse_partida');
Route::get('/partidasDisponibles',[PartidasController::class,'disponibles'])->name('partidas_disponibles');

//PartidasGato
Route::prefix('gato')->group(function ($router) {
Route::post('partida',[PartidasGatoController::class,'store'])->name('nueva_partida_gato');
Route::put('/partida/join/{id}',[PartidasGatoController::class,'join'])->name('unirse_partida_gato');
});


//Lobbies
Route::apiResource('lobby', LobbiesController::class);

//Gato
Route::apiResource('gato', GatoController::class);

//Resultados
Route::get('/partidasGanadas',[ResultadosController::class,'won_battles'])->name('partidas_ganadas');
Route::get('/partidasPerdidas',[ResultadosController::class,'lost_battles'])->name('partidas_perdidas');

Route::resource('posts', PostController::class)->only([
    'destroy', 'show', 'store', 'update'
]);