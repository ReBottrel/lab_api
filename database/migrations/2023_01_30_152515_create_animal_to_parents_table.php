<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnimalToParentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animal_to_parents', function (Blueprint $table) {
            $table->id();
            $table->integer('animal_id');
            $table->string('animal_name')->nullable();
            $table->string('especies')->nullable();
            $table->string('animal_register')->nullable();
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
        Schema::dropIfExists('animal_to_parents');
    }
}
