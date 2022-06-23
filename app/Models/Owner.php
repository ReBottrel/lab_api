<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'animal_id',
        'document',
        'owner_name',
        'email',
        'fone',
        'cell',
        'whatsapp',
        'zip_code',
        'address',
        'number',
        'complement',
        'district',
        'city',
        'state',
        'status',
    ];

    public function animals()
    {
        return $this->hasMany(Animal::class, 'id', 'animal_id');
    }
}
