<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCupomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cupoms', function (Blueprint $table) {
            $table->id();
            $table->string('code', 100);
            $table->string('type', 100)->nullable();
            $table->decimal('value', 10, 2)->nullable();
            $table->integer('used')->default(0);
            $table->dateTime('validity')->nullable();
            $table->integer('max_use')->default(1);
            $table->integer('max_use_client')->default(1);
            $table->integer('active')->default(1);
            $table->integer('client_id')->nullable();
            $table->integer('order_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('category_id')->nullable();
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
        Schema::dropIfExists('cupoms');
    }
}
