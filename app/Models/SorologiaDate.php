<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SorologiaDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'pedido_id',
        'animal_id',
        'order_id',
        'data_recebimento',
        'data_resultado',
        'numero_aie',
        'numero_mormo',
    ];
}
