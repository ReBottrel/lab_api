<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSorologiaDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sorologia_dates', function (Blueprint $table) {
            $table->id();
            $table->integer('pedido_id')->nullable();
            $table->integer('animal_id')->nullable();
            $table->integer('order_id')->nullable();
            $table->string('data_recebimento')->nullable();
            $table->string('data_resultado')->nullable();
            $table->string('numero_aie')->nullable();
            $table->string('numero_mormo')->nullable();
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
        Schema::dropIfExists('sorologia_dates');
    }
}
