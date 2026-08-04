<?php

namespace App\Http\Controllers\Admin;

use App\Models\Laudo;
use App\Models\Order;
use App\Models\Animal;
use App\Models\OrdemServico;
use Illuminate\Http\Request;
use App\Exports\LaudosExport;
use App\Exports\OrdersExport;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Rap2hpoutre\FastExcel\FastExcel;

class RelatoriosController extends Controller
{
    public function index()
    {
        $laudosExcluidos = Laudo::where('status', 1)
            ->where('conclusao', 'like', '%não está qualificado pela genitora%')
            ->orWhere('conclusao', 'like', '%não está qualificado pelo genitor%')
            ->select(
                'animal_id',

            )
            ->get();

        $totalLaudosExclusao = count($laudosExcluidos);

        $laudosExcluidosGenitor = Laudo::where('status', 1)

            ->where('conclusao', 'like', '%não está qualificado pelo genitor%')
            ->select(
                'animal_id',

            )
            ->get();

        $totalLaudosExclusaoGenitor = count($laudosExcluidosGenitor);

        $laudosExcluidosGenitora = Laudo::where('status', 1)
            ->where('conclusao', 'like', '%não está qualificado pela genitora%')
            ->select(
                'animal_id',

            )
            ->get();

        $totalLaudosExclusaoGenitora = count($laudosExcluidosGenitora);

        $laudos = Laudo::where('status', 1)
            ->select(
                'animal_id',

            )
            ->get();

        $totalLaudos = count($laudos);
        return view('admin.relatorios', get_defined_vars());
    }

