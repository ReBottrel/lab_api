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
            $table->string('register_pai')->nullable()->after('pai_id');
            $table->string('register_mae')->nullable()->after('register_pai');
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
            $table->dropColumn('register_pai');
            $table->dropColumn('register_mae');
        });
    }
};
