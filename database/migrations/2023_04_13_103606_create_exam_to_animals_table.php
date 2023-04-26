<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamToAnimalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_to_animals', function (Blueprint $table) {
            $table->id();
            $table->integer('exam_id')->nullable();
            $table->integer('animal_id')->nullable();
            $table->integer('order_id')->nullable();
            $table->integer('status')->nullable();
            $table->string('total_price')->nullable();
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
        Schema::dropIfExists('exam_to_animals');
    }
}
