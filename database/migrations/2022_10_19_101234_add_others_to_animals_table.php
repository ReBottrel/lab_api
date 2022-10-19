<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOthersToAnimalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->integer('old_id')->nullable();
            $table->integer('owner_id')->nullable();
            $table->integer('fazenda_id')->nullable();
            $table->string('codlab')->nullable();
            $table->string('number_definitive')->nullable();
            $table->string('cod_bar')->nullable();
            $table->string('identificador')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('animals', function (Blueprint $table) {
            //
        });
    }
}
