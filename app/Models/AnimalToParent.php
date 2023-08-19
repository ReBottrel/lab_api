<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimalToParent extends Model
{
    use HasFactory;

    protected $fillable = [
        'animal_id',
        'animal_name',
        'especies',
        'animal_register',
        'mae_id',
        'pai_id',
        'register_pai',
        'register_mae',
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }
}
