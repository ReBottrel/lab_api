<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOwnerIdToPedidoAnimalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pedido_animals', function (Blueprint $table) {
            $table->integer('owner_id')->after('id_animal')->nullable();
            $table->integer('user_id')->after('owner_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pedido_animals', function (Blueprint $table) {
            $table->dropColumn('owner_id');
            $table->dropColumn('user_id');
        });
    }
}
