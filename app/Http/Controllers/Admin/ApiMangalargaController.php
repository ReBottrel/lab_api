<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Owner;
use App\Models\Animal;
use App\Models\OrderRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tecnico;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Concerns\ToArray;

class ApiMangalargaController extends Controller
{
    public function __construct()
    {
        ini_set('max_execution_time', 5000);
    }
    public function getApi()
    {
        \Log::info('passei pelo cron de api');
        $coletas = $this->fetchDataFromApi('coletas', 18, 1, ['dataEnvioInicio' => '2023-02-17T00:00:00']);
        // dd($coletas);

        foreach ($coletas as $coleta) {
            $user = User::where('email', $coleta->cliente->email)->first();
            $tecnico = Tecnico::where('professional_name', $coleta->tecnico)->first();
            $owner = Owner::where('email', $coleta->cliente->email)->first();
            if (!$tecnico) {
                $tecnicoc = Tecnico::create([
                    'name' => $coleta->tecnico,
                ]);
            }
            if (!$user) {
                $userc = User::create([
                    'name' => $coleta->cliente->nome,
                    'email' => $coleta->cliente->email,
                    'password' => Hash::make($coleta->cliente->cpf_Cnpj),
                    'permission' => 1,
                ]);

                $userc->info()->create([
                    'user_id' => $userc->id,
                    'document' => $coleta->cliente->cpf_Cnpj,
                    'phone' => $coleta->cliente->telefones[0]->telefone,
                    'cellphone' => $coleta->cliente->telefones[1]->telefone,
                    'number' => $coleta->cliente->enderecos[0]->numero,
                    'address' => $coleta->cliente->enderecos[0]->logradouro,
                    'complement' => $coleta->cliente->enderecos[0]->complemento,
                    'district' => $coleta->cliente->enderecos[0]->bairro,
                    'city' => $coleta->cliente->enderecos[0]->cidade,
                    'state' => $coleta->cliente->enderecos[0]->uf,
                    'zip_code' => $coleta->cliente->enderecos[0]->cep,
                ]);
                $ownerc = Owner::firstOrCreate([
                    'email' => $coleta->cliente->email,
                ], [
                    'user_id' => $userc->id,
                    'document' => $coleta->cliente->cpf_Cnpj,
                    'owner_name' => $coleta->cliente->nome,
                    'fone' => $coleta->cliente->telefones[0]->telefone,
                    'cell' => $coleta->cliente->telefones[1]->telefone,
                    'whatsapp' => $coleta->cliente->telefones[1]->telefone,
                    'zip_code' => $coleta->cliente->enderecos[0]->cep,
                    'address' => $coleta->cliente->enderecos[0]->logradouro,
                    'number' => $coleta->cliente->enderecos[0]->numero,
                    'complement' => $coleta->cliente->enderecos[0]->complemento,
                    'district' => $coleta->cliente->enderecos[0]->bairro,
                    'city' => $coleta->cliente->enderecos[0]->cidade,
                    'state' => $coleta->cliente->enderecos[0]->uf,
                    'status' => 1,
                    'propriety' =>  $coleta->cliente->fazendas[0]->nome,
                ]);
            }
            $order = OrderRequest::firstOrCreate([
                'collection_number' => $coleta->rowidColeta
            ], [
                'origin' => 'API',
                'user_id' => $user->id ?? $userc->id,
                'creator' => $coleta->cliente->nome,
                'creator_number' => $coleta->cliente->matricula,
                'technical_manager' => $coleta->tecnico,
                'collection_date' => $coleta->dataColeta,
                'id_tecnico' => $tecnico->id ?? $tecnicoc->id,
                'status' => 1,
                'owner_id' => $owner->id ? $owner->id : $ownerc->id,
            ]);

            foreach ($coleta->animais as $animal) {
                $data = Animal::firstOrCreate([
                    'register_number_brand' => $animal->rowidAnimal
                ], [
                    'order_id' => $order->id,
                    'animal_name' => $animal->nome,
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
        }
        return response()->json('ok');
    }

    public function createAnimals(OrderRequest $order)
    {
        $animals =  $this->fetchDataFromApi('coletas', 18, 1, [
            'rowidColeta' => $order->collection_number
        ]);
        foreach ($animals as $animal) {
            $data = Animal::firstOrCreate([
                'register_number_brand' => $animal->rowidAnimal
            ], [
                'order_id' => $order->id,
                'animal_name' => $animal->nome,
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

        $coletas = $this->fetchDataFromApi('coletas', 18, 2, ['dataEnvioInicio' => '2023-02-25T00:00:00']);
        foreach ($coletas as $coleta) {
            // find owner, user, and tecnico by email or create them if they don't exist
            $owner = Owner::firstOrCreate(['email' => $coleta->cliente->email], [
                'document' => $coleta->cliente->cpf_Cnpj,
                'owner_name' => $coleta->cliente->nome,
                'fone' => $coleta->cliente->telefones[0]->telefone ?? null,
                'cell' => $coleta->cliente->telefones[1]->telefone ?? null,
                'whatsapp' => $coleta->cliente->telefones[1]->telefone ?? null,
                'zip_code' => $coleta->cliente->enderecos[0]->cep ?? null,
                'address' => $coleta->cliente->enderecos[0]->logradouro ?? null,
                'number' => $coleta->cliente->enderecos[0]->numero ?? null,
                'complement' => $coleta->cliente->enderecos[0]->complemento ?? null,
                'district' => $coleta->cliente->enderecos[0]->bairro ?? null,
                'city' => $coleta->cliente->enderecos[0]->cidade ?? null,
                'state' => $coleta->cliente->enderecos[0]->uf ?? null,
                'status' => 1,
                'propriety' => $coleta->cliente->fazendas[0]->nome ?? null,
            ]);
            $user = User::firstOrCreate(['email' => $coleta->cliente->email], [
                'name' => $coleta->cliente->nome,
                'password' => Hash::make($coleta->cliente->cpf_Cnpj),
                'permission' => 1,
            ]);
            $tecnico = Tecnico::firstOrCreate(['professional_name' => $coleta->tecnico], ['name' => $coleta->tecnico]);

            // create order request with the coleta data and related owner, user, and tecnico
            $order = OrderRequest::firstOrCreate(['collection_number' => $coleta->rowidColeta], [
                'origin' => 'API',
                'user_id' => $user->id,
                'creator' => $coleta->cliente->nome,
                'creator_number' => $coleta->cliente->matricula,
                'technical_manager' => $coleta->tecnico,
                'collection_date' => $coleta->dataColeta,
                'id_tecnico' => $tecnico->id,
                'status' => 1,
                'owner_id' => $owner->id,
            ]);
            foreach ($coleta->animais as $animal) {
                $data2 =  Animal::firstOrCreate([
                    'register_number_brand' => $animal->rowidAnimal
                ], [
                    'order_id' => $order->id,
                    'animal_name' => $animal->nome,
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
        }
        return response()->json('ok');
    }
    public function createResenha(OrderRequest $order)
    {
        $animals =  $this->fetchDataFromApi('coletas', 18, 2, [
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

    public function fetchDataFromApi($resource, $id, $tipo, $query = [])
    {
        $url = "http://laboratorios.abccmm.org.br/api/$resource/$id/$tipo" . '?' . http_build_query($query);
        $response =  Http::get($url);
        return json_decode($response->body());
    }
}
