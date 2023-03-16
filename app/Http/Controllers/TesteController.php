<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use Illuminate\Http\Request;
use App\Models\ResenhaAnimal;
use Illuminate\Support\Facades\Http;

class TesteController extends Controller
{
    public function index()
    {
        return view('admin.teste');
    }

    public function duplicate()
    {
        $owner = Owner::get();
        $ownernovo = $owner->groupBy('owner_name')->filter(function ($item) {
            return $item->count() > 1;
        })->map(function ($item) {
            return $item->count();
        });
        \Log::info($ownernovo->toArray());
    }
}
