<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'animal',
        'title',
        'short_description',
        'value',
        'requests',
        'extra_value',
        'status',
        'type',
        'days'
    ];
}
