<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Owner;
use App\Models\Animal;
use App\Models\Tecnico;
use App\Models\DnaVerify;
use App\Models\OrderRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Concerns\ToArray;

class ApiMangalargaController extends Controller
{
    public function __construct()
    {
        ini_set('max_execution_time', 600000);
    }
    public function getApi()
    {

        $coletas = $this->fetchDataFromApi('coletas', 18, 1, ['dataEnvioInicio' => date('Y-m-d\TH:i:s', strtotime('-1 day'))]);
        // dd($coletas);
        // $coletas = $this->fetchDataFromApi('coletas', 18, 1, ['dataEnvioInicio' => '2023-05-21T00:00:00']);
        foreach ($coletas as $coleta) {
            $user = User::where('email', $coleta->cliente->email)->first();
            $tecnico = Tecnico::where('professional_name', $coleta->tecnico)->first();
            $owner = Owner::where('email', $coleta->cliente->email)->first();
            $ownerid = $owner->id ?? null;
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
                $owner->update([
                    'user_id' => $user->id ?? $userc->id,
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
                    'propriety' =>  $coleta->cliente->fazendas[0]->nome ?? null,
                ]);
                $ownerid = $ownerc->id;
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
                'owner_id' => $ownerid,
                'parceiro' => 'ABCCMM'
            ]);

