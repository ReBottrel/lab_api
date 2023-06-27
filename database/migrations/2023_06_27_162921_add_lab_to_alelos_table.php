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
        Schema::table('alelos', function (Blueprint $table) {
            $table->string('lab')->nullable()->after('alelo2');
            $table->string('data')->nullable()->after('lab');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alelos', function (Blueprint $table) {
            $table->dropColumn('lab');
            $table->dropColumn('data');
        });
    }
};
