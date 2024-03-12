<?php

namespace App\Http\Controllers\Admin;

use App\Models\Animal;
use App\Models\Parceiro;
use App\Models\OrderRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Laudo;
use App\Models\OrdemServico;

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


    public function searchOrdersView()
    {
        return view('admin.parcerios.buscar-pedido', get_defined_vars());
    }

    public function searchOrders(Request $request)
    {
        $pedidos = Animal::where('animal_name', $request->name)->get();

        $dados = [];

        foreach ($pedidos as $pedido) {
        
            $laudo = Laudo::where('animal_id', $pedido->id)->first();
            $ordemServico = OrdemServico::where('animal_id', $pedido->id)->first();

            $dados[] = [
                'id' => $pedido->register_number_brand,
                'animal_name' => $pedido->animal_name,
                'status' => $pedido->status,
                'data' =>   date('d/m/Y', strtotime($ordemServico->data)),
                'pdf' => $laudo->id ?? null,
            ];
        }

        return response()->json($dados);
    }
}
