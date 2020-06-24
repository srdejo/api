<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHorariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('negocio_id')->nullable();
            $table->foreign('negocio_id')->references('id')->on('negocios');
            $table->integer('dia_semana');
            $table->time('hora_apertura');
            $table->time('hora_cierre');
            $table->enum('estado', ['Activo', 'Inactivo']);
            
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
        Schema::dropIfExists('horarios');
    }
}
