<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTecnicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tecnicos', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('professional_name')->nullable();
            $table->string('document')->nullable();
            $table->string('email')->nullable();
            $table->string('fone')->nullable();
            $table->string('cell')->nullable();
            $table->integer('whatsapp')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('address')->nullable();
            $table->string('number')->nullable();
            $table->string('complement')->nullable();
            $table->string('district')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('nr_portaria_mormo')->nullable();
            $table->string('registro_profissional')->nullable();
            $table->string('conselho')->nullable();
            $table->string('forma_tratamento')->nullable();
            $table->string('sexo')->nullable();
            $table->string('parceiro_id')->nullable();
            $table->text('observacao')->nullable();
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('tecnicos');
    }
}
