<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Exam;
use App\Models\Animal;
use App\Models\OrderLote;
use App\Models\OrdemServico;
use App\Models\OrderRequest;
use Illuminate\Http\Request;
use App\Models\OrderRequestPayment;
use App\Http\Controllers\Controller;
use App\Models\Alelo;
use App\Models\DnaVerify;
use App\Models\Laudo;
use App\Models\Marcador;
use App\Models\Result;
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
            // Ordem não encontrada
            return response()->json(['error' => 'Pedido não encontrado.'], 404);
        }

        // Verificar se já existe uma ordem de serviço com o order_id
        $existingOrder = OrdemServico::where('order', $order->id)->first();
        if ($existingOrder) {
            // Já existe uma ordem de serviço com o order_id
            return response()->json(['error' => 'Já existe uma ordem de serviço para este pedido.'], 400);
        }

        $orderRequest = OrderRequestPayment::where('order_request_id', $order->id)->get();
        $lote = OrderLote::create([
            'order_id' => $order->id,
            'owner' => $order->creator,
        ]);

        foreach ($orderRequest as $item) {
            $exame = Exam::find($item->exam_id);
            $animal = Animal::find($item->animal_id);
            $data = Carbon::parse($item->updated_at)->addWeekdays($exame->days);
            $randomNumber = mt_rand(0, 1000000);
            $dna_verify = DnaVerify::where('animal_id', $item->animal_id)->latest('created_at')->first();
            if (!$dna_verify) {
                switch ($animal->especies) {
                    case 'EQUINA':
                        $tipo = 'EQUTR';
                        break;
                    case 'MUARES':
                        $tipo = 'MUATR';
                        break;
                    case 'ASININO':
                        $tipo = 'ASITR';
                        break;
                    case 'EQUINO_PEGA':
                        $tipo = 'ASITR';
                        break;
                    case 'BOVINA':
                        $tipo = 'BOVTR';
                        break;
                    default:
                        $tipo = 'EQUTR';
                }
                $dna_verify = DnaVerify::create([
                    'animal_id' => $item->animal_id,
                    'order_id' => $order->id,
                    'verify_code' => $tipo,
                ]);
            }
            $sigla = substr($animal->especies, 0, 3) ? substr($animal->especies, 0, 3) : 'EQU';
            $codlab = $this->generateUniqueCodlab($sigla);

            if ($animal->codlab == null) {
                $animal->update([
                    'codlab' => $codlab,
                ]);
            }

            $ordemServico = OrdemServico::create([
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
                'data_payment' => $item->updated_at,
                'rg_pai' => $animal->registro_pai,
                'rg_mae' => $animal->registro_mae,
                'status' => 1,
            ]);
        }

        return response()->json('success', 200);
    }
    private function generateUniqueCodlab($sigla)
    {
        $startValue = 100000;
        $maxNumber = Animal::selectRaw('MAX(CAST(SUBSTRING(codlab, 4) AS UNSIGNED)) as max_num')
            ->whereRaw('CAST(SUBSTRING(codlab, 4) AS UNSIGNED) >= 100000 AND CAST(SUBSTRING(codlab, 4) AS UNSIGNED) < 200000')
            ->first();

        if ($maxNumber !== null && $maxNumber->max_num !== null) {
            $startValue = $maxNumber->max_num + 1;
        }

        while (Animal::where('codlab', '=', $sigla . strval($startValue))->exists()) {
            $startValue += 1;
        }

        $codlab = $sigla . strval($startValue);

        return $codlab;
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

                            // Criar o registro de Alelo para o animal encontrado
                            $alelo = Alelo::create([
                                'animal_id' => $animal->id,
                                'marcador' => $marcador,
                                'alelo1' => $alelo1,
                                'alelo2' => $alelo2,
                                'lab' => 'Loci Genética Laboratorial',
                                'data' => Carbon::now(),
                            ]);
                            $animal->update([
                                'identificador' =>  'LO23-' . substr($animal->codlab, 3)
                            ]);
                        }
                    } else {
                        // Tratar o caso em que a colunsa não existe
                        $sampleName = null; // Ou qualquer outro valor padrão que faça sentido para o seu caso
                    }
                }

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
        $dna_verify = DnaVerify::where('animal_id', $ordem->animal_id)->first();
        $laudo = Laudo::where('ordem_id', $id)
            ->orderBy('id', 'desc')
            ->first();
        $result = Result::where('ordem_servico', $id)
            ->orderBy('id', 'desc')
            ->first();
        $sigla = substr($animal->especies, 0, 3);
        $pai = null;
        $mae = null;

        switch ($dna_verify->verify_code) {
            case $sigla . 'PD':
                $pai = Animal::with('alelos')->where('animal_name', $animal->pai)->first();
                break;
            case $sigla . 'MD':
                $mae = Animal::with('alelos')->where('animal_name', $animal->mae)->first();
                break;
            case $sigla . 'TR':
                $pai = Animal::with('alelos')->where('animal_name', $animal->pai)->first();
                $mae = Animal::with('alelos')->where('animal_name', $animal->mae)->first();
                break;
            default:
                break;
        }

        dd($pai,$mae);

        return view('admin.ordem-servico.alelo-compare', get_defined_vars());
    }

    public function dataBarCode(Request $request)
    {
        $ordem = OrdemServico::find($request->ordem_id);
        $ordem->update([
            'data_bar' => $request->data
        ]);
        return response()->json($ordem);
    }



    public function analise(Request $request)
    {
        $ordem = OrdemServico::find($request->ordem);
        $animal = Animal::with('alelos')->find($ordem->animal_id);
        $dna_verify = DnaVerify::where('animal_id', $animal->id)->first();
        $sigla = substr($animal->especies, 0, 3);
        $result = Result::where('ordem_servico', $ordem->id)
            ->orderBy('id', 'desc')
            ->first();
        $pai = null;
        $mae = null;
        switch ($dna_verify->verify_code) {
            case $sigla . 'PD':
                $pai = Animal::with('alelos')->where('animal_name', $animal->pai)->first();
                break;
            case $sigla . 'MD':
                $mae = Animal::with('alelos')->where('animal_name', $animal->mae)->first();
                break;
            case $sigla . 'TR':
                $pai = Animal::with('alelos')->where('animal_name', $animal->pai)->first();
                $mae = Animal::with('alelos')->where('animal_name', $animal->mae)->first();
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

        // Comparar alelos entre mãe e animal
        if ($mae != null) {
            foreach ($animal->alelos as $animalAlelo) {
                foreach ($mae->alelos as $maeAlelo) {
                    if ($animalAlelo->marcador == $maeAlelo->marcador) {
                        $alelosMae[] = [
                            'marcador' => $animalAlelo->marcador,
                            'alelo1' => $animalAlelo->alelo1,
                            'alelo2' => $animalAlelo->alelo2,
                            'aleloMae1' => $maeAlelo->alelo1,
                            'aleloMae2' => $maeAlelo->alelo2,
                        ];
                        break;
                    }
                }
            }
            foreach ($alelosMae as $maeAl) {
                if (($maeAl['alelo1'] != '' || $maeAl['alelo2'] != '') && ($maeAl['aleloMae1'] != '' || $maeAl['aleloMae2'] != '')) {
                    if (
                        $maeAl['alelo1'] == $maeAl['aleloMae1'] ||
                        $maeAl['alelo1'] == $maeAl['aleloMae2'] ||
                        $maeAl['alelo2'] == $maeAl['aleloMae1'] ||
                        $maeAl['alelo2'] == $maeAl['aleloMae2']
                    ) {
                        $laudoMae[] = [
                            'marcador' => $maeAl['marcador'],
                            'include' => 'M'
                        ];
                        \Log::info("Condição 1 cumprida" . $maeAl['marcador']);
                    } else {
                        $laudoMae[] = [
                            'marcador' => $maeAl['marcador'],
                            'include' => ''
                        ];
                        \Log::info("Condição 2 cumprida" . $maeAl['marcador']);
                    }
                } elseif ($maeAl['alelo1'] == '' && $maeAl['alelo2'] == '' || empty($maeAl['aleloMae1']) && empty($maeAl['aleloMae2'])) {
                    $laudoMae[] = [
                        'marcador' => $maeAl['marcador'],
                        'include' => 'V'
                    ];
                    \Log::info("Condição 3 cumprida" . $maeAl['marcador']);
                } else {
                    \Log::info("Nenhuma condição cumprida" . $maeAl['marcador']);
                }
            }
        } else {
            $laudoMae = null;
        }

        // Comparar alelos entre pai e animal
        if ($pai != null) {
            foreach ($animal->alelos as $animalAlelo) {
                foreach ($pai->alelos as $paiAlelo) {
                    if ($animalAlelo->marcador == $paiAlelo->marcador) {
                        $alelosPai[] = [
                            'marcador' => $animalAlelo->marcador,
                            'alelo1' => $animalAlelo->alelo1,
                            'alelo2' => $animalAlelo->alelo2,
                            'aleloPai1' => $paiAlelo->alelo1,
                            'aleloPai2' => $paiAlelo->alelo2,
                        ];
                        break;
                    }
                }
            }

            foreach ($alelosPai as $paiAl) {
                if (($paiAl['alelo1'] != '' || $paiAl['alelo2'] != '') && ($paiAl['aleloPai1'] != '' || $paiAl['aleloPai2'] != '')) {
                    if (
                        $paiAl['alelo1'] == $paiAl['aleloPai1'] ||
                        $paiAl['alelo1'] == $paiAl['aleloPai2'] ||
                        $paiAl['alelo2'] == $paiAl['aleloPai1'] ||
                        $paiAl['alelo2'] == $paiAl['aleloPai2']
                    ) {
                        $laudoPai[] = [
                            'marcador' => $paiAl['marcador'],
                            'include' => 'P'
                        ];
                        \Log::info("Condição 1 cumprida" . $paiAl['marcador']);
                    } else {
                        $laudoPai[] = [
                            'marcador' => $paiAl['marcador'],
                            'include' => ''
                        ];
                        \Log::info("Condição 2 cumprida" . $paiAl['marcador']);
                    }
                } elseif ($paiAl['alelo1'] == '' && $paiAl['alelo2'] == '' || empty($paiAl['aleloPai1']) && empty($paiAl['aleloPai2'])) {
                    $laudoPai[] = [
                        'marcador' => $paiAl['marcador'],
                        'include' => 'V'
                    ];
                    \Log::info("Condição 3 cumprida" . $paiAl['marcador']);
                } else {
                    \Log::info("Nenhuma condição cumprida" . $paiAl['marcador']);
                }
            }
        } else {
            $laudoPai = null;
        }

        \Log::info($laudoPai);

        return response()->json([
            'laudoMae' => $laudoMae,
            'laudoPai' => $laudoPai,
            'animal' => $animal,
            'pai' => $pai,
            'mae' => $mae,
            'result' => $result,

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

        return response()->json($alelo);
    }

    public function dataAnalise(Request $request)
    {
        // dd($request->all());    
        $ordem = OrdemServico::find($request->id);
        $ordem->update([
            'data_analise' =>  $request->data,
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

        return view('admin.ordem-servico.bar-code', get_defined_vars());
    }

    public function delete(Request $request)
    {
        $ordem = OrderLote::find($request->id);

        if (!$ordem) {
            return response()->json('Ordem não encontrada', 404);
        }

        $ordemServicos = OrdemServico::where('lote', $ordem->id)->get();

        if ($ordemServicos->isEmpty()) {
            // return response()->json('Não foram encontradas ordens de serviço relacionadas', 404);
            $ordem->delete();
        }

        foreach ($ordemServicos as $ordemServico) {
            $ordemServico->delete();
        }

        $ordem->delete();

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
}
