<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marking extends Model
{
    use HasFactory;

    protected $fillable = [
        'mark_name',
        'mark_path',
        'categorie'
    ];
}
