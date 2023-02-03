<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataColetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_coletas', function (Blueprint $table) {
            $table->id();
            $table->integer('id_order')->nullable();
            $table->integer('id_animal')->nullable();
            $table->string('data_coleta')->nullable();
            $table->string('data_laboratorio')->nullable();
            $table->string('data_recebimento')->nullable();
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
        Schema::dropIfExists('data_coletas');
    }
}
