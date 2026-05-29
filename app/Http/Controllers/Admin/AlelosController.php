<?php

namespace App\Http\Controllers\Admin;

use App\Models\Alelo;
use App\Models\Animal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\CodlabGenerator;
use App\Models\Marcador;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log as LogFacade;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Cache;

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
        $response = Http::timeout(60)->get('http://laboratorios.abccmm.org.br/api/Exames', ['registro' => $request->registro]);

        $data = $response->body();
        $data = json_decode($data, true);
        $data = collect($data);
        \Log::info([$data]);
        return $data;
    }
    public function store(Request $request)
    {
        $registro = trim((string) $request->registro);

        $this->logAlelosImport('Início da importação de alelos via API ABCCMM', [
            'registro' => $registro,
            'user_id' => Auth::id(),
            'user_name' => Auth::user()?->name,
        ]);

        $response = Http::timeout(60)->get('http://laboratorios.abccmm.org.br/api/Exames', ['registro' => $registro]);

        if (! $response->successful()) {
            $this->logAlelosImport('Falha na requisição à API de exames', [
                'registro' => $registro,
                'http_status' => $response->status(),
                'body' => $response->body(),
            ], 'error');

            return response()->json(['error' => 'erro']);
        }

        $data = $response->json();
        $animalData = $data['animal'] ?? null;
        $exameData = $data['exame'] ?? null;

        if (! $animalData || ! $exameData) {
            $this->logAlelosImport('Resposta da API sem dados de animal ou exame', [
                'registro' => $registro,
                'payload_keys' => array_keys($data ?? []),
            ], 'warning');

            return response()->json(['error' => 'erro']);
        }

        $animal = Animal::where('animal_name', $animalData['nomeAnimal'])->first();
        $marcadores = Marcador::where('especie', 'EQUINA')->get();
        $animalAcao = 'atualizado';

        if ($animal) {
            $codlabAnterior = $animal->codlab;
            $identificadorAnterior = $animal->identificador;

            if (! $animal->codlab) {
                $animal->codlab = CodlabGenerator::generate('EQU');
                $this->logAlelosImport('Codlab gerado para animal existente', [
                    'animal_id' => $animal->id,
                    'animal_name' => $animal->animal_name,
                    'codlab_anterior' => $codlabAnterior,
                    'codlab_novo' => $animal->codlab,
                    'registro' => $registro,
                ]);
            }

            $animal->identificador = $exameData['codigo'] ?? null;
            $animal->save();

            if ($identificadorAnterior !== $animal->identificador) {
                $this->logAlelosImport('Identificador do animal atualizado', [
                    'animal_id' => $animal->id,
                    'animal_name' => $animal->animal_name,
                    'identificador_anterior' => $identificadorAnterior,
                    'identificador_novo' => $animal->identificador,
                    'registro' => $registro,
                ]);
            }
        } else {
            $animalAcao = 'criado';
            $codlab = CodlabGenerator::generate('EQU');

            $animal = Animal::create([
                'animal_name' => $animalData['nomeAnimal'],
                'especies' => 'EQUINA',
                'breed' => 'MANGALARGA',
                'sex' => $animalData['sexo'],
                'birth_date' => $animalData['dataNascimento'],
                'number_definitive' => $animalData['registro'],
                'status' => 1,
                'codlab' => $codlab,
                'identificador' => $exameData['codigo'] ?? null,
            ]);

            $this->logAlelosImport('Animal criado na importação de alelos', [
                'animal_id' => $animal->id,
                'animal_name' => $animal->animal_name,
                'codlab' => $codlab,
                'registro_api' => $animalData['registro'] ?? $registro,
                'identificador' => $animal->identificador,
            ]);
        }

        if ($exameData['alelos'] === null) {
            $this->logAlelosImport('Exame sem alelos na resposta da API', [
                'animal_id' => $animal->id,
                'animal_name' => $animal->animal_name,
                'registro' => $registro,
                'exame_codigo' => $exameData['codigo'] ?? null,
                'laboratorio' => $exameData['laboratorio'] ?? null,
            ], 'warning');

            return response()->json(['error' => 'vazio']);
        }

        $stats = ['criados' => 0, 'atualizados' => 0, 'limpos' => 0];

        foreach ($marcadores as $marcador) {
            $apiAlelos = collect($exameData['alelos'])->where('marcador', $marcador->gene)->first();
            $alelo = Alelo::where('animal_id', $animal->id)
                ->where('marcador', $marcador->gene)
                ->first();

            if ($apiAlelos) {
                if ($alelo) {
                    $alelo->alelo1 = $apiAlelos['alelo1'];
                    $alelo->alelo2 = $apiAlelos['alelo2'];
                    $alelo->lab = $exameData['laboratorio'];
                    $alelo->data = $exameData['dataResultado'];
                    $alelo->save();
                    $stats['atualizados']++;
                } else {
                    Alelo::create([
                        'animal_id' => $animal->id,
                        'marcador' => $marcador->gene,
                        'alelo1' => $apiAlelos['alelo1'],
                        'alelo2' => $apiAlelos['alelo2'],
                        'lab' => $exameData['laboratorio'],
                        'data' => $exameData['dataResultado'],
                    ]);
                    $stats['criados']++;
                }
            } else {
                if ($alelo) {
                    $alelo->alelo1 = '';
                    $alelo->alelo2 = '';
                    $alelo->lab = $exameData['laboratorio'];
                    $alelo->data = $exameData['dataResultado'];
                    $alelo->save();
                    $stats['limpos']++;
                } else {
                    Alelo::create([
                        'animal_id' => $animal->id,
                        'marcador' => $marcador->gene,
                        'alelo1' => '',
                        'alelo2' => '',
                        'lab' => $exameData['laboratorio'],
                        'data' => $exameData['dataResultado'],
                    ]);
                    $stats['criados']++;
                }
            }
        }

        $this->logAlelosImport('Importação de alelos concluída com sucesso', [
            'animal_id' => $animal->id,
            'animal_name' => $animal->animal_name,
            'animal_acao' => $animalAcao,
            'codlab' => $animal->codlab,
            'registro' => $registro,
            'exame_codigo' => $exameData['codigo'] ?? null,
            'laboratorio' => $exameData['laboratorio'] ?? null,
            'data_resultado' => $exameData['dataResultado'] ?? null,
            'marcadores_processados' => $marcadores->count(),
            'alelos_na_api' => count($exameData['alelos']),
            'stats' => $stats,
            'user_id' => Auth::id(),
            'user_name' => Auth::user()?->name,
        ]);

        return response()->json(['success' => 'ok']);
    }

    private function logAlelosImport(string $message, array $context = [], string $level = 'info'): void
    {
        LogFacade::channel('alelos_import')->{$level}($message, $context);
    }
    public function getAnimal(Request $request)
    {
        $animal = Animal::with('alelos')->where('animal_name', $request->name)->first();
        $especie = $animal->especies; // Define 'EQUINA' como valor padrão se $animal->especies for null
        if ($especie != null) {
            $marcadores = Marcador::where('especie', $especie)->get();
        } else {
            $marcadores = [];
        }

        $view = view('admin.animais.includes.alelos-render', get_defined_vars())->render();
        if ($animal) {
            return response()->json(get_defined_vars());
        }

        return response()->json(['error' => 'erro']);
    }
    public function getAnimalCodlab(Request $request)
    {
        $animal = Animal::with('alelos')->where('codlab', $request->codlab)->first();
        $especie = $animal->especies; // Define 'EQUINA' como valor padrão se $animal->especies for null
        if ($especie != null) {
            $marcadores = Marcador::where('especie', $especie)->get();
        } else {
            $marcadores = [];
        }

        $view = view('admin.animais.includes.alelos-render', get_defined_vars())->render();
        if ($animal) {
            return response()->json(get_defined_vars());
        }

        return response()->json(['error' => 'erro']);
    }

    public function storeAlelo(Request $request)
    {
        $animal = Animal::with('alelos')->find($request->animal_id);

        if ($animal) {
            $alelos1 = $request->input('alelo1', []);
            $alelos2 = $request->input('alelo2', []);
            $alelos1 = array_map('strtoupper', $alelos1);
            $alelos2 = array_map('strtoupper', $alelos2);
            $marcadores = $request->input('marcador', []);

            foreach ($marcadores as $key => $marcador) {
                // Verifica se o marcador existe na relação de alelos
                $existingAlelo = $animal->alelos->where('marcador', $marcador)->first();

                if ($existingAlelo) {
                    // Atualiza o alelo existente
                    $existingAlelo->update([
                        'alelo1' => $alelos1[$key] !== null ? $alelos1[$key] : '',
                        'alelo2' => $alelos2[$key] !== null ? $alelos2[$key] : '',
                        'lab' => $request->input('lab'),
                        'data' => $request->input('data'),
                    ]);
                } else {
                    // Cria um novo alelo
                    Alelo::create([
                        'animal_id' => $animal->id,
                        'marcador' => $marcador,
                        'alelo1' => $alelos1[$key] !== null ? $alelos1[$key] : '',
                        'alelo2' => $alelos2[$key] !== null ? $alelos2[$key] : '',
                        'lab' => $request->input('lab'),
                        'data' => $request->input('data'),
                    ]);
                }
            }

            if ($animal->identificador) {
                $animal->update([
                    'identificador' => $request->input('identificador') ? $request->input('identificador') : 'LO23-' . substr($animal->codlab, 3),
                ]);
            } else {
                $animal->update([
                    'identificador' => $request->input('identificador') ? $request->input('identificador') : 'LO23-' . substr($animal->codlab, 3),
                ]);
            }

            return response()->json(['success' => 'ok']);
        }
    }


    public function destroyAlelos(Request $request)
    {
        $alelos = Alelo::where('animal_id', $request->id)->get();

        if ($alelos) {
            foreach ($alelos as $alelo) {
                $alelo->delete();
            }

            return response()->json(['success' => 'ok']);
        }
    }
    public function replicate(Request $request)
    {
        // Obtém o primeiro animal com os respectivos alelos
        $firstAnimal = Animal::with('alelos')->find($request->id);

        $otherAnimals = Animal::where('codlab',  $request->codlab)->get();

        if ($firstAnimal) {
            foreach ($otherAnimals as $animal) {
                if ($animal->id != $firstAnimal->id) {
                    foreach ($firstAnimal->alelos as $alelo) {
                        $existingAlelo = $animal->alelos->where('marcador', $alelo->marcador)->first();

                        if ($existingAlelo) {
                            $existingAlelo->update([
                                'alelo1' => $alelo->alelo1,
                                'alelo2' => $alelo->alelo2,
                                'lab' => $alelo->lab,
                                'data' => $alelo->data,
                            ]);
                        } else {
                            Alelo::create([
                                'animal_id' => $animal->id,
                                'marcador' => $alelo->marcador,
                                'alelo1' => $alelo->alelo1,
                                'alelo2' => $alelo->alelo2,
                                'lab' => $alelo->lab,
                                'data' => $alelo->data,
                            ]);
                        }
                    }
                }
            }
            \Log::info($otherAnimals);
            return response()->json(['success' => 'ok']);
        }
    }
}
