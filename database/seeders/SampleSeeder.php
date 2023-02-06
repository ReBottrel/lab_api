<?php

namespace Database\Seeders;

use App\Models\Sample;
use Illuminate\Database\Seeder;

class SampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            'Pelo',
            'Sangue',
            'Crina',
            'Osso',
            'Semem',
            'Sangue em FTA',
            'Swab'
        ];

        foreach ($types as $type) {
            $sample = new Sample;
            $sample->name = $type;
            $sample->save();
        }
    }
}
