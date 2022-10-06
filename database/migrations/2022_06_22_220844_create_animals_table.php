<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnimalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animals', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('register_number_brand')->nullable();
            $table->string('animal_name')->nullable();
            $table->text('classification')->nullable();
            $table->string('especies')->nullable();
            $table->string('breed')->nullable();
            $table->string('sex')->nullable();
            $table->string('age')->nullable();
            $table->string('utility')->nullable();
            $table->string('animal_location')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('number_existing_equines')->nullable();
            $table->string('birth_date')->nullable();
            $table->string('fur')->nullable();
            $table->longText('description')->nullable();
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
        Schema::dropIfExists('animals');
    }
}
