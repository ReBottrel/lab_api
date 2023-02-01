<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FurParent extends Model
{
    use HasFactory;

    protected $fillable = [
        'animal_id',
        'father_fur',
        'mother_fur',
        'mother_name',
        'father_name',
    ];
}
