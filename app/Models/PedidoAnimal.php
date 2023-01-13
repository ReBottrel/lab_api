<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoAnimal extends Model
{
    use HasFactory;

    protected $table = 'pedido_animals';
    protected $fillable = ['id_pedido', 'id_animal', 'status'];


    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }

    public function animal()
    {
        return $this->belongsTo(Animal::class, 'id_animal');
    }

    
}
