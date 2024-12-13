<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Log;
use App\Models\Exam;
use App\Models\Alelo;
use App\Models\Laudo;
use App\Models\Animal;
use App\Models\Result;
use App\Models\Marcador;
use App\Models\DnaVerify;
use App\Models\OrderLote;
use App\Models\OrdemServico;
use App\Models\OrderRequest;
use Illuminate\Http\Request;
use App\Models\AnimalToParent;
use App\Models\OrderRequestPayment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Picqer\Barcode\BarcodeGeneratorPNG;


class OrdemServicoController extends Controller
{
    public function __construct()
    {
        ini_set('max_execution_time', 600000);
    }
    public function store(Request $request)
    {
        $order = OrderRequest::find($request->order);
        if (!$order) {
            return response()->json(['error' => 'Pedido não encontrado.'], 404);
        }

        // if (OrdemServico::where('order', $order->id)->exists()) {
        //     return response()->json(['error' => 'Já existe uma ordem de serviço para este pedido.'], 400);
        // }

        $orderRequests = OrderRequestPayment::where('order_request_id', $order->id)->where('payment_status', 1)->get();
        $lote = OrderLote::updateOrCreate([
            'order_id' => $order->id,
            'owner' => $order->creator,
        ]);




        $animalIds = $orderRequests->pluck('animal_id')->unique()->toArray();
        $animals = Animal::whereIn('id', $animalIds)->get()->keyBy('id');

        foreach ($orderRequests as $item) {
            $exame = Exam::find($item->exam_id);

            $animal = $animals[$item->animal_id] ?? Animal::where('animal_name', $item->animal)->first();

            // Atualizar o identificador apenas se estiver vazio
            if (empty($animal->identificador) && !empty($animal->codlab)) {
                // Extrair apenas os números do codlab
                $codlabNumber = preg_replace('/[^\d]/', '', $animal->codlab);

                //pegar o ano atual com 2 digitos
                $ano = date('y');


                // Atualizar o identificador com o formato desejado
                $animal->update([
                    'identificador' => 'LO' . $ano . '-' . $codlabNumber,
                ]);
            }

            $data = Carbon::parse($item->updated_at)->addWeekdays($exame->days);

            $dna_verify = DnaVerify::where('animal_id', $item->animal_id)->latest('created_at')->first();

            if (OrdemServico::where('animal_id', $animal->id)->where('order', $order->id)->exists()) {
                continue; // Se existir, pula para a próxima iteração
            }

            if (!$dna_verify) {
                $tipo = $this->determineTipo($animal->especies);

                if (!$tipo) {
                    \Log::error("Tipo não determinado para a espécie: {$animal->especies}. Animal ID: {$item->animal_id}");
                    continue; // pula para a próxima iteração do loop
                }

                $dna_verify = DnaVerify::create([
                    'animal_id' => $item->animal_id,
                    'order_id' => $order->id,
                    'verify_code' => $tipo,
                ]);
            }

            $sigla = $this->determineSigla($animal->especies);

            if ($animal->codlab === null) {
                $animal->update(['codlab' => $this->generateUniqueCodlab($sigla)]);
            }

            $ordem =  OrdemServico::create([
                'order' => $order->id,
                'animal_id' => $animal->id,
                'owner_id' => $order->owner_id,
                'lote' => $lote->id,
                'animal' => $animal->animal_name,
                'codlab' => $animal->codlab,
                'id_abccmm' => $animal->register_number_brand,
                'tipo_exame' => $dna_verify->verify_code,
                'proprietario' => $order->creator,
                'tecnico' => $order->technical_manager,
                'data' => $data,
                'observacao' => $animal->description,
                'data_payment' => $item->updated_at,
                'rg_pai' => $animal->registro_pai,
                'rg_mae' => $animal->registro_mae,
                'status' => 1,
            ]);
        }

        $log = Log::create([
            'user' => Auth::user()->name,
            'action' => 'Criou uma nova ordem de serviço',
            'ordem_id' => $ordem->id ?? null,
            'order_id' => $order->id,
        ]);

        return response()->json('success', 200);
    }

