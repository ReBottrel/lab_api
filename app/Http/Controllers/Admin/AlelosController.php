<?php

namespace App\Http\Controllers\Admin;

use App\Models\Alelo;
use App\Models\Animal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Marcador;
use Illuminate\Support\Facades\Http;
use RealRashid\SweetAlert\Facades\Alert;

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

    public function alelosCreate()
    {

        return view('admin.animais.alelos-create', get_defined_vars());
    }

    public function importTxt()
    {
        return view('admin.ordem-servico.import-txt');
    }

    public function alelosApi()
    {
        return view('admin.animais.alelos-api');
    }

    public function api(Request $request)
    {
        $response = Http::get('http://laboratorios.abccmm.org.br/api/Exames', ['registro' => $request->registro]);

        $data = $response->body();
        $data = json_decode($data, true);
        $data = collect($data);
        return $data;
    }
    public function store(Request $request)
    {
        // Obtém a resposta da API
        $response = Http::get('http://laboratorios.abccmm.org.br/api/Exames', ['registro' => $request->registro]);

        // Verifica se a resposta é bem-sucedida
        if ($response->successful()) {
            $data = $response->json();

            // Extrai os dados relevantes
            $animalData = $data['animal'];
            $exameData = $data['exame'];

            // Verifica se o animal já existe no banco de dados
            $animal = Animal::where('animal_name', $animalData['nomeAnimal'])->first();

            if (!$animal) {
                $randomNumber = mt_rand(0, 1000000);

                // Cria um novo animal no banco de dados
                $animal = Animal::create([
                    'animal_name' => $animalData['nomeAnimal'],
                    'especies' => 'EQUINA',
                    'breed' => 'MANGALARGA',
                    'sex' => $animalData['sexo'],
                    'birth_date' => $animalData['dataNascimento'],
                    'number_definitive' => $animalData['registro'],
                    'status' => 1,
                    'codlab' => $randomNumber,
                ]);
            }
            if ($exameData['alelos'] != null) {

                // Verifica se existem registros de alelos para o animal
                if (Alelo::where('animal_id', $animal->id)->exists()) {
                    return response()->json(['error' => 'existe']);
                }

                // Cria os registros de alelos
                foreach ($exameData['alelos'] as $item) {
                    Alelo::create([
                        'animal_id' => $animal->id,
                        'marcador' => $item['marcador'],
                        'alelo1' => $item['alelo1'],
                        'alelo2' => $item['alelo2'],
                    ]);
                }

                return response()->json(['success' => 'ok']);
            }
            return response()->json(['error' => 'vazio']);
        }

        return response()->json(['error' => 'erro']);
    }

    public function getAnimal(Request $request)
    {
        $animal = Animal::where('animal_name', $request->name)->first();
        $especie = $animal->especies ?? 'EQUINA'; // Define 'EQUINA' como valor padrão se $animal->especies for null
        $marcadores = Marcador::where('especie', $especie)->get();
        $view = view('admin.animais.includes.alelos-render', compact('marcadores'))->render();
        if ($animal) {
            return response()->json(get_defined_vars());
        }

        return response()->json(['error' => 'erro']);
    }

    public function storeAlelo(Request $request)
    {
        $animal = Animal::where('animal_name', $request->animal_name)->first();

        if ($animal) {
            if (Alelo::where('animal_id', $animal->id)->exists()) {
                return response()->json(['error' => 'existe']);
            }

            $alelos1 = $request->input('alelo1', []);
            $alelos2 = $request->input('alelo2', []);

            $alelos1 = array_map('strtoupper', $alelos1);
            $alelos2 = array_map('strtoupper', $alelos2);

            foreach ($alelos1 as $key => $item) {
                // Verifica se o campo 'alelo1' ou 'alelo2' está preenchido
                if ($item || $alelos2[$key]) {
                    Alelo::create([
                        'animal_id' => $animal->id,
                        'marcador' => $request->input('marcador.' . $key),
                        'alelo1' => $item,
                        'alelo2' => $alelos2[$key],
                    ]);
                }
            }

            return response()->json(['success' => 'ok']);
        }
    }
}
