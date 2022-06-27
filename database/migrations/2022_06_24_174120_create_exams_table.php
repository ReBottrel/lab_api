<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->enum('category', ['dna', 'sorologia'])->nullable();
            $table->enum('animal', ['equinos', 'bovinos', 'asininos_muares'])->nullable();
            $table->string('title')->nullable();
            $table->string('short_description')->nullable();
            $table->float('value')->nullable();
            $table->integer('requests')->nullable();
            $table->float('extra_value')->nullable();
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
        Schema::dropIfExists('exams');
    }
}
