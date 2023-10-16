<?php

namespace App\Http\Controllers\Admin;

use App\Models\Log;
use App\Models\User;
use App\Models\Owner;
use App\Models\Animal;
use App\Models\Tecnico;
use App\Models\DnaVerify;
use App\Models\OrderRequest;
use Illuminate\Http\Request;
use App\Models\AnimalToParent;
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
        // $coletas = $this->fetchDataFromApi('coletas', 18, 1, ['dataEnvioInicio' => '2023-07-21T00:00:00']);
        foreach ($coletas as $coleta) {
            $user = User::where('email', $coleta->cliente->email)->first();
            $tecnico = Tecnico::where('professional_name', $coleta->tecnico->nome)->first();
            $owner = Owner::where('email', $coleta->cliente->email)->first();
            $ownerid = $owner->id ?? null;
            if (!$tecnico) {
                $tecnicoc = Tecnico::create([
                    'name' => $coleta->tecnico->nome,
                    'matricula' => $coleta->matricula->matricula,
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
                'technical_manager' => $coleta->tecnico->nome,
                'collection_date' => $coleta->dataColeta,
                'id_tecnico' => $tecnico->id ?? $tecnicoc->id,
                'status' => 1,
                'owner_id' => $ownerid,
                'parceiro' => 'ABCCMM'
            ]);

            foreach ($coleta->animais as $animal) {
                $existingAnimal = Animal::where('register_number_brand', $animal->rowidAnimal)->first();
                $currentAnimal = null;  // Inicializa a variável que irá armazenar o animal atual

                if ($existingAnimal) {
                    $codlab = $this->generateUniqueCodlab('EQU');
                    // $existingAnimal->status = 1;
        
                    $existingAnimal->order_id = $order->id; // atualize o status como necessário
                    $existingAnimal->save();
                    $currentAnimal = $existingAnimal;
                } else {
                    $codlab = $this->generateUniqueCodlab('EQU');
                    $newAnimal = Animal::create([
                        'register_number_brand' => $animal->rowidAnimal,
                        'order_id' => $order->id,
                        'animal_name' => $animal->nome,
                        'sex' => $animal->sexo,
                        'birth_date' => $animal->dataNascimento,
                        'description' => $animal->obs,
                        'especies' => 'EQUINA',
                        'breed' => 'MANGALARGA MARCHADOR',
                        'status' => 1,
                        'registro_pai' => $animal->registroPai,
                        'pai' => $animal->nomePai,
                        'registro_mae' => $animal->registroMae,
                        'mae' => $animal->nomeMae,
                        'codlab' => $codlab,
                        'row_id' => $animal->rowidAnimal,
                    ]);


                    $currentAnimal = $newAnimal;
                    $verify = DnaVerify::create([
                        'animal_id' => $currentAnimal->id,
                        'order_id' => $order->id,
                        'verify_code' => 'EQUTR',
                    ]);
                }
                $animalToParent = AnimalToParent::updateOrCreate(
                    ['animal_id' => $currentAnimal->id],  // Condições para encontrar um registro existente
                    [
                        'animal_name' => $animal->nome,
                        'especies' => 'EQUINA',
                        'animal_register' => null,
                        'register_pai' => $animal->registroPai ?? null,
                        'register_mae' => $animal->registroMae ?? null,
                    ]  // Valores para atualizar ou criar
                );
            }
        }

        $log = Log::create([
            'user' => 'Sistema API',
            'action' => 'Criou pedido de exame',
            'order_id' => $order->id ?? 'deu erro',
            'animal' => $animal->nome ?? 'deu erro',
        ]);
        return response()->json('ok');
    }



    public function getResenha()
    {

        // $coletas = $this->fetchDataFromApi('coletas', 18, 2, ['dataEnvioInicio' => date('Y-m-d\TH:i:s', strtotime('-1 day'))]);
        // $coletas = $this->fetchDataFromApi('coletas', 18, 2, ['rowidColeta' => '756744']);
         $coletas = $this->fetchDataFromApi('coletas', 18, 2, ['dataEnvioInicio' => '2023-10-09T00:00:00']);
        foreach ($coletas as $coleta) {
            // find owner, user, and tecnico by email or create them if they don't exist
            

            $user = User::where('email', $coleta->cliente->email)->first();
            $tecnico = Tecnico::where('professional_name', $coleta->tecnico->nome)->first();
            $owner = Owner::where('email', $coleta->cliente->email)->first();
            $ownerid = $owner->id ?? null;
            if (!$tecnico) {
               
                $tecnicoc = Tecnico::create([
                    'name' => $coleta->tecnico->nome,
                    'matricula' => $coleta->matricula->matricula,
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
                'collection_number' => $coleta->rowidColeta,
            ], [
                'origin' => 'API',
                'user_id' => $user->id ?? $userc->id,
                'creator' => $coleta->cliente->nome,
                'creator_number' => $coleta->cliente->matricula,
                'technical_manager' => $coleta->tecnico->nome,
                'collection_date' => $coleta->dataColeta,
                'id_tecnico' => $tecnico->id ?? $tecnicoc->id,
                'status' => 1,
                'owner_id' => $ownerid,
                'parceiro' => 'ABCCMM',
            ]);

            foreach ($coleta->animais as $animal) {
                \Log::info([$animal]);
                $codlab = $this->generateUniqueCodlab('EQU');
                $existingAnimal = Animal::where('register_number_brand', $animal->rowidAnimal)->first();
                $currentAnimal = null;
                if ($existingAnimal) {
                    // $existingAnimal->status = 1;
                  
                    $existingAnimal->order_id = $order->id; // atualize o status como necessário
                    $existingAnimal->save();
                    $currentAnimal = $existingAnimal;
                } else {
                    $codlab = $this->generateUniqueCodlab('EQU');

                    $newAnimal = Animal::create([
                        'register_number_brand' => $animal->rowidAnimal,
                        'order_id' => $order->id,
                        'animal_name' => $animal->nome,
                        'sex' => $animal->sexo,
                        'birth_date' => $animal->dataNascimento,
                        'description' => $animal->obs,
                        'status' => 1,
                        'codlab' => $codlab,
                        'especies' => 'EQUINA',
                        'breed' => 'MANGALARGA MARCHADOR',
                        'registro_pai' => $animal->registroPai,
                        'pai' => $animal->nomePai,
                        'registro_mae' => $animal->registroMae,
                        'mae' => $animal->nomeMae,
                        'row_id' => $animal->rowidAnimal,
                    ]);

                    $currentAnimal = $newAnimal;

                    $verify = DnaVerify::create([
                        'animal_id' => $currentAnimal->id,
                        'order_id' => $order->id,
                        'verify_code' => 'EQUTR',
                    ]);
                }
                $animalToParent = AnimalToParent::updateOrCreate(
                    ['animal_id' => $currentAnimal->id],  // Condições para encontrar um registro existente
                    [
                        'animal_name' => $animal->nome,
                        'especies' => 'EQUINA',
                        'animal_register' => null,
                        'register_pai' => $animal->registroPai ?? null,
                        'register_mae' => $animal->registroMae ?? null,
                    ]  // Valores para atualizar ou criar
                );
            }
        }
        $log = Log::create([
            'user' => 'Sistema API',
            'action' => 'Criou pedido de exame',
            'order_id' => $order->id ?? 'deu erro',
            'animal' => $animal->nome ?? 'deu erro',
        ]);
        return response()->json('ok');
    }

    public function getRowId()
    {
        $coletas = $this->fetchDataFromApi('coletas', 18, 2, ['dataEnvioInicio' => '2023-06-05T00:00:00']);

        foreach ($coletas as $coleta) {
            foreach ($coleta->animais as $animal) {
                //   dd($animal);
                $existingAnimal = Animal::where('animal_name', $animal->nome)->first();

                if ($existingAnimal) {
                    $existingAnimal->register_number_brand = $animal->rowidAnimal;
                    $existingAnimal->row_id = $animal->rowidAnimal;
                    $existingAnimal->save();
                }
            }
        }
        return response()->json('ok');
    }

    private function generateUniqueCodlab($sigla)
    {
        $maxNumber = Animal::selectRaw('MAX(CAST(SUBSTRING(codlab, 4) AS UNSIGNED)) as max_num')
            ->whereRaw('CAST(SUBSTRING(codlab, 4) AS UNSIGNED) >= 100000 AND CAST(SUBSTRING(codlab, 4) AS UNSIGNED) < 200000')
            ->first();

        $startValue = ($maxNumber && $maxNumber->max_num) ? $maxNumber->max_num + 1 : 100000;

        // Verifique a unicidade da parte numérica do codlab em todo o banco de dados
        while (Animal::whereRaw('CAST(SUBSTRING(codlab, 4) AS UNSIGNED) = ?', [$startValue])->exists()) {
            $startValue += 1;
        }

        return $sigla . strval($startValue);
    }
    public function fetchDataFromApi($resource, $id, $tipo, $query = [])
    {
        $url = "http://laboratorios.abccmm.org.br/api/$resource/$id/$tipo" . '?' . http_build_query($query);
        $response =  Http::get($url);
        return json_decode($response->body());
    }
}
