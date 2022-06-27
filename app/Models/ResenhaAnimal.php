<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'resenha',
        'photo_path',
        'localization',
        'description',
        'status',
    ];
}
