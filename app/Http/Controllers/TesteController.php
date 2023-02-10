<?php

namespace App\Http\Controllers;

use App\Models\ResenhaAnimal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TesteController extends Controller
{
    public function index()
    {
        return view('admin.teste');
    }

    public function store(Request $request)
    {
        $image = $request->input('data');

        // $image = base64_decode($image);
        $data = ResenhaAnimal::create([
            'animal_id' => 2,
            'user_id' => 1,
            'resenha' => 1,
            'localization' => $image,
        ]);

        return response()->json(['message' => 'Imagem salva com sucesso!']);
    }
    public function show()
    {
        $data = ResenhaAnimal::find(15);
        return view('admin.teste-img', get_defined_vars());
    }
}
