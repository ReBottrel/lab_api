<?php

namespace App\Http\Controllers\Admin;

use App\Models\Animal;
use App\Models\OrderRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Concerns\ToArray;

class ApiMangalargaController extends Controller
{
    public function __construct()
    {
        ini_set('max_execution_time', 300);
    }
    public function getApi()
    {
        \Log::info('passei pelo cron de api');
        $coletas = $this->fetchDataFromApi('coletas', 18, 1, ['dataEnvioInicio' => '2023-02-12T00:00:00']);
        // dd($coletas);
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
        $animals = $this->fetchDataFromApi('coletas', 18, 1, [
            'rowidColeta' => $order->collection_number
        ]);
        foreach ($animals as $animal) {
            $data = Animal::firstOrCreate([
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
            if ($data->status == 6) {
                $data->status = 1;
                $data->order_id = $order->id;
                $data->save();
            }
        }

        \Log::info($data->toArray());
    }

    public function getResenha()
    {
        \Log::info('passei pelo cron');
        $coletas = $this->fetchDataFromApi('coletas', 18, 2, ['dataEnvioInicio' => '2023-02-12T00:00:00']);
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
            $this->createResenha($order);
        }
        return response()->json('ok');
    }
    public function createResenha(OrderRequest $order)
    {
        $animals = $this->fetchDataFromApi('coletas', 18, 2, [
            'rowidColeta' => $order->collection_number
        ]);
        foreach ($animals as $animal) {
            $data2 =  Animal::firstOrCreate([
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
            if ($data2->status == 6) {
                $data2->status = 1;
                $data2->order_id = $order->id;
                $data2->save();
            }
        }
        \Log::info($data2->toArray());
    }

    private function fetchDataFromApi($resource, $id, $tipo, $query = [])
    {
        $url = "http://laboratorios.abccmm.org.br/api/$resource/$id/$tipo" . '?' . http_build_query($query);
        $response = Http::get($url);
        return json_decode($response->body());
    }
}
