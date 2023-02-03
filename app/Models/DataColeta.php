<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataColeta extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_order',
        'id_animal',
        'data_coleta',
        'data_laboratorio',
        'data_recebimento',
    ];
}
