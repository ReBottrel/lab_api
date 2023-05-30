<?php

namespace App\Http\Controllers\Admin;

use App\Models\Animal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class AlelosController extends Controller
{

    public function __construct()
    {
        ini_set('max_execution_time', 8000);
    }
    public function index()
    {
        return view('admin.animais.alelos');
    }

    public function api(Request $request)
    {
        $response = Http::get('http://laboratorios.abccmm.org.br/api/Exames', ['registro' => $request->registro]);

        $data = $response->body();
        $data = json_decode($data, true);
        $data = collect($data);
        // $animal = Animal::where('animal_name', $data['animal']['nomeAnimal'])->first();
        // $animal->number_definitive = $data['animal']['registro'];
        // $animal->save();
        return $data;
        // $data = json_decode($data);
        // $data = collect($data);

        // return view('teste', ['data' => $data]);
    }
}
