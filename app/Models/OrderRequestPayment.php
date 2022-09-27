<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRequestPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_request_id',
        'owner_name',
        'email',
        'location',
        'exam_id',
        'category',
        'animal',
        'title',
        'short_description',
        'value',
        'requests',
        'extra_value',
        'extra_requests',
        'payment_id',
        'payment_status',
        'days',
        'paynow',
    ];

    public function historyStatus()
    {
        return $this->hasMany(HistoryStatus::class, 'reference_id')->where('reference_type', 'OrderRequestPayment');
    }
}