    private function determineTipo($especies)
    {
        switch ($especies) {
            case 'EQUINA':
                return 'EQUTR';
            case 'MUARES':
                return 'MUATR';
            case 'ASININO':
            case 'PEGA_EQUINO':
                return 'PEGTR';
            case 'BOVINA':
                return 'BOVTR';
            default:
                return 'EQUTR';
        }
    }

    private function determineSigla($especies)
    {
        return substr($especies, 0, 3) ?: 'EQU';
    }

    private function generateUniqueCodlab($sigla)
    {
        // Buscar o último animal criado com esta sigla, ordenado pela data de criação
        $lastAnimal = Animal::latest('created_at')
            ->first();

        if ($lastAnimal) {
            // Extrair o número do codlab do último animal
            $lastNumber = (int) substr($lastAnimal->codlab, 3);
            $nextNumber = $lastNumber + 1;
        } else {
            // Se não existir nenhum animal com esta sigla, começar do 200000
            $nextNumber = 200000;
        }
        // Verificar se o próximo número já existe (por segurança)
        while (Animal::where('codlab', $sigla . strval($nextNumber))->exists()) {
            $nextNumber++;
        }

        return $sigla . strval($nextNumber);
    }

    public function index()
    {
        $ordemServicos = OrderLote::paginate(10);
        return view('admin.ordem-servico.index', get_defined_vars());
    }

    public function show($id)
    {
        $ordem = OrderLote::find($id);
        $ordemServicos = OrdemServico::where('lote', $id)->get();
        return view('admin.ordem-servico.show', get_defined_vars());
    }

    public function importFile(Request $request)
    {
        // Verificar se um arquivo foi enviado
        if ($request->hasFile('file')) {
            // Obter o arquivo do campo de entrada
            $file = $request->file('file');

            // Verificar se o arquivo é válido
            if ($file->isValid()) {
                // Caminho para salvar o arquivo
                $filePath = storage_path('app/files/') . $file->getClientOriginalName();

                // Mover o arquivo para o diretório desejado
                $file->move(storage_path('app/files'), $file->getClientOriginalName());

                // Ler o conteúdo do arquivo
                $fileContent = file_get_contents($filePath);

                // Quebrar o conteúdo do arquivo em linhas
                $lines = explode("\n", $fileContent);

                // Iterar pelas linhas do arquivo
                foreach ($lines as $line) {
                    // Quebrar a linha em colunas separadas por tabulação
                    $columns = explode("\t", $line);

                    // Verificar se a coluna com o índice 1 existe
                    if (isset($columns[1])) {
                        $sampleName = $columns[1];
                        $animal = Animal::where('codlab', $sampleName)->first();
                        if ($animal) {
                            // Remover espaços e asteriscos dos valores dos alelos
                            $marcador = trim(str_replace('*', '', $columns[2]));
                            $alelo1 = trim(str_replace('*', '', $columns[3]));
                            $alelo2 = trim(str_replace('*', '', $columns[4]));

                            // Se alelo1 e alelo2 estiverem vazios, manter como vazios
                            if (!empty($alelo1) || !empty($alelo2)) {
                                // Se alelo1 estiver vazio, copiar valor de alelo2
                                if (empty($alelo1)) {
                                    $alelo1 = $alelo2;
                                }

                                // Se alelo2 estiver vazio, copiar valor de alelo1
                                if (empty($alelo2)) {
                                    $alelo2 = $alelo1;
                                }
                            }

                            // Verificar se o alelo já existe
                            $alelo = Alelo::where('animal_id', $animal->id)
                                ->where('marcador', $marcador)
                                ->first();

                            if ($alelo) {
                                // Se o alelo existir, atualizá-lo
                                $alelo->update([
                                    'alelo1' => $alelo1,
                                    'alelo2' => $alelo2,
                                    'lab' => 'Loci Genética Laboratorial',
                                    'data' => Carbon::now(),
                                ]);
                            } else {
                                // Se o alelo não existir, criá-lo
                                $alelo = Alelo::create([
                                    'animal_id' => $animal->id,
                                    'marcador' => $marcador,
                                    'alelo1' => $alelo1,
                                    'alelo2' => $alelo2,
                                    'lab' => 'Loci Genética Laboratorial',
                                    'data' => Carbon::now(),
                                ]);
                            }

                            // ...

                        }
                    } else {
                        // Tratar o caso em que a colunsa não existe
                        $sampleName = null; // Ou qualquer outro valor padrão que faça sentido para o seu caso
                    }
                }

                $log = Log::create([
                    'user' => Auth::user()->name,
                    'action' => 'Importou um arquivo txt de alelos',
                ]);

                // Retorne uma resposta adequada após a importação
                return redirect()->back()->with('success', 'Arquivo importado com sucesso');
            }
        }

        // Caso nenhum arquivo tenha sido enviado ou o arquivo seja inválido
        return response()->json(['message' => 'Nenhum arquivo válido enviado'], 400);
    }




