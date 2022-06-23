<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use App\Models\Animal;
use Illuminate\Http\Request;

class AnimalController extends Controller
{
    public function animalPost(Request $request)
    {
        $user = user_token();
        // \Log::info($request->all());
        $create_animal = collect($request->all())->put('user_id', $user->id)->forget(['owner']);
        $animal = Animal::create($create_animal->toArray());
        $create_owner = collect($request->owner)->put('user_id', $user->id)->put('animal_id', $animal->id);
        Owner::create($create_owner->toArray());
    }
}
