<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdemServicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordem_servicos', function (Blueprint $table) {
            $table->id();
            $table->integer('animal_id')->nullable();
            $table->integer('owner_id')->nullable();
            $table->integer('lote')->nullable();
            $table->string('animal')->nullable();
            $table->string('codlab')->nullable();
            $table->string('id_abccmm')->nullable();
            $table->string('tipo_exame')->nullable();
            $table->string('proprietario')->nullable();
            $table->string('tecnico')->nullable();
            $table->dateTime('data')->nullable();
            $table->string('status')->nullable();
            $table->string('observacao')->nullable();
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
        Schema::dropIfExists('ordem_servicos');
    }
}
