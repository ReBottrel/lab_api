<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResenhaAnimalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resenha_animals', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('animal_id')->nullable();
            $table->enum('resenha', ['right_side', 'left_side', 'snout', 'top_line_of_eyes', 'neck_bottom_view', 'hind_limbs_and_rear_view', 'forelimbs_rear_view'])->nullable();
            $table->string('photo_path')->nullable();
            $table->string('brand_id')->nullable();
            $table->longText('localization')->nullable();
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
        Schema::dropIfExists('resenha_animals');
    }
}
