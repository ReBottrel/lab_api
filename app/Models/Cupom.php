<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cupom extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'used',
        'validity',
        'max_use',
        'max_use_client',
        'active',
        'client_id',
        'order_id',
        'product_id',
        'category_id',
    ];
}
