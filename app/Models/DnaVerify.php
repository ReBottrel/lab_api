<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DnaVerify extends Model
{
    use HasFactory;

    protected $fillable = [
        'animal_id',
        'order_id',
        'verify_code',
        'verify_status',
    ];
}
