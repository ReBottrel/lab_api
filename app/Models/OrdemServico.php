<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdemServico extends Model
{
    use HasFactory;

    protected $fillable = [
        'order',
        'animal_id',
        'owner_id',
        'lote',
        'animal',
        'codlab',
        'id_abccmm',
        'tipo_exame',
        'proprietario',
        'tecnico',
        'data',
        'status',
        'observacao',
        'data_bar',
        'bar_code',
        'data_payment',
        'rg_pai',
        'rg_mae',
        'data_analise'
    ];


}
