<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use App\Models\Animal;
use Illuminate\Http\Request;
use App\Models\ResenhaAnimal;
use Illuminate\Support\Facades\Http;

class TesteController extends Controller
{
    public function __construct()
    {
        ini_set('max_execution_time', 8000);
    }
    public function index()
    {
        return view('admin.teste');
    }

    public function duplicate()
    {
        $owner = Owner::get();
        $ownernovo = $owner->groupBy('owner_name')->filter(function ($item) {
            return $item->count() > 1;
        })->map(function ($item) {
            return $item->count();
        });
        \Log::info($ownernovo->toArray());
    }

    public function api()
    {
        $response = Http::get('http://laboratorios.abccmm.org.br/api/Exames', ['registro' => '044806']);

        $data = $response->body();
        $data = json_decode($data, true);
        $data = collect($data);
        $animal = Animal::where('animal_name', $data['animal']['nomeAnimal'])->first();
        $animal->number_definitive = $data['animal']['registro'];
        $animal->save();
        return 'ok';
        // $data = json_decode($data);
        // $data = collect($data);

        // return view('teste', ['data' => $data]);
    }
}
