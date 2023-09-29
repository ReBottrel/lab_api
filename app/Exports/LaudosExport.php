<?php
namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaudosExport implements FromCollection, WithHeadings
{
    protected $laudos;

    public function __construct($laudos)
    {
        $this->laudos = $laudos;
    }

    public function collection()
    {
        return $this->laudos;
    }

    public function headings(): array
    {
        return [
            'id do animal',
            'mae_id',
            'pai_id',
            'veterinario',
            'id do proprietário',
            'data de coleta',
            'data de realização',
            'data de entrada no laboratório',
            'codigo_busca',
            'observacao',
            'conclusao',
            'tipo',
            'veterinario_id',
            'id da ordem',
            'id do pedido',
            'pdf',
            'retificação',
            'status',
            'data_retificacao',
            'data de criação',
            'data de finalização',
        ];
    }
}
