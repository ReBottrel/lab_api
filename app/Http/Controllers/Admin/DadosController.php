<?php

namespace App\Http\Controllers\Admin;

use App\Models\Owner;
use App\Models\Animal;
use App\Models\Tecnico;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DadosController extends Controller
{
    public function getOwner(Request $request)
    {
        $query = $request->get('q');
        $results = [];

        if ($query) {
            $owners = Owner::where('owner_name', 'like', "%{$query}%")
                ->limit(20)
                ->get();
        }
        return response()->json($owners);
    }

    public function getTecnico(Request $request)
    {
        $query = $request->get('q');
        $results = [];

        if ($query) {
            $tecnicos = Tecnico::where('professional_name', 'like', "%{$query}%")
                ->limit(20)
                ->get();
        }
        return response()->json($tecnicos);
    }
    public function getAnimal(Request $request)
    {
        $query = $request->get('q');
        $results = [];

        if ($query) {
            $animals = Animal::where('animal_name', 'like', "%{$query}%")
                ->limit(20)
                ->get();
        }
        return response()->json($animals);
    }
}