    public function compareAlelo($id)
    {
        $ordem = OrdemServico::find($id);
        $animal = Animal::with('alelos')->find($ordem->animal_id);
        $dna_verify = DnaVerify::where('animal_id', $ordem->animal_id)->orderBy('id', 'desc')
            ->first();

        if ($animal->especies == null) {
            $animal->update([
                'especies' => "EQUINA",
            ]);
        }
        $laudo = Laudo::where('ordem_id', $id)
            ->orderBy('id', 'desc')
            ->first();
        $result = Result::where('ordem_servico', $id)
            ->orderBy('id', 'desc')
            ->first();
        $sigla = substr($animal->especies, 0, 3);
        $pai = null;
        $mae = null;
        $relation = AnimalToParent::where('animal_id', $animal->id)->first();
        $marks = Marcador::where('especie', $animal->especies)->get();
        switch ($dna_verify->verify_code) {
            case $sigla . 'PD':
                if ($relation) {
                    if ($relation->register_pai) {
                        $pai = Animal::with('alelos')->where('number_definitive', $relation->register_pai)->first();
                    }
                    if (!$pai && $relation->pai_id) {
                        $pai = Animal::with('alelos')->find($relation->pai_id);
                    }
                }
                break;
            case $sigla . 'MD':
                if ($relation) {

                    if ($relation->register_mae) {
                        $mae = Animal::with('alelos')->where('number_definitive', $relation->register_mae)->first();
                    }
                    if (!$mae && $relation->mae_id) {
                        $mae = Animal::with('alelos')->find($relation->mae_id);
                    }
                }
                break;
            case $sigla . 'TR':
                if ($relation) {
                    if ($relation->register_pai) {
                        $pai = Animal::with('alelos')->where('number_definitive', $relation->register_pai)->first();
                    }
                    if (!$pai && $relation->pai_id) {
                        $pai = Animal::with('alelos')->find($relation->pai_id);
                    }

                    if ($relation->register_mae) {
                        $mae = Animal::with('alelos')->where('number_definitive', $relation->register_mae)->first();
                    }
                    if (!$mae && $relation->mae_id) {
                        $mae = Animal::with('alelos')->find($relation->mae_id);
                    }
                }
                break;
            default:
                break;
        }


        // dd($dna_verify);


        return view('admin.ordem-servico.alelo-compare', get_defined_vars());
    }

    public function dataBarCode(Request $request)
    {
        $ordem = OrdemServico::find($request->ordem_id);
        $ordem->update([
            'data_bar' => $request->data
        ]);

        $animal = Animal::find($ordem->animal_id);
        $animal->update([
            'status' => 3,
        ]);

        $log = Log::create([
            'user' => Auth::user()->name,
            'action' => 'Atualizou a data de entrada na area tecnica',
        ]);

        return response()->json($ordem);
    }



