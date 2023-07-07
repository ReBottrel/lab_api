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
            $table->date('data_payment')->nullable()->after('bar_code');
            $table->string('rg_pai')->nullable()->after('id_abccmm');
            $table->string('rg_mae')->nullable()->after('rg_pai');
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
            $table->dropColumn('data_payment');
            $table->dropColumn('rg_pai');
            $table->dropColumn('rg_mae');
        
        });
    }
};
