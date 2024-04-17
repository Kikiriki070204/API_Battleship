<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resultados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('partida_id');
            $table->unsignedBigInteger('ganador_id');
            $table->unsignedBigInteger('perdedor_id');
            $table->foreign('ganador_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('perdedor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('partida_id')->references('id')->on('partidas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
