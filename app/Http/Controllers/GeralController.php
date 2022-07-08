<?php

namespace App\Http\Controllers;

use App\Models\TabelaGeral;
use Illuminate\Http\Request;

class GeralController extends Controller
{
    ##############################################################
    ####################GERAÇÂO DE TABELAS########################
    public function getTabela(Request $request)
    {
        $data = TabelaGeral::where(function($query) use($request){
            if(isset($request->filter)){
                foreach($request->filter as $filter){
                    $query = $query->where($filter['column'], ($filter['condition'] ?? '='), $filter['value']);
                }
            }
            return $query;
        });

        $data = $data->paginate($request->per_page ?? 20);

        return response()->json($data, 200);
    }

    public function postTabela(Request $request)
    {
        $tabela_geral = TabelaGeral::create($request->all());

        return response()->json($tabela_geral);
    }
    ####################GERAÇÂO DE TABELAS########################
    ##############################################################

    public function FunctionName(Type $var = null)
    {
        # code...
    }
}
