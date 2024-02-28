<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use Dompdf\Dompdf;
use App\Models\Alelo;
use App\Models\Laudo;
use App\Models\Owner;
use App\Models\Animal;
use App\Models\QrCode;
use App\Models\Tecnico;
use App\Models\DnaVerify;
use App\Models\DataColeta;
use App\Models\OrdemServico;
use App\Models\OrderRequest;
use App\Models\PedidoAnimal;
use Illuminate\Http\Request;
use App\Exports\LaudosExport;
use App\Models\ResenhaAnimal;
use Illuminate\Support\Facades\DB;
use App\Models\OrderRequestPayment;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class TesteController extends Controller
{
    public function __construct()
    {
        ini_set('max_execution_time', 8000);
    }
    public function index()
    {
        return view('teste');
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

    public function gerarPdf()
    {
        // Cria uma instância do Dompdf
        $dompdf = new Dompdf();

        // Define o tamanho e a orientação da página como A4
        $dompdf->setPaper('A4', 'portrait');

        // Renderiza o HTML em PDF
        // $html = view('admin.ordem-servico.laudo');
        $html = view('admin.ordem-servico.laudo-imp-teste');
        $dompdf->loadHtml($html);
        $dompdf->render();

        // Gera o PDF e envia para o navegador
        $dompdf->stream('documento.pdf');
    }
    public function verPdf()
    {
        return view('admin.ordem-servico.laudo-imp-teste');
    }

    public function updateStatus()
    {
        $data = PedidoAnimal::get();

        foreach ($data as $key => $value) {
            $animal = Animal::find($value->id_animal);
            if ($animal) {
                $animal->update([
                    'order_id' => $value->id_pedido,
                ]);
            }
        }
        return 'ok';
    }

    public function getOrderNotCreate()
    {
        $id_pedidos = PedidoAnimal::whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('order_requests')
                ->whereColumn('order_requests.id', 'pedido_animals.id_pedido');
        })->distinct('id_pedido')->pluck('id_pedido');

        return $id_pedidos;
    }
    public function selectCodlabInRange()
    {
        $codlabs = Animal::select('codlab')
            ->whereRaw('CAST(SUBSTRING(codlab, 4) AS UNSIGNED) >= 200000 AND CAST(SUBSTRING(codlab, 4) AS UNSIGNED) < 300000')
            ->get();

        return $codlabs;
    }

    public function exportDuplicatedCodlabToTxt()
    {
        $animalsByCodlab = Animal::select('codlab', 'animal_name')
            ->get();

        $groupedCodlabs = [];

        foreach ($animalsByCodlab as $animal) {
            $codlab_number = substr($animal->codlab, 3);
            $groupedCodlabs[$codlab_number][] = "{$animal->animal_name}: {$animal->codlab}";
        }

        $duplicatedCodlabs = [];

        foreach ($groupedCodlabs as $codlab_number => $animals) {
            if (count($animals) > 1) {
                $duplicatedCodlabs[$codlab_number] = $animals;
            }
        }

        $txtContent = '';

        foreach ($duplicatedCodlabs as $codlab_number => $animals) {
            $txtContent .= "Códigos de laboratório com o número {$codlab_number}:\n";
            foreach ($animals as $animal) {
                $txtContent .= " - {$animal}\n";
            }
        }

        $fileName = 'duplicated_codlab.txt';
        file_put_contents($fileName, $txtContent);

        return response()->download($fileName)->deleteFileAfterSend(true);
    }




    public function updateCodlabInRange()
    {
        // Pegue todos os codlabs dentro da faixa numérica desejada e ordene-os pela parte numérica
        $codlabs = Animal::select('id', 'codlab')
            ->whereRaw('CAST(SUBSTRING(codlab, 4) AS UNSIGNED) >= 100000 AND CAST(SUBSTRING(codlab, 4) AS UNSIGNED) < 200000')
            ->orderByRaw('CAST(SUBSTRING(codlab, 4) AS UNSIGNED)')
            ->get();

        $updates = [];
        $startValue = 100000;

        foreach ($codlabs as $animal) {
            // Geramos um novo codlab com o próximo valor numérico disponível
            while (Animal::whereRaw('CAST(SUBSTRING(codlab, 4) AS UNSIGNED) = ?', [$startValue])->exists()) {
                $startValue++;
            }

            $prefix = substr($animal->codlab, 0, 3);
            $newCodlab = $prefix . strval($startValue);

            $updates[$animal->id] = ['codlab' => $newCodlab];
            $startValue++;
        }

        DB::beginTransaction();
        try {
            foreach ($updates as $id => $update) {
                Animal::where('id', $id)->update($update);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }
    }



    public function pdfLaudo($id)
    {
        $laudo = Laudo::find($id);
        $animal = Animal::find($laudo->animal_id);
        $owner = Owner::find($laudo->owner_id);
        $datas = DataColeta::where('id_animal', $laudo->animal_id)->first();
        $tecnico = Tecnico::find($laudo->veterinario_id);
        $dna_verify = DnaVerify::where('animal_id', $animal->id)->first();
        $sigla = substr($animal->especies, 0, 3);
        $examType = substr($dna_verify->verify_code, 3, 2);
        $ordem = OrdemServico::where('animal_id', $laudo->animal_id)->latest()->first();
        $pai = null;
        $mae = null;
        switch ($dna_verify->verify_code) {
            case $sigla . 'PD':
                $pai = Animal::with('alelos')->find($laudo->pai_id);
                break;
            case $sigla . 'MD':
                $mae = Animal::with('alelos')->find($laudo->mae_id);
                break;
            case $sigla . 'TR':
                $pai = Animal::with('alelos')->find($laudo->pai_id);
                $mae = Animal::with('alelos')->find($laudo->mae_id);
                break;
            default:
                break;
        }
        $qrCode = QrCode::where('laudo_id', $laudo->id)->first();

        return view('admin.ordem-servico.laudo-imp', get_defined_vars());
    }

    public function alelosDuplicados()
    {
        $duplicados = Alelo::select('animal_id', 'marcador')
            ->groupBy('animal_id', 'marcador')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        $dupli = [];
        foreach ($duplicados as $duplicado) {
            $dupli[] = "Animal ID: " . $duplicado->animal_id . " | Marcador: " . $duplicado->marcador;
        }
        return $dupli;
    }
    public function apagarAlelosDuplicados()
    {
        $duplicados = Alelo::select('animal_id', 'marcador')
            ->groupBy('animal_id', 'marcador')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicados as $duplicado) {
            $registrosDuplicados = Alelo::where('animal_id', $duplicado->animal_id)
                ->where('marcador', $duplicado->marcador)
                ->get();

            $primeiroRegistro = true;
            foreach ($registrosDuplicados as $registro) {
                if ($primeiroRegistro) {
                    $primeiroRegistro = false;
                    continue;
                }

                $registro->delete();
            }
        }
    }

    public function mudarEspecie()
    {
        $animals = Animal::where('especies', 'EQUINO_PEGA')->get();
        foreach ($animals as $animal) {
            $animal->update([
                'especies' => 'PEGA_EQUINO',
            ]);
        }
        return $animals;
    }

    public function getPagamentos()
    {
        $payments_without_orders = OrderRequestPayment::where('payment_status', 0)
            ->whereNotIn('order_request_id', function ($query) {
                $query->select('id')->from(with(new OrderRequest)->getTable());
            })
            ->select('order_request_id', 'owner_name', 'updated_at', 'animal')
            ->get();

        // Crie uma string para armazenar os dados.
        $data = "";

        // Adicione cada pagamento ao arquivo .txt.
        foreach ($payments_without_orders as $payment) {
            $formattedDate = $payment->updated_at ? $payment->updated_at->format('d/m/Y') : '';
            $data .= "Numero pedido: " . $payment->order_request_id . ", Proprietario: " . $payment->owner_name . ", Data: " . $formattedDate . ", Animal: " . $payment->animal . "\n";
        }

        // Escreva os dados no arquivo .txt.
        file_put_contents('payments_without_orders.txt', $data);

        return $payments_without_orders;
    }

    public function getOrdemServicosDuplicadas()
    {
        $ordens_servicos_duplicadas = OrdemServico::select('animal', 'order')
            ->groupBy('animal', 'order')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        return $ordens_servicos_duplicadas;
    }
    public function deleteOrdemServicosDuplicadasSemDataBar()
    {
        // Obtenha as ordens de serviço duplicadas
        $duplicated_orders = OrdemServico::select('animal', 'order')
            ->groupBy('animal', 'order')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('order');

        // De todas as ordens de serviço duplicadas, obtenha as que não têm uma data_bar
        $orders_to_delete = OrdemServico::whereIn('order', $duplicated_orders)
            ->whereNull('data_bar')
            ->get();

        // Exclua essas ordens de serviço
        foreach ($orders_to_delete as $order) {
            $order->delete();
        }
    }
    public function alterarStatusLaudo()
    {
        // Filtrando laudos com status 0 e created_at diferente de 04/08/2023 e atualizando
        $updatedCount = Laudo::where('status', 0)
            ->whereNotNull('pdf')
            ->update(['status' => 1]);
        return $updatedCount;
    }

    public function viewStatus()
    {
        return view('admin.animais.status');
    }

    public function alterarStatusAnimalByOrder(Request $request)
    {

        $animal = Animal::where('order_id', $request->order_id)->get();
        foreach ($animal as $key => $value) {
            $value->update([
                'status' => $request->status,
            ]);
        }
        return redirect()->back()->with('success', 'Status alterado com sucesso!');
    }

    public function getAnimalParents()
    {
        $animals = Animal::get();

        foreach ($animals as $animal) {
            $animal->mae = Animal::find($animal->mae);
        }
    }

    public function getLaudoTotal()
    {
        $laudos = Laudo::where('status', 1)
            ->where('conclusao', 'like', '%não está qualificado pela genitora%')
            ->where('conclusao', 'like', '%não está qualificado pelo genitor%')
            ->select(
                'animal_id',
                'mae_id',
                'pai_id',
                'veterinario',
                'owner_id',
                'data_coleta',
                'data_realizacao',
                'data_lab',
                'codigo_busca',
                'observacao',
                'conclusao',
                'tipo',
                'veterinario_id',
                'ordem_id',
                'order_id',
                'pdf',
                'ret',
                'status',
                'data_retificacao',
                'created_at',
                'updated_at'
            )
            ->get();

        $totalLaudos = count($laudos);

        \Log::info('Total de laudos com status 1 e texto específico na conclusão: ' . $totalLaudos);
        return Excel::download(new LaudosExport($laudos), 'laudos-total-exclusao.xlsx');
    }

    public function getLaudosTotal()
    {
        $laudos = Laudo::where('status', 1)
            ->select(
                'animal_id',
                'mae_id',
                'pai_id',
                'veterinario',
                'owner_id',
                'data_coleta',
                'data_realizacao',
                'data_lab',
                'codigo_busca',
                'observacao',
                'conclusao',
                'tipo',
                'veterinario_id',
                'ordem_id',
                'order_id',
                'pdf',
                'ret',
                'status',
                'data_retificacao',
                'created_at',
                'updated_at'
            )
            ->get();

        $totalLaudos = count($laudos);

        \Log::info('Total de laudos com status 1 e texto específico na conclusão: ' . $totalLaudos);
        return Excel::download(new LaudosExport($laudos), 'laudos-total.xlsx');
    }


    public function testeEnvioEmail()
    {
        $data = [
            'name' => 'Teste',
            'body' => 'Teste de envio de email'
        ];
        Mail::send('mails.teste', $data, function ($message) {
            $message->to('compras@kswbike.com.br', 'Teste')->subject('Teste de envio de email');
            $message->from('felipephplow@gmail.com', 'Teste');
        });
    }
}
