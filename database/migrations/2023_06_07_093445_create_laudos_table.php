<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaudosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laudos', function (Blueprint $table) {
            $table->id();
            $table->integer('animal_id');
            $table->integer('mae_id');
            $table->integer('pai_id');
            $table->string('veterinario');
            $table->integer('veterinario_id');
            $table->integer('owner_id');
            $table->string('data_coleta')->nullable();
            $table->string('data_realizacao')->nullable();
            $table->string('data_lab')->nullable();
            $table->string('codigo_busca')->nullable();
            $table->text('observacao')->nullable();
            $table->text('conclusao')->nullable();
            $table->string('tipo')->nullable();
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
        Schema::dropIfExists('laudos');
    }
}
