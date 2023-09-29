<?php

namespace App\Http\Controllers\Admin;

use App\Models\Laudo;
use Illuminate\Http\Request;
use App\Exports\LaudosExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

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
}
