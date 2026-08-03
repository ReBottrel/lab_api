<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddFulltextIndexToAnimalsAnimalName extends Migration
{
    public function up()
    {
        $driver = Schema::getConnection()->getDriverName();

        if (! in_array($driver, ['mysql', 'mariadb'], true)) {
            return;
        }

        $exists = collect(DB::select("SHOW INDEX FROM animals WHERE Key_name = 'animals_animal_name_fulltext'"))->isNotEmpty();

        if (! $exists) {
            DB::statement('ALTER TABLE animals ADD FULLTEXT INDEX animals_animal_name_fulltext (animal_name)');
        }
    }

    public function down()
    {
        $driver = Schema::getConnection()->getDriverName();

        if (! in_array($driver, ['mysql', 'mariadb'], true)) {
            return;
        }

        $exists = collect(DB::select("SHOW INDEX FROM animals WHERE Key_name = 'animals_animal_name_fulltext'"))->isNotEmpty();

        if ($exists) {
            DB::statement('ALTER TABLE animals DROP INDEX animals_animal_name_fulltext');
        }
    }
}
