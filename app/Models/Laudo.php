<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laudo extends Model
{
    use HasFactory;

    protected $fillable = [
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
        'qrcode',
        'ordem_id',
        'order_id',
        'pdf',
        'ret',
        'status',
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }
}
