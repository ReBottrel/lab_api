<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRequest extends Model
{
    use HasFactory;

    protected $casts = [
        'data_g' => 'array'
    ];

    protected $fillable = [
        'user_id',
        'origin',
        'creator',
        'creator_number',
        'technical_manager',
        'collection_date',
        'collection_number',
        'data_g',
        'status',
        'total',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function orderRequestPayment()
    {
        return $this->hasMany(OrderRequestPayment::class, 'order_request_id');
    }
}
