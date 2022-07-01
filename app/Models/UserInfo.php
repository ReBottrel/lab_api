<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'document',
        'phone',
        'zip_code',
        'address',
        'number',
        'complement',
        'district',
        'city',
        'state',
        'buyer_id',
        'status',
    ];
}
