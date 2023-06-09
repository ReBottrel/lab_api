<?php

namespace App\Http\Controllers\Admin;

use App\Models\Alelo;
use App\Models\Laudo;
use App\Models\Owner;
use App\Models\Animal;
use App\Models\DataColeta;
use App\Models\OrdemServico;
use App\Models\OrderRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LaudoController extends Controller
{
    public function store(Request $request)
    {
        $ordem = OrdemServico::find($request->ordem);
        $order = OrderRequest::find($ordem->order);
        $animal = Animal::find($ordem->animal_id);
        $pai = Animal::where('animal_name', $animal->pai)->first();
        $mae = Animal::where('animal_name', $animal->mae)->first();
        $datas = DataColeta::where('id_order', $order->id)->first();
        $laudo = Laudo::find($request->laudo);
        if (!$laudo) {
            $laudo = Laudo::create([
                'animal_id' => $ordem->animal_id,
                'mae_id' => $mae->id,
                'pai_id' => $pai->id,
                'veterinario' => $ordem->tecnico,
                'owner_id' => $ordem->owner_id,
                'data_coleta' => $datas->data_coleta,
                'data_realizacao' => $datas->data_recebimento,
                'data_lab' => $datas->data_laboratorio,
                'codigo_busca' => '123456789',
                'observacao' => $request->obs,
                'conclusao' => $request->conclusao,
            ]);
        } else {
            $laudo->update([
                'animal_id' => $ordem->animal_id,
                'mae_id' => $mae->id,
                'pai_id' => $pai->id,
                'veterinario' => $ordem->tecnico,
                'owner_id' => $ordem->owner_id,
                'data_coleta' => $datas->data_coleta,
                'data_realizacao' => $datas->data_recebimento,
                'data_lab' => $datas->data_laboratorio,
                'codigo_busca' => '123456789',
                'observacao' => $request->obs,
                'conclusao' => $request->conclusao,
            ]);
        }


        return response()->json($laudo, 200);
    }
}
