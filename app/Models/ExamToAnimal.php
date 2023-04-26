<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamToAnimal extends Model
{
    use HasFactory;
    protected $table = 'exam_to_animals';
    protected $fillable = [
        'exam_id',
        'animal_id',
        'order_id',
        'status',
        'total_price',
    ];
}