            foreach ($coleta->animais as $animal) {
                $existingAnimal = Animal::where('register_number_brand', $animal->rowidAnimal)->first();
                if ($existingAnimal) {
                    $codlab = 'EQU' . strval($this->generateUniqueCodlab());
                    $existingAnimal->status = 1;
                    $existingAnimal->codlab = Animal::where('codlab', $codlab)->exists() ? 'EQU' . strval($this->generateUniqueCodlab()) : $codlab;
                    $existingAnimal->order_id = $order->id; // atualize o status como necessário
                    $existingAnimal->save();
                } else {
                    $codlab = 'EQU' . strval($this->generateUniqueCodlab());
                    $newAnimal = Animal::create([
                        'register_number_brand' => $animal->rowidAnimal,
                        'order_id' => $order->id,
                        'animal_name' => $animal->nome,
                        'sex' => $animal->sexo,
                        'birth_date' => $animal->dataNascimento,
                        'description' => $animal->obs,
                        'especie' => 'EQUINA',
                        'status' => 1,
                        'registro_pai' => $animal->registroPai,
                        'pai' => $animal->nomePai,
                        'registro_mae' => $animal->registroMae,
                        'mae' => $animal->nomeMae,
                        'codlab' => Animal::where('codlab', $codlab)->exists() ? 'EQU' . strval($this->generateUniqueCodlab()) : $codlab,
                        'row_id' => $order->collection_number,
                    ]);

                    $verify = DnaVerify::create([
                        'animal_id' => $newAnimal->id,
                        'order_id' => $order->id,
                        'verify_code' => 'EQUTR',
                    ]);
                }
            }
        }
        return response()->json('ok');
    }



    public function getResenha()
    {

        $coletas = $this->fetchDataFromApi('coletas', 18, 2, ['dataEnvioInicio' => date('Y-m-d\TH:i:s', strtotime('-1 day'))]);
        // $coletas = $this->fetchDataFromApi('coletas', 18, 2, ['dataEnvioInicio' => '2023-06-05T00:00:00']);
        foreach ($coletas as $coleta) {
            // find owner, user, and tecnico by email or create them if they don't exist
            $user = User::where('email', $coleta->cliente->email)->first();
            $tecnico = Tecnico::where('professional_name', $coleta->tecnico)->first();
            $owner = Owner::where('email', $coleta->cliente->email)->first();
            $ownerid = $owner->id ?? null;
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
                    'number' => $coleta->cliente->enderecos[0]->numero,
                    'address' => $coleta->cliente->enderecos[0]->logradouro,
                    'complement' => $coleta->cliente->enderecos[0]->complemento,
                    'district' => $coleta->cliente->enderecos[0]->bairro,
                    'city' => $coleta->cliente->enderecos[0]->cidade,
                    'state' => $coleta->cliente->enderecos[0]->uf,
                    'zip_code' => $coleta->cliente->enderecos[0]->cep,
                ]);
                if ($owner) {
                    $owner->update([
                        'user_id' => $userc->id ? $userc->id : $user->id,
                    ]);
                }
                $ownerc = Owner::firstOrCreate([
                    'email' => $coleta->cliente->email,
                ], [
                    'user_id' => $userc->id,
                    'document' => $coleta->cliente->cpf_Cnpj,
                    'owner_name' => $coleta->cliente->nome,
                    'fone' => $coleta->cliente->telefones[0]->telefone,
                    'cell' => $coleta->cliente->telefones[1]->telefone ?? null,
                    'whatsapp' => $coleta->cliente->telefones[1]->telefone ?? null,
                    'zip_code' => $coleta->cliente->enderecos[0]->cep,
                    'address' => $coleta->cliente->enderecos[0]->logradouro,
                    'number' => $coleta->cliente->enderecos[0]->numero,
                    'complement' => $coleta->cliente->enderecos[0]->complemento,
                    'district' => $coleta->cliente->enderecos[0]->bairro,
                    'city' => $coleta->cliente->enderecos[0]->cidade,
                    'state' => $coleta->cliente->enderecos[0]->uf,
                    'status' => 1,
                    'propriety' =>  $coleta->cliente->fazendas[0]->nome ?? null,
                ]);
                $ownerid = $ownerc->id;
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
                'owner_id' => $ownerid,
                'parceiro' => 'ABCCMM'
            ]);

            foreach ($coleta->animais as $animal) {
                $codlab = 'EQU' . strval($this->generateUniqueCodlab());
                $existingAnimal = Animal::where('register_number_brand', $animal->rowidAnimal)->first();
                if ($existingAnimal) {
                    $existingAnimal->status = 1;
                    $existingAnimal->codlab = Animal::where('codlab', $codlab)->exists() ? 'EQU' . strval($this->generateUniqueCodlab()) : $codlab;
                    $existingAnimal->order_id = $order->id; // atualize o status como necessário
                    $existingAnimal->save();
                } else {
                    $codlab = 'EQU' . strval($this->generateUniqueCodlab());
                    $newAnimal = Animal::create([
                        'register_number_brand' => $animal->rowidAnimal,
                        'order_id' => $order->id,
                        'animal_name' => $animal->nome,
                        'sex' => $animal->sexo,
                        'birth_date' => $animal->dataNascimento,
                        'description' => $animal->obs,
                        'status' => 1,
                        'codlab' => Animal::where('codlab', $codlab)->exists() ? 'EQU' . strval($this->generateUniqueCodlab()) : $codlab,
                        'especie' => 'EQUINA',
                        'registro_pai' => $animal->registroPai,
                        'pai' => $animal->nomePai,
                        'registro_mae' => $animal->registroMae,
                        'mae' => $animal->nomeMae,
                        'row_id' => $order->collection_number,
                    ]);

                    $verify = DnaVerify::create([
                        'animal_id' => $newAnimal->id,
                        'order_id' => $order->id,
                        'verify_code' => 'EQUTR',
                    ]);
                }
            }
        }
        return response()->json('ok');
    }


    private function generateUniqueCodlab()
    {
        $startValue = 100000;
        $codlab = Animal::max('codlab');

        if ($codlab >= $startValue) {
            if (is_numeric($codlab)) {
                $codlab = intval($codlab);
            } else {
                $codlab = $startValue;
            }
            $codlab += 1;
        } else {
            $codlab = $startValue;
        }

        while (Animal::where('codlab', $codlab)->exists()) {
            $codlab += 1;
        }

        return $codlab;
    }
    public function fetchDataFromApi($resource, $id, $tipo, $query = [])
    {
        $url = "http://laboratorios.abccmm.org.br/api/$resource/$id/$tipo" . '?' . http_build_query($query);
        $response =  Http::get($url);
        return json_decode($response->body());
    }
}
