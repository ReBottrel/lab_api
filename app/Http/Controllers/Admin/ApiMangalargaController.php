<?php

namespace App\Http\Controllers\Admin;

use App\Models\Animal;
use App\Models\OrderRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class ApiMangalargaController extends Controller
{
    public function getApi()
    {
        $response = Http::get('http://laboratorios.abccmm.org.br/api/coletas/18');
        $data = $response->body();
        $data = json_decode($data);

        foreach ($data as $dado) {

            $create = OrderRequest::where('collection_number', $dado->rowidColeta)->first();
            if (!$create) {
                $order =  OrderRequest::create([
                    'origin' => 'API',
                    'creator' => $dado->proprietario,
                    'technical_manager' => $dado->tecnico,
                    'collection_date' => $dado->dataColeta,
                    'collection_number' => $dado->rowidColeta,
                    'status' => 1,
                ]);

                Animal::create([
                    'order_id' => $order->id,
                    'register_number_brand' => $dado->rowidAnimal,
                    'animal_name' => $dado->produto,
                    'sex' => $dado->sexo,
                    'birth_date' => $dado->dataNascimento,
                    'description' => $dado->obs,
                    'status' => 1,
                    'registro_pai' => $dado->registroPai,
                    'pai' => $dado->nomePai,
                    'registro_mae' => $dado->registroMae,
                    'mae' => $dado->nomeMae,
                ]);
            }
        }

        return response()->json($order);
    }
}
