<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentReturn extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_request_id',
        'user_id',
        'payment_id',
        'payment_type',
        'payment_status',
        'pixqrcode',
        'pixcode',
        'boleto',
    ];
}
