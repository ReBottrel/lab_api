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
        Schema::table('animal_to_parents', function (Blueprint $table) {
            $table->integer('mae_id')->nullable()->after('animal_id');
            $table->integer('pai_id')->nullable()->after('mae_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('animal_to_parents', function (Blueprint $table) {
            $table->dropColumn('mae_id');
            $table->dropColumn('pai_id');
        });
    }
};
