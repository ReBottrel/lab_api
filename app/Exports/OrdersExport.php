<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithHeadings
{
    protected $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function collection()
    {
        return $this->orders;
    }

    public function headings(): array
    {
        return [
            'Ordem',
            'Animal',
            'Código Lab',
            'ID ABCCMM',
            'Tipo Exame',
            'Proprietário',
            'Técnico',
            'Data Análise',
            'Data prevista',
            'Status',
            'Observação',
            'Bar Code',
            'Data Pagamento',
            'Conclusão Laudo',
            'Data Laudo',
            'Retificado',
            'Data Retificação'
        ];
    }
} 