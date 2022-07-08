<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_type',
        'reference_id',
        'type',
        'reason',
        'description',
    ];
}
