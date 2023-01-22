<?php

namespace App\Http\Controllers\Admin;

use App\Models\Animal;
use App\Models\OrderRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class ApiMangalargaController extends Controller
{
    public function __construct()
    {
        ini_set('max_execution_time', 300);
    }
    public function getApi()
    {
        $coletas = $this->fetchDataFromApi('coletas', 18, ['dataColetaInicio' => '2023-01-15T00:00:00']);
        foreach ($coletas as $coleta) {
            $order = OrderRequest::firstOrCreate([
                'collection_number' => $coleta->rowidColeta
            ], [
                'origin' => 'API',
                'creator' => $coleta->proprietario,
                'technical_manager' => $coleta->tecnico,
                'collection_date' => $coleta->dataColeta,
                'status' => 1,
            ]);
            $this->createAnimals($order);
        }
        return response()->json('ok');
    }

    public function createAnimals(OrderRequest $order)
    {
        $animals = $this->fetchDataFromApi('coletas', 18, [
            'rowidColeta' => $order->collection_number
        ]);
        foreach ($animals as $animal) {
            Animal::firstOrCreate([
                'register_number_brand' => $animal->rowidAnimal
            ], [
                'order_id' => $order->id,
                'animal_name' => $animal->produto,
                'sex' => $animal->sexo,
                'birth_date' => $animal->dataNascimento,
                'description' => $animal->obs,
                'status' => 1,
                'registro_pai' => $animal->registroPai,
                'pai' => $animal->nomePai,
                'registro_mae' => $animal->registroMae,
                'mae' => $animal->nomeMae,
                'row_id' => $order->collection_number,
            ]);
        }
    }

    private function fetchDataFromApi($resource, $id, $query = [])
    {
        $url = "http://laboratorios.abccmm.org.br/api/$resource/$id" . '?' . http_build_query($query);
        $response = Http::get($url);
        return json_decode($response->body());
    }


    // public function getApi()
    // {
    //     // $response = Http::get('http://laboratorios.abccmm.org.br/api/coletas/18');
    //     $response = Http::get('http://laboratorios.abccmm.org.br/api/coletas/18?dataColetaInicio=2023-01-15T00:00:00');
    //     $data = $response->body();
    //     $data = json_decode($data);
    //     // dd($data);
    //     foreach ($data as $dado) {
    //         $create = OrderRequest::where('collection_number', $dado->rowidColeta)->exists();
    //         if (!$create) {
    //             $order =  OrderRequest::create([
    //                 'origin' => 'API',
    //                 'creator' => $dado->proprietario,
    //                 'technical_manager' => $dado->tecnico,
    //                 'collection_date' => $dado->dataColeta,
    //                 'collection_number' => $dado->rowidColeta,
    //                 'status' => 1,
    //             ]);
    //         }
    //     }
    //     $this->getAnimal();
    //     return response()->json('ok');
    // }
    // public function getAnimal()
    // {
    //     $orders = OrderRequest::where('origin', 'API')->get();
    //     foreach ($orders as $order) {
    //         $response = Http::get("http://laboratorios.abccmm.org.br/api/coletas/18?rowidColeta=$order->collection_number");
    //         $data = $response->body();
    //         $data = json_decode($data);

    //         foreach ($data as $dado) {
    //             $exists = Animal::where('register_number_brand', $dado->rowidAnimal)->exists();
    //             if ($exists) {
    //                 continue;
    //             } else {
    //                 $animal = Animal::create([
    //                     'order_id' => $order->id,
    //                     'register_number_brand' => $dado->rowidAnimal,
    //                     'animal_name' => $dado->produto,
    //                     'sex' => $dado->sexo,
    //                     'birth_date' => $dado->dataNascimento,
    //                     'description' => $dado->obs,
    //                     'status' => 1,
    //                     'registro_pai' => $dado->registroPai,
    //                     'pai' => $dado->nomePai,
    //                     'registro_mae' => $dado->registroMae,
    //                     'mae' => $dado->nomeMae,
    //                     'row_id' => $order->collection_number,
    //                 ]);
    //             }
    //         }
    //     }
    //     return response()->json('ok');
    // }

}
