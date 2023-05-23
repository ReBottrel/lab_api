<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdemServico extends Model
{
    use HasFactory;

    protected $fillable = [
        'animal_id',
        'owner_id',
        'tecnico_id',
        'animal',
        'codlab',
        'id_abccmm',
        'tipo_exame',
        'proprietario',
        'tecnico',
        'data',
        'status',
        'observacao',
    ];
}
