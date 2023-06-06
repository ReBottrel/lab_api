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

            if ($animal->codlab == null) {
                $animal->update([
                    'codlab' => Animal::where('codlab', $randomNumber)->exists() ? rand(0, 1000000) : $randomNumber,
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
                'tipo_exame' => 'EQUTR',
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

        $pai = Animal::with('alelos')->where('animal_name', $animal->pai)->first();
        $mae = Animal::with('alelos')->where('animal_name', $animal->mae)->first();

        return view('admin.ordem-servico.alelo-compare', get_defined_vars());
    }

    public function analise(Request $request)
    {
        $ordem = OrdemServico::find($request->ordem);
        $animal = Animal::with('alelos')->find($ordem->animal_id);

        $pai = Animal::with('alelos')->where('animal_name', $animal->pai)->first();
        $mae = Animal::with('alelos')->where('animal_name', $animal->mae)->first();

        $alelosIguaisPai = [];
        $alelosIguaisMae = [];

        // Comparar alelos entre pai e animal
        if ($pai && $pai->alelos) {
            foreach ($pai->alelos as $paiAlelo) {
                foreach ($animal->alelos as $animalAlelo) {
                    if (
                        $paiAlelo->marcador === $animalAlelo->marcador &&
                        (
                            ($paiAlelo->alelo1 === $animalAlelo->alelo1 && ($paiAlelo->alelo1 !== '' || $animalAlelo->alelo1 !== '')) ||
                            ($paiAlelo->alelo2 === $animalAlelo->alelo1 && ($paiAlelo->alelo2 !== '' || $animalAlelo->alelo1 !== '')) ||
                            ($paiAlelo->alelo1 === $animalAlelo->alelo2 && ($paiAlelo->alelo1 !== '' || $animalAlelo->alelo2 !== '')) ||
                            ($paiAlelo->alelo2 === $animalAlelo->alelo2 && ($paiAlelo->alelo2 !== '' || $animalAlelo->alelo2 !== '')) ||
                            ($mae && $mae->alelos && $mae->alelos->isEmpty() && $paiAlelo->alelo1 === '' && $paiAlelo->alelo2 === '')
                        )
                    ) {
                        $alelosIguaisPai[] = $paiAlelo;
                        break;
                    }
                }
            }
        }

        // Comparar alelos entre mãe e animal
        if ($mae && $mae->alelos) {
            foreach ($mae->alelos as $maeAlelo) {
                foreach ($animal->alelos as $animalAlelo) {
                    if (
                        $maeAlelo->marcador === $animalAlelo->marcador &&
                        (
                            ($maeAlelo->alelo1 === $animalAlelo->alelo1 && ($maeAlelo->alelo1 == '' || $animalAlelo->alelo1 !== '')) ||
                            ($maeAlelo->alelo2 === $animalAlelo->alelo1 && ($maeAlelo->alelo2 == '' || $animalAlelo->alelo1 !== '')) ||
                            ($maeAlelo->alelo1 === $animalAlelo->alelo2 && ($maeAlelo->alelo1 == '' || $animalAlelo->alelo2 !== '')) ||
                            ($maeAlelo->alelo2 === $animalAlelo->alelo2 && ($maeAlelo->alelo2 == '' || $animalAlelo->alelo2 !== ''))
                        )
                    ) {
                        $alelosIguaisMae[] = $maeAlelo;
                        break;
                    }
                }
            }
        }

        // $view = view('admin.ordem-servico.include.input-alelo', get_defined_vars())->render();

        return response()->json([
            'animal' => $animal,
            'alelos_mae' => $alelosIguaisMae,
            'alelos_pai' => $alelosIguaisPai,

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
