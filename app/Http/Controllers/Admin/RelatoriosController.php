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

        $filename = 'relatorio-pagamentos-' . $data_inicial . '-ate-' . $data_final . '.xlsx';
        return Excel::download(new OrdersExport(collect($dados)), $filename);
    }
}
