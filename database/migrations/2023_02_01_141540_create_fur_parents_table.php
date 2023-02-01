<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFurParentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fur_parents', function (Blueprint $table) {
            $table->id();
            $table->integer('animal_id');
            $table->string('father_fur')->nullable();
            $table->string('mother_fur')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('father_name')->nullable();
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
        Schema::dropIfExists('fur_parents');
    }
}
