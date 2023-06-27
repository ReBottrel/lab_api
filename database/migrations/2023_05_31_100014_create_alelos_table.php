<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlelosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alelos', function (Blueprint $table) {
            $table->id();
            $table->integer('animal_id')->nullable();
            $table->string('marcador')->nullable();
            $table->string('alelo1')->nullable();
            $table->string('alelo2')->nullable();
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
        Schema::dropIfExists('alelos');
    }
}
