<?php

namespace App\Console\Commands;

use App\Models\Animal;
use App\Models\AnimalToParent;
use Illuminate\Console\Command;

class MigrateAnimalsToParents extends Command
{
    protected $signature = 'migrate:animals-to-parents';
    protected $description = 'Migrate animals to the animal_to_parents table';

    public function handle()
    {
        $animals = Animal::all();

        foreach ($animals as $animal) {

            $pai_id = null;
            $mae_id = null;

            $pai = Animal::where('animal_name', $animal->pai)->first();
            $mae = Animal::where('animal_name', $animal->mae)->first();

            if ($pai) {
                $pai_id = $pai->id;
            }
            if ($mae) {
                $mae_id = $mae->id;
            }

            AnimalToParent::create([
                'animal_id'       => $animal->id,
                'mae_id'          => $mae_id,
                'pai_id'          => $pai_id,
                'animal_name'     => $animal->animal_name,
                'especies'        => $animal->especies,
                'animal_register' => $animal->register_number_brand,
            ]);
        }

        $this->info('Data migration completed.');
    }
}
