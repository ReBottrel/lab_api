<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAnimalPaisToAnimalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->string('registro_pai')->nullable();
            $table->string('pai')->nullable();
            $table->string('registro_mae')->nullable();
            $table->string('mae')->nullable();
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
