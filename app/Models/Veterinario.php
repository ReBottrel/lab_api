<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Veterinario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'permission',
        'access_token',
        'token_expires_in',
        'status',
        'association_id',
        'cpf',
        'portaria',
        'crmv',
        'phone',
        'address',
        'number',
        'complement',
        'district',
        'city',
        'state',
        'cep',
        
    ];
}
