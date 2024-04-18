<?php

use App\Http\Controllers\PartidasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

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
Route::post('verify',[AuthController::class,'verify'])->name('verify');
Route::post('logout',[AuthController::class,'logout'])->name('logout');

//Partidas
Route::post('partida/{id}',[PartidasController::class,'store'])->name('nueva_partida');
Route::put('/partida/join/{id}',[PartidasController::class,'join'])->name('unirse_partida');