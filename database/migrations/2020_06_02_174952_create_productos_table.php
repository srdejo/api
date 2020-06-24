<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->integer('precio');
            $table->string('imagen');
            $table->integer('precio_oferta')->nullable();
            $table->date('fecha_inicio_oferta')->nullable();
            $table->date('fecha_fin_oferta')->nullable();
            
            $table->unsignedBigInteger('negocio_id');
            $table->foreign('negocio_id')->references('id')->on('negocios');
            
            $table->unsignedBigInteger('categoria_id');
            $table->foreign('categoria_id')->references('id')->on('categorias');
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
        Schema::dropIfExists('productos');
    }
}
