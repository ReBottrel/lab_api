<?php

namespace App\Http\Controllers\Admin;

use App\Models\Parceiro;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ParceiroController extends Controller
{
    public function index()
    {
        $parceiros = Parceiro::get();
        return view('admin.parcerios.index', get_defined_vars());
    }

    public function store(Request $request)
    {
        $parceiro = Parceiro::create($request->all());
        return redirect()->route('parceiros')->with('success', 'Parceiro criado com sucesso!');
    }

    public function destroy(Request $request)
    {

        $parceiro = Parceiro::find($request->id);
        $parceiro->delete();
        return response()->json(['success' => 'Parceiro deletado com sucesso!']);
    }
}
