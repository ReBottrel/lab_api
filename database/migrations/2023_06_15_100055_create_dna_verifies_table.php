<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dna_verifies', function (Blueprint $table) {
            $table->id();
            $table->integer('animal_id')->nullable();
            $table->integer('order_id')->nullable();
            $table->string('verify_code')->nullable();
            $table->string('verify_status')->nullable();
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
        Schema::dropIfExists('dna_verifies');
    }
};
