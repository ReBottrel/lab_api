<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    use HasFactory;

    protected $casts = [
        // 'classification' => 'array',
    ];

    protected $fillable = [
        'user_id',
        'order_id',
        'register_number_brand',
        'animal_name',
        'classification',
        'especies',
        'breed',
        'sex',
        'age',
        'utility',
        'animal_location',
        'city',
        'state',
        'number_existing_equines',
        'birth_date',
        'fur',
        'description',
        'status',
        'chip_number',
        'registro_pai',
        'pai',
        'registro_mae',
        'mae',
        'owner_id',
        'codlab',
        'especie_pai',
        'especie_mae',
        'row_id',
        'vet_id',
        'oesa_cad',
        'numero_aie',
        'numero_mormo',
        'collect_date',
    ];

    public function owner()
    {
        return $this->hasOne(Owner::class, 'animal_id');
    }

    public function resenhas()
    {
        return $this->hasMany(ResenhaAnimal::class, 'animal_id');
    }

    public function order()
    {
        return $this->belongsTo(OrderRequest::class, 'order_id');
    }
}
