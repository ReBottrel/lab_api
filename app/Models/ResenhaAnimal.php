<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResenhaAnimal extends Model
{
    use HasFactory;

    protected $casts = [
        'localization' => 'array',
        'description' => 'array',
    ];

    protected $fillable = [
        'user_id',
        'animal_id',
        'position',
        'photo_path',
        'brand_id',
        'localization',
        'description',
    ];
}
