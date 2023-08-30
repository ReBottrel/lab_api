<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tecnico extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'professional_name',
        'document',
        'email',
        'fone',
        'cell',
        'whatsapp',
        'zip_code',
        'address',
        'number',
        'complement',
        'district',
        'city',
        'state',
        'nr_portaria_mormo',
        'registro_profissional',
        'conselho',
        'forma_tratamento',
        'sexo',
        'parceiro_id',
        'observacao',
        'status',
        'matricula',
    ];
}
