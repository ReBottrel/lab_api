<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCpfeportariaToVeterinariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('veterinarios', function (Blueprint $table) {
            $table->string('cpf')->nullable();
            $table->string('portaria')->nullable();
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
            $table->dropColumn('cpf');
            $table->dropColumn('portaria');
        });
    }
}