    public function getLaudoTotal()
    {
        $laudos = Laudo::where('status', 1)
            ->where('conclusao', 'like', '%não está qualificado pela genitora%')
            ->orWhere('conclusao', 'like', '%não está qualificado pelo genitor%')
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
    public function getLaudoTotalGenitora()
    {
        $laudos = Laudo::where('status', 1)
            ->where('conclusao', 'like', '%não está qualificado pela genitora%')
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


        return Excel::download(new LaudosExport($laudos), 'laudos-total-exclusao-genitora.xlsx');
    }
    public function getLaudoTotalGenitor()
    {
        $laudos = Laudo::where('status', 1)
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

        return Excel::download(new LaudosExport($laudos), 'laudos-total-exclusao-genitor.xlsx');
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
    public function getCodlab(Request $request)
    {
        $animal = Animal::where('codlab', $request->codlab)
            ->with('paiRel', 'maeRel')
            ->first();
        return response()->json($animal);
    }

    public function getOrdemServico(Request $request)
    {
        $data_inicial = $request->data_inicial;
        $data_final = $request->data_final;
        
        $query = OrdemServico::query();
        
        if ($data_inicial && $data_final) {
            $query->whereBetween('created_at', [$data_inicial . ' 00:00:00', $data_final . ' 23:59:59']);
        }
        
        $ordens = $query->with(['animal', 'owner', 'tecnico'])
            ->select(
                'id',
                'animal_id',
                'owner_id',
                'tecnico_id',
                'data_coleta',
                'data_lab',
                'codlab',
                'created_at'
            )
            ->get()
            ->map(function ($ordem) {
                return [
                    'ordem' => $ordem->id,
                    'animal' => $ordem->animal->nome ?? '',
                    'codlab' => $ordem->codlab,
                    'proprietario' => $ordem->owner->nome ?? '',
                    'tecnico' => $ordem->tecnico->nome ?? '',
                    'data_coleta' => $ordem->data_coleta,
                    'data_lab' => $ordem->data_lab,
                    'data_cadastro' => $ordem->created_at->format('d/m/Y'),
                ];
            });

        return Excel::download(new OrdersExport($ordens), 'relatorio-ordens-servico.xlsx');
    }

    public function getRelatorioPorDataPagamento(Request $request)
    {
        $data_inicial = $request->data_inicial;
        $data_final = $request->data_final;
        
        \Log::info([$request->all()]);
        
        $orders = OrdemServico::query();
        
        if ($data_inicial && $data_final) {
            $orders->whereBetween('data_payment', [$data_inicial . ' 00:00:00', $data_final . ' 23:59:59']);
        }
        
        $orders = $orders->select(
                'id',
                'order',
                'animal',
                'codlab',
                'id_abccmm',
                'tipo_exame',
                'proprietario',
                'tecnico',
                'data',
                'status',
                'observacao',
                'bar_code',
                'data_payment',
                'data_analise',
                'animal_id'
            )
            ->get();
        
        $dados = [];
        foreach ($orders as $order) {
            $laudo = Laudo::where('animal_id', $order->animal_id)->where('ordem_id', $order->id)
                ->latest()
                ->first();

            $dados[] = [
                'ordem' => $order->order,
                'animal' => $order->animal,
                'codlab' => $order->codlab,
                'id_abccmm' => $order->id_abccmm,
                'tipo_exame' => $order->tipo_exame,
                'proprietario' => $order->proprietario,
                'tecnico' => $order->tecnico,
                'data_analise' => $order->data_analise ? Carbon::parse($order->data_analise)->format('d/m/Y') : '-',
                'data' => $order->data ? Carbon::parse($order->data)->format('d/m/Y') : '-',
                'status' => $laudo ? ($laudo->status == 1 ? 'Liberado' : 'Não Liberado') : 'Sem laudo',
                'observacao' => $order->observacao,
                'bar_code' => $order->bar_code,
                'data_payment' => $order->data_payment ? Carbon::parse($order->data_payment)->format('d/m/Y') : '-',
                'conclusao_laudo' => $laudo->conclusao ?? 'Sem laudo',
                'data_laudo' => $laudo ? Carbon::parse($laudo->updated_at)->format('d/m/Y') : '-',
                'retificado' => $laudo ? ($laudo->ret ?: 'Não') : 'Não',
                'data_retificacao' => $laudo && $laudo->data_retificacao ? Carbon::parse($laudo->data_retificacao)->format('d/m/Y') : '-'
            ];
        }

        $filename = 'relatorio-ordens-servico-' . $data_inicial . '-ate-' . $data_final . '.xlsx';
        return Excel::download(new OrdersExport(collect($dados)), $filename);
    }

    /**
     * Exporta animais Mangalarga Marchador com status 10 (Concluído),
     * incluindo marcadores e alelos da tabela alelos.
     */
    public function exportMangalargaStatus10()
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $baseQuery = Animal::query()
            ->where('status', 10)
            ->where('breed', 'MANGALARGA MARCHADOR');

        if (! $baseQuery->exists()) {
            return redirect()
                ->route('relatorios')
                ->with('error', 'Nenhum animal encontrado com raça MANGALARGA MARCHADOR e status 10.');
        }

        $padrao = collect([
            'AHT4', 'AHT5', 'ASB2', 'ASB23', 'HMS2', 'HMS3',
            'HMS6', 'HMS7', 'HTG10', 'HTG4', 'HTG7', 'VHL20',
        ]);

        $extra = \DB::table('alelos')
            ->join('animals', 'animals.id', '=', 'alelos.animal_id')
            ->where('animals.status', 10)
            ->where('animals.breed', 'MANGALARGA MARCHADOR')
            ->whereNotNull('alelos.marcador')
            ->distinct()
            ->pluck('alelos.marcador')
            ->map(function ($marcador) {
                return strtoupper(trim((string) $marcador));
            })
            ->filter();

        $marcadores = $padrao->merge($extra)->unique()->sort()->values();

        $generator = function () use ($marcadores) {
            $animals = Animal::with('alelos')
                ->where('status', 10)
                ->where('breed', 'MANGALARGA MARCHADOR')
                ->orderBy('id')
                ->lazy(200);

            foreach ($animals as $animal) {
                $row = [
                    'ID' => $animal->id,
                    'Nome' => $animal->animal_name,
                    'Codlab' => $animal->codlab,
                    'Identificador' => $animal->identificador,
                    'Raça' => $animal->breed,
                    'Espécie' => $animal->especies,
                    'Sexo' => $animal->sex,
                    'Registro' => $animal->register_number_brand,
                    'Registro definitivo' => $animal->number_definitive,
                    'Status' => 'Concluído',
                    'Pedido' => $animal->order_id,
                    'Data nascimento' => $animal->birth_date
                        ? date('d/m/Y', strtotime($animal->birth_date))
                        : '',
                    'Criado em' => $animal->created_at
                        ? date('d/m/Y H:i', strtotime($animal->created_at))
                        : '',
                ];

                $alelosPorMarcador = $animal->alelos->keyBy(function ($alelo) {
                    return strtoupper(trim((string) $alelo->marcador));
                });

                foreach ($marcadores as $marcador) {
                    $alelo = $alelosPorMarcador->get($marcador);
                    if ($alelo) {
                        $alelo1 = $alelo->alelo1 !== null && $alelo->alelo1 !== '' ? $alelo->alelo1 : '*';
                        $alelo2 = $alelo->alelo2 !== null && $alelo->alelo2 !== '' ? $alelo->alelo2 : '*';
                        $row[$marcador] = $alelo1 . '/' . $alelo2;
                    } else {
                        $row[$marcador] = '';
                    }
                }

                yield $row;
            }
        };

        if (! is_dir(public_path('arquivos'))) {
            mkdir(public_path('arquivos'), 0755, true);
        }

        $name = 'mangalarga-concluidos-' . date('d-m-Y-His') . '.xlsx';
        (new FastExcel($generator()))->export(public_path('arquivos/' . $name));

        return response()
            ->download(public_path('arquivos/' . $name), $name, [
                'Content-Type' => 'application/vnd.ms-excel',
            ])
            ->deleteFileAfterSend(true);
    }
}
