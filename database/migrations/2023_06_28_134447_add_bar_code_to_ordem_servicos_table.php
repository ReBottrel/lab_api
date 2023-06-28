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
        Schema::table('ordem_servicos', function (Blueprint $table) {
            $table->string('bar_code')->nullable()->after('data_bar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ordem_servicos', function (Blueprint $table) {
            $table->dropColumn('bar_code');
        });
    }
};
