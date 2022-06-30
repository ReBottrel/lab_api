<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderRequestPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_request_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('order_request_id')->nullable();
            $table->string('owner_name')->nullable();
            $table->string('email')->nullable();
            $table->string('location')->nullable();
            $table->integer('exam_id')->nullable();
            $table->string('category')->nullable();
            $table->string('animal')->nullable();
            $table->string('title')->nullable();
            $table->longText('short_description')->nullable();
            $table->float('value')->nullable();
            $table->string('requests')->nullable();
            $table->string('extra_value')->nullable();
            $table->integer('extra_requests')->default(0);
            $table->string('payment_id')->nullable();
            $table->integer('payment_status')->default(0);
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
        Schema::dropIfExists('order_request_payments');
    }
}
