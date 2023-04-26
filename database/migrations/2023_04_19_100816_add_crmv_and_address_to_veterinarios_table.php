<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCrmvAndAddressToVeterinariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('veterinarios', function (Blueprint $table) {
            $table->string('crmv')->nullable();
            $table->string('address')->nullable();
            $table->string('number')->nullable();
            $table->string('complement')->nullable();
            $table->string('district')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('cep')->nullable();
            $table->string('phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('veterinarios', function (Blueprint $table) {
            $table->dropColumn('crmv');
            $table->dropColumn('address');
            $table->dropColumn('number');
            $table->dropColumn('complement');
            $table->dropColumn('district');
            $table->dropColumn('city');
            $table->dropColumn('state');
            $table->dropColumn('cep');
            $table->dropColumn('phone');
        });
    }
}
