<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExameOldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exame_olds', function (Blueprint $table) {
            $table->id();
            $table->string('old_id')->nullable();
            $table->string('solicitacao_id')->nullable();
            $table->string('animal_id')->nullable();
            $table->string('genitor_id')->nullable();
            $table->string('genitora_id')->nullable();
            $table->string('nome')->nullable();
            $table->string('vlrunitario')->nullable();
            $table->string('vlrpago')->nullable();
            $table->string('vlrtotal')->nullable();
            $table->string('vlrdesconto')->nullable();
            $table->string('nome2')->nullable();
            $table->string('profissional_id')->nullable();
            $table->string('identificacao')->nullable();
            $table->string('dtcoleta')->nullable();
            $table->string('nranimaisexistentes')->nullable();
            $table->string('nrcontraprova')->nullable();
            $table->string('nrlacre')->nullable();
            $table->string('nrlacre_mormo')->nullable();
            $table->string('portador')->nullable();
            $table->string('identidade')->nullable();
            $table->string('orgaoemissor')->nullable();
            $table->text('local_animal')->nullable();
            $table->text('nome3')->nullable();
            $table->text('nome4')->nullable();
            $table->text('gestante_animal')->nullable();
            $table->string('nr_requsicao_aie')->nullable();
            $table->text('nr_requsicao_mormo')->nullable();
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
        Schema::dropIfExists('exame_olds');
    }
}
