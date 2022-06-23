<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\Request;

class AnimalController extends Controller
{
    public function animalPost(Request $request)
    {
        $user = user_token();
        $create_animal = collect($request->all())->put('user_id', $user->id);
        \Log::info($request->all());
        // $animal = Animal::create($create_animal->toArray());
        // \Log::info($create_animal->toArray());
    }
}
