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
use Picqer\Barcode\BarcodeGeneratorPNG;


class OrdemServicoController extends Controller
{
    public function store(Request $request)
    {

        $order = OrderRequest::find($request->order);
        $orderRequest = OrderRequestPayment::where('order_request_id', $order->id)->get();
        $lote = OrderLote::create([
            'order_id' => $order->id,
            'owner' => $order->creator,
        ]);
        foreach ($orderRequest as $item) {
            $exame = Exam::find($item->exam_id);
            $animal = Animal::find($item->animal_id);
            $data = Carbon::now()->addWeekdays($exame->days);
            $randomNumber = mt_rand(0, 1000000);
            $dna_verify = DnaVerify::where('animal_id', $animal->id)->first();
            $sigla = substr($animal->especies, 0, 3);

            if ($animal->codlab == null) {
                $animal->update([
                    'codlab' => Animal::where('codlab', $randomNumber)->exists() ? $sigla . rand(0, 1000000) :  $sigla . $randomNumber,
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
                'status' => 1,

            ]);
        }



        return response()->json($ordemServico);
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
                            // Criar o registro de Alelo para o animal encontrado
                            $alelo = Alelo::create([
                                'animal_id' => $animal->id,
                                'marcador' => $columns[2],
                                'alelo1' => $columns[3] ?? "*",
                                'alelo2' => $columns[4] ?? "*",
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
        $dna_verify = DnaVerify::where('animal_id', $animal->id)->first();
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
                foreach ($pai->alelos as $paiAlAlelo) {
                    if ($animalAlelo->marcador == $paiAlAlelo->marcador) {
                        $alelosPai[] = [
                            'marcador' => $animalAlelo->marcador,
                            'alelo1' => $animalAlelo->alelo1,
                            'alelo2' => $animalAlelo->alelo2,
                            'aleloPai1' => $paiAlAlelo->alelo1,
                            'aleloPai2' => $paiAlAlelo->alelo2,
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
                    } else {
                        $laudoPai[] = [
                            'marcador' => $paiAl['marcador'],
                            'include' => ''
                        ];
                    }
                } elseif ($paiAl['alelo1'] == '' && $paiAl['alelo2'] == '') {
                    $laudoPai[] = [
                        'marcador' => $paiAl['marcador'],
                        'include' => 'V'
                    ];
                }
            }
        } else {
            $laudoPai = null;
        }

        \Log::info($laudoMae);

        return response()->json([
            'laudoMae' => $laudoMae,
            'laudoPai' => $laudoPai,
            'animal' => $animal,
            'pai' => $pai,
            'mae' => $mae,
        ]);
    }



    public function gerarBarCode($id)
    {
        $ordem = OrdemServico::find($id);
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($ordem->codlab, $generator::TYPE_CODE_128);

        $barcodex = base64_encode($barcode);

        return view('admin.ordem-servico.bar-code', get_defined_vars());
    }
}