    public function analise(Request $request)
    {
        $ordem = OrdemServico::find($request->ordem);
        $animal = Animal::with('alelos')->find($ordem->animal_id);
        $dna_verify = DnaVerify::where('animal_id', $animal->id)->latest('created_at')->first();
        $sigla = substr($animal->especies, 0, 3);
        $marcadores = Marcador::where('especie', $animal->especies)->get();
        $result = Result::where('ordem_servico', $ordem->id)
            ->orderBy('id', 'desc')
            ->first();
        $pai = null;
        $mae = null;
        $relation = AnimalToParent::where('animal_id', $animal->id)->first();
        switch ($dna_verify->verify_code) {
            case $sigla . 'PD':
                if ($relation) {
                    if ($relation->register_pai) {
                        $pai = Animal::with('alelos')->where('number_definitive', $relation->register_pai)->first();
                    }
                    if (!$pai && $relation->pai_id) {
                        $pai = Animal::with('alelos')->find($relation->pai_id);
                    }
                }
                break;
            case $sigla . 'MD':
                if ($relation) {

                    if ($relation->register_mae) {
                        $mae = Animal::with('alelos')->where('number_definitive', $relation->register_mae)->first();
                    }
                    if (!$mae && $relation->mae_id) {
                        $mae = Animal::with('alelos')->find($relation->mae_id);
                    }
                }
                break;
            case $sigla . 'TR':
                if ($relation) {
                    if ($relation->register_pai) {
                        $pai = Animal::with('alelos')->where('number_definitive', $relation->register_pai)->first();
                    }
                    if (!$pai && $relation->pai_id) {
                        $pai = Animal::with('alelos')->find($relation->pai_id);
                    }

                    if ($relation->register_mae) {
                        $mae = Animal::with('alelos')->where('number_definitive', $relation->register_mae)->first();
                    }
                    if (!$mae && $relation->mae_id) {
                        $mae = Animal::with('alelos')->find($relation->mae_id);
                    }
                }
                break;
            default:
                break;
        }

        $alelosMae = [];
        $alelosPai = [];
        $laudoMae = [];
        $laudoMaeExclui = [];
        $alelosPai = [];
        $laudoGeral = [];
        $laudoPai = [];

        // Comparar alelos entre mãe e animal
        // Comparar alelos entre mãe e animal
        if ($mae != null) {
            $laudoMae = [];
            foreach ($animal->alelos as $animalAlelo) {
                $result = '';
                $maeAlelo = null; // Inicialize a variável $maeAlelo fora do loop

                // Verifica se o alelo do animal possui asterisco ou está vazio
                if (strpos($animalAlelo->alelo1, '*') !== false || strpos($animalAlelo->alelo2, '*') !== false || empty(trim($animalAlelo->alelo1)) || empty(trim($animalAlelo->alelo2))) {
                    $result = 'V';
                }

                if (!$result) {
                    $aleloEncontrado = false;
                    foreach ($mae->alelos as $maeAleloAtual) {
                        if ($animalAlelo->marcador == $maeAleloAtual->marcador) {
                            // Verifica se o alelo da mãe possui asterisco ou está vazio apenas para o marcador correspondente
                            if (strpos($maeAleloAtual->alelo1, '*') !== false || strpos($maeAleloAtual->alelo2, '*') !== false || empty(trim($maeAleloAtual->alelo1)) || empty(trim($maeAleloAtual->alelo2))) {
                                $result = 'V';
                                break;
                            } elseif (($maeAleloAtual->alelo1 !== $animalAlelo->alelo1 && $maeAleloAtual->alelo2 !== $animalAlelo->alelo2) && ($maeAleloAtual->alelo1 !== $animalAlelo->alelo2 && $maeAleloAtual->alelo2 !== $animalAlelo->alelo1)) {
                                $result = '';
                            } else {
                                $result = 'M';
                            }

                            // Atribua os valores dos alelos da mãe à variável $maeAlelo
                            $maeAlelo = $maeAleloAtual;
                        }
                    }
                }

                $laudoMae[] = [
                    'marcador' => $animalAlelo->marcador,
                    'alelo1' => $maeAlelo->alelo1 ?? null,
                    'alelo2' => $maeAlelo->alelo2 ?? null,
                    'filho1' => $animalAlelo->alelo1,
                    'filho2' => $animalAlelo->alelo2,
                    'include' => $result
                ];
            }
        } else {
            $laudoMae = null;
        }


        // Comparar alelos entre pai e animal
        if ($pai != null) {
            $laudoPai = [];
            foreach ($animal->alelos as $animalAlelo) {
                $result = '';
                $paiAleloNovo = null; // Inicialize a variável $paiAlelo fora do loop
                // Verifica se o alelo do animal possui asterisco ou está vazio
                if (strpos($animalAlelo->alelo1, '*') !== false || strpos($animalAlelo->alelo2, '*') !== false || empty(trim($animalAlelo->alelo1)) || empty(trim($animalAlelo->alelo2))) {
                    $result = 'V';
                }

                if (!$result) {
                    foreach ($pai->alelos as $paiAlelo) {
                        if ($animalAlelo->marcador == $paiAlelo->marcador) {
                            // Verifica se o alelo do pai possui asterisco ou está vazio apenas para o marcador correspondente
                            if (strpos($paiAlelo->alelo1, '*') !== false || strpos($paiAlelo->alelo2, '*') !== false || empty(trim($paiAlelo->alelo1)) || empty(trim($paiAlelo->alelo2))) {
                                $result = 'V';
                                break;
                            } elseif (($paiAlelo->alelo1 !== $animalAlelo->alelo1 && $paiAlelo->alelo2 !== $animalAlelo->alelo2) && ($paiAlelo->alelo1 !== $animalAlelo->alelo2 && $paiAlelo->alelo2 !== $animalAlelo->alelo1)) {
                                $result = '';
                            } else {

                                $result = 'P';
                            }
                            $paiAleloNovo = $paiAlelo;
                        }
                    }
                }

                $laudoPai[] = [
                    'marcador' => $animalAlelo->marcador,
                    'alelo1' => $paiAleloNovo->alelo1 ?? null,
                    'alelo2' => $paiAleloNovo->alelo2 ?? null,
                    'filho1' => $animalAlelo->alelo1,
                    'filho2' => $animalAlelo->alelo2,
                    'include' => $result
                ];
            }
            if ($mae != null) {
                // \Log::info($laudoMae);
                foreach ($laudoMae as &$maeAl) {
                    $variavel = collect($laudoPai)->where('marcador', $maeAl['marcador']);
                    $varKey = $variavel->keys()[0];
                    $variavel = $variavel->first();
                    $points = 0;
                    // \Log::info($variavel);
                    // Comparação dos alelos entre mãe e filho
                    $arraypai = [$variavel['alelo1'], $variavel['alelo2']];
                    $arrayfilho = [$variavel['filho1'], $variavel['filho2']];
                    $arrayMae = [$maeAl['alelo1'], $maeAl['alelo2']];

                    // Verifica se todos os alelos do filho estão presentes na mãe e no pai
                    // $alelosPresentes = true;
                    $alele1status = false;
                    $alele2status = false;

                    // foreach ($arrayfilho as $alelo) {
                    if (in_array($variavel['filho1'], $arrayMae) || in_array($variavel['filho1'], $arraypai)) {
                        $alele1status = true;
                        // \Log::info([$alelo, $arrayMae, $arraypai]);
                    }
                    if (in_array($variavel['filho2'], $arraypai) || in_array($variavel['filho2'], $arrayMae)) {
                        $alele2status = true;
                        // \Log::info([$alelo, $arrayMae, $arraypai]);
                    }



                    $maefilter = array_filter($arrayMae, function ($value) {
                        if (strpos($value, '*') !== false || empty(trim($value))) {
                            return false;
                        }
                        return true;
                    });
                    $paifilter = array_filter($arraypai, function ($value) {
                        if (strpos($value, '*') !== false || empty(trim($value))) {
                            return false;
                        }
                        return true;
                    });

                    if (count($maefilter) >= 1 && count($paifilter) >= 1) {

                        if ($alele1status && $alele2status) {
                        } else {

                            $alelo1statuspai = false;
                            $alelo2statuspai = false;
                            $alelo1statusmae = false;
                            $alelo2statusmae = false;
                            if (in_array($variavel['filho1'], $arrayMae)) {
                                $alelo1statusmae = true;
                            }
                            if (in_array($variavel['filho1'], $arraypai)) {
                                $alelo1statuspai = true;
                            }
                            if (in_array($variavel['filho2'], $arrayMae)) {
                                $alelo2statusmae = true;
                            }
                            if (in_array($variavel['filho2'], $arraypai)) {
                                $alelo2statuspai = true;
                            }


                            if ($alelo1statusmae || $alelo1statuspai) {
                                // \Log::info([$alelo2statusmae, $alelo2statuspai, $maeAl['marcador']]);
                                if ($alelo1statusmae && $alelo1statuspai) {
                                    if (!$alelo2statusmae && !$alelo2statuspai) {
                                        $maeAl['include'] = 'I';
                                        $laudoPai[$varKey]['include'] = '  ';
                                    }
                                }
                            } elseif ($alelo2statusmae || $alelo2statuspai) {
                                if ($alelo2statusmae && $alelo2statuspai) {
                                    if (!$alelo1statusmae && !$alelo1statuspai) {
                                        $maeAl['include'] = 'I';
                                        $laudoPai[$varKey]['include'] = '  ';
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $laudoPai = null;
        }

        return response()->json([
            'laudoMae' => $laudoMae,
            'laudoPai' => $laudoPai,
            'animal' => $animal,
            'pai' => $pai,
            'mae' => $mae,
            'result' => $result,
            'marcadores' => $marcadores,
        ]);
    }

    public function storeResult(Request $request)
    {
        // The $request->ordem, $request->incluidos and $request->excluidos will contain your data.
        $ordem = $request->ordem;
        $incluidos = $request->incluidos;
        $excluidos = $request->excluidos;

        // Now, you can do whatever you want with this data.
        // For instance, you can save them to the database.

        // Assuming you have a 'results' table and a 'Result' model
        $result = new Result;
        $result->ordem_servico = $ordem;
        $result->incluido = json_encode($incluidos);
        $result->excluido = json_encode($excluidos);
        $result->save();

        $log = Log::create([
            'ordem_id' => $ordem,
            'user' => Auth::user()->name,
            'action' => 'Salvou resultado do log',
        ]);

        // Return a response to the AJAX call
        return response()->json(['message' => 'Data saved successfully!']);
    }
    public function getResult($id)
    {
        $result = Result::where('ordem_servico', $id)
            ->orderBy('id', 'desc')
            ->first();

        return response()->json($result);
    }
    public function aleloUpdate(Request $request)
    {
        $alelo = Alelo::find($request->id);
        // dd($request->all());
        $data = [];

        if ($request->has('alelo1')) {
            if (!is_null($request->alelo1)) {
                $data['alelo1'] = strtoupper($request->alelo1);
            } else {
                $data['alelo1'] = null; // Define o valor de alelo1 como nulo
            }
        }

        if ($request->has('alelo2')) {
            if (!is_null($request->alelo2)) {
                $data['alelo2'] = strtoupper($request->alelo2);
            } else {
                $data['alelo2'] = null; // Define o valor de alelo2 como nulo
            }
        }

        $alelo->update($data);

        $log = Log::create([
            'ordem_id' => $alelo->animal->ordemServico->id,
            'user' => Auth::user()->name,
            'action' => 'Atualizou o alelo',
        ]);

        return response()->json($alelo);
    }

    public function dataAnalise(Request $request)
    {
        // dd($request->all());    
        $ordem = OrdemServico::find($request->id);
        $ordem->update([
            'data_analise' =>  $request->data,
        ]);

        $log = Log::create([
            'ordem_id' => $ordem->id,
            'user' => Auth::user()->name,
            'action' => 'Atualizou a data de análise',
        ]);

        return response()->json($ordem);
    }

    public function gerarBarCode($id)
    {
        $ordem = OrdemServico::find($id);
        $animal = Animal::with('alelos')->find($ordem->animal_id);
        $ordem->update([
            'bar_code' => $animal->codlab ?? null,
        ]);
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($ordem->codlab, $generator::TYPE_CODE_128);

        $barcodex = base64_encode($barcode);

        $log = Log::create([
            'ordem_id' => $ordem->id,
            'user' => Auth::user()->name,
            'action' => 'Gerou o código de barras',
        ]);

        return view('admin.ordem-servico.bar-code', get_defined_vars());
    }

    public function delete(Request $request)
    {
        $ordem = OrderLote::find($request->id);

        if (!$ordem) {
            return response()->json('Ordem não encontrada', 404);
        }

        $ordemServicos = OrdemServico::where('order', $ordem->order_id)->get();

        if ($ordemServicos->isEmpty()) {
            // return response()->json('Não foram encontradas ordens de serviço relacionadas', 404);
            $ordem->delete();
        }

        foreach ($ordemServicos as $ordemServico) {
            $ordemServico->delete();
        }

        $ordem->delete();

        $log = Log::create([
            'ordem_id' => $ordem->id,
            'user' => Auth::user()->name,
            'action' => 'Excluiu a ordem de serviço',
        ]);

        return response()->json('Ordem e ordens de serviço relacionadas foram excluídas com sucesso', 200);
    }
    public function searchByOrder(Request $request)
    {
        if ($request->ajax()) {
            $busca = $request->busca;

            $ordemServicos = OrderLote::where('order_id', $busca)
                ->orWhere('owner', 'LIKE', '%' . $busca . '%')
                ->get();

            $viewRender = view('admin.ordem-servico.include.search', compact('ordemServicos'))->render();

            return response()->json(['viewRender' => $viewRender]);
        }
    }
    public function searchByCodlab(Request $request)
    {
        if ($request->ajax()) {
            $codlab = $request->codlab;

            $items = OrdemServico::where('codlab',  $codlab)
                ->get();
            // dd($item);

            $viewRender = view('admin.ordem-servico.include.search-codlab', compact('items'))->render();

            return response()->json(['viewRender' => $viewRender]);
        }
    }

    public function searchByAnimal(Request $request)
    {
        if ($request->ajax()) {
            $busca = $request->animal;

            $items = OrdemServico::where('animal', $busca)->get();

            $viewRender = view('admin.ordem-servico.include.search-codlab', compact('items'))->render();

            return response()->json(['viewRender' => $viewRender]);
        }
    }

    public function resultado($id)
    {
        $ordemServico = OrdemServico::find($id);
        $ordem = OrderLote::find($ordemServico->lote);
        return view('admin.ordem-servico.resultado-busca', get_defined_vars());
    }

    public function edit($id)
    {
        $ordemServico = OrdemServico::find($id);
        $ordem = OrderLote::find($ordemServico->lote);
        return response()->json($ordemServico);
    }

    public function update(Request $request)
    {
        $ordemServico = OrdemServico::find($request->ordem_id);
        $dna_verify = DnaVerify::where('animal_id', $ordemServico->animal_id)->latest('created_at')->first();

        $tipoExame = null;
        if ($request->tipo_exame == 'EQUPEGGN') {
            $tipoExame = 'PEGGN';
        } else {
            $tipoExame = $request->tipo_exame;
        }
        if (!$dna_verify) {
            $dna_verify = new DnaVerify();
            $dna_verify->animal_id = $ordemServico->animal_id;
            $dna_verify->order_id = $ordemServico->order;
            $dna_verify->verify_code = $tipoExame;
            $dna_verify->save();
        } else {
            $dna_verify->update([
                'verify_code' => $tipoExame,
            ]);
        }

        $ordemServico->update($request->all());

        $log = Log::create([
            'ordem_id' => $ordemServico->id,
            'user' => Auth::user()->name,
            'action' => 'Atualizou a ordem de serviço',
        ]);

        return redirect()->back()->with('success', 'Ordem de serviço atualizada com sucesso');
    }
}
