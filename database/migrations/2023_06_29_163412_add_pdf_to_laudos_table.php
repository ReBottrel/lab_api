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
        Schema::table('laudos', function (Blueprint $table) {
            $table->integer('ordem_id')->nullable()->after('id');
            $table->integer('order_id')->nullable()->after('ordem_id');
            $table->text('pdf')->nullable()->after('order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('laudos', function (Blueprint $table) {
            $table->dropColumn('ordem_id');
            $table->dropColumn('order_id');
            $table->dropColumn('pdf');
        
        });
    }
};
