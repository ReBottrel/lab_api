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
use App\Models\UserInfo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Concerns\ToArray;

class ApiMangalargaController extends Controller
{
    public function __construct()
    {
        ini_set('max_execution_time', 600000);
    }




    public function getApi()
    {
        try {
            // Fetch data from API
            $coletas = $this->fetchDataFromApi('coletas', 18, 1, ['dataEnvioInicio' => date('Y-m-d\TH:i:s', strtotime('-1 day'))]);

            foreach ($coletas as $coleta) {
                // Find or create entities
                $user = User::firstOrCreate(
                    ['name' => $coleta->cliente->nome],
                    [
                        'name' => $coleta->cliente->nome,
                        'email' => $coleta->cliente->email,
                        'password' => Hash::make($coleta->cliente->cpf_Cnpj),
                        'permission' => 1,
                    ]
                );

                // Encontrar os diferentes tipos de telefone
                $telefones = collect($coleta->cliente->telefones)->mapWithKeys(function ($telefone) {
                    return [$telefone->tipo => $telefone->telefone];
                });

                $userinfo = UserInfo::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'user_id' => $user->id,
                        'document' => $coleta->cliente->cpf_Cnpj,
                        'fone' => $telefones['Residencial'] ?? $telefones['Comercial'] ?? $telefones->first(),
                        'phone' => $telefones['Celular'] ?? null,
                        'zip_code' => $coleta->cliente->enderecos[0]->cep,
                        'address' => $coleta->cliente->enderecos[0]->logradouro,
                        'number' => $coleta->cliente->enderecos[0]->numero,
                        'complement' => $coleta->cliente->enderecos[0]->complemento,
                        'district' => $coleta->cliente->enderecos[0]->bairro,
                        'city' => $coleta->cliente->enderecos[0]->cidade,
                        'state' => $coleta->cliente->enderecos[0]->uf,
                        'status' => 1,
                        'propriety' =>  $coleta->cliente->fazendas[0]->nome ?? null,
                    ]
                );

                $tecnico = Tecnico::firstOrCreate(
                    ['professional_name' => $coleta->tecnico->nome],
                    ['name' => $coleta->tecnico->nome, 'matricula' => $coleta->tecnico->matricula]
                );

                $owner = Owner::updateOrCreate(
                    ['email' => $coleta->cliente->email],
                    [
                        'user_id' => $user->id,
                        'document' => $coleta->cliente->cpf_Cnpj,
                        'owner_name' => $coleta->cliente->nome,
                        'fone' => $telefones['Residencial'] ?? $telefones['Comercial'] ?? $telefones->first(),
                        'cell' => $telefones['Celular'] ?? null,
                        'whatsapp' => $telefones['Celular'] ?? null,
                        'zip_code' => $coleta->cliente->enderecos[0]->cep,
                        'address' => $coleta->cliente->enderecos[0]->logradouro,
                        'number' => $coleta->cliente->enderecos[0]->numero,
                        'complement' => $coleta->cliente->enderecos[0]->complemento,
                        'district' => $coleta->cliente->enderecos[0]->bairro,
                        'city' => $coleta->cliente->enderecos[0]->cidade,
                        'state' => $coleta->cliente->enderecos[0]->uf,
                        'status' => 1,
                        'propriety' =>  $coleta->cliente->fazendas[0]->nome ?? null,
                    ]
                );

                $order = OrderRequest::updateOrCreate(
                    ['collection_number' => $coleta->rowidColeta],
                    [
                        'origin' => 'API',
                        'user_id' => $user->id,
                        'creator' => $coleta->cliente->nome,
                        'creator_number' => $coleta->cliente->matricula,
                        'technical_manager' => $coleta->tecnico->nome,
                        'collection_date' => $coleta->dataColeta,
                        'id_tecnico' => $tecnico->id,
                        'status' => 1,
                        'owner_id' => $owner->id,
                        'parceiro' => 'ABCCMM',
                    ]
                );

                foreach ($coleta->animais as $animal) {
                    $existingAnimal = Animal::updateOrCreate(
                        ['register_number_brand' => $animal->rowidAnimal],
                        [
                            'order_id' => $order->id,
                            'animal_name' => $animal->nome,
                            'sex' => $animal->sexo,
                            'birth_date' => $animal->dataNascimento,
                            'description' => $animal->obs,
                            'status' => 1,
                            'codlab' => $this->generateUniqueCodlab('EQU'),
                            'especies' => 'EQUINA',
                            'breed' => 'MANGALARGA MARCHADOR',
                            'registro_pai' => $animal->registroPai,
                            'pai' => $animal->nomePai,
                            'registro_mae' => $animal->registroMae,
                            'mae' => $animal->nomeMae,
                            'row_id' => $animal->rowidAnimal,
                        ]
                    );

                    // Find or create parent animals
                    $pai = Animal::firstOrCreate(
                        ['number_definitive' => $animal->registroPai],
                        [
                            'animal_name' => $animal->nomePai,
                            'especies' => 'EQUINA',
                            'breed' => 'MANGALARGA MARCHADOR',
                            'codlab' => $this->generateUniqueCodlab('EQU'),
                        ]
                    );

                    $mae = Animal::firstOrCreate(
                        ['number_definitive' => $animal->registroMae],
                        [
                            'animal_name' => $animal->nomeMae,
                            'especies' => 'EQUINA',
                            'breed' => 'MANGALARGA MARCHADOR',
                            'codlab' => $this->generateUniqueCodlab('EQU'),
                        ]
                    );

                    // Create DNA verification record
                    DnaVerify::firstOrCreate(
                        ['animal_id' => $existingAnimal->id, 'order_id' => $order->id],
                        ['verify_code' => 'EQUTR']
                    );

                    // Update or create the AnimalToParent relationship
                    AnimalToParent::updateOrCreate(
                        [
                            'animal_id' => $existingAnimal->id,
                            'register_pai' => $pai->number_definitive,
                            'register_mae' => $mae->number_definitive,
                        ],
                        [
                            'animal_name' => $animal->nome,
                            'especies' => 'EQUINA',
                            'animal_register' => null,
                        ]
                    );
                }
            }

            // Log the action
            Log::create([
                'user' => 'Sistema API',
                'action' => 'Criou pedido de exame',
                'order_id' => $order->id ?? 'deu erro',
                'animal' => $animal->nome ?? 'deu erro',
            ]);

            return response()->json('ok');
        } catch (\Exception $e) {
            // Log the error
            \Log::info($e->getMessage());
            // return response()->json('Erro ao criar pedido de exame', 500);
        }
    }



    public function getResenha()
    {
        try {
            // Fetch data from API
            $coletas = $this->fetchDataFromApi('coletas', 18, 2, ['dataEnvioInicio' => date('Y-m-d\TH:i:s', strtotime('-1 day'))]);

 


            foreach ($coletas as $coleta) {
                // Find or create entities
                $user = User::firstOrCreate(
                    ['name' => $coleta->cliente->nome],
                    [
                        'name' => $coleta->cliente->nome,
                        'email' => $coleta->cliente->email,
                        'password' => Hash::make($coleta->cliente->cpf_Cnpj),
                        'permission' => 1,
                    ]
                );

                // Encontrar os diferentes tipos de telefone
                $telefones = collect($coleta->cliente->telefones)->mapWithKeys(function ($telefone) {
                    return [$telefone->tipo => $telefone->telefone];
                });

                $userinfo = UserInfo::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'user_id' => $user->id,
                        'document' => $coleta->cliente->cpf_Cnpj,
                        'fone' => $telefones['Residencial'] ?? $telefones['Comercial'] ?? $telefones->first(),
                        'phone' => $telefones['Celular'] ?? null,
                        'zip_code' => $coleta->cliente->enderecos[0]->cep,
                        'address' => $coleta->cliente->enderecos[0]->logradouro,
                        'number' => $coleta->cliente->enderecos[0]->numero,
                        'complement' => $coleta->cliente->enderecos[0]->complemento,
                        'district' => $coleta->cliente->enderecos[0]->bairro,
                        'city' => $coleta->cliente->enderecos[0]->cidade,
                        'state' => $coleta->cliente->enderecos[0]->uf,
                        'status' => 1,
                        'propriety' =>  $coleta->cliente->fazendas[0]->nome ?? null,
                    ]
                );

                $tecnico = Tecnico::firstOrCreate(
                    ['professional_name' => $coleta->tecnico->nome],
                    ['name' => $coleta->tecnico->nome, 'matricula' => $coleta->tecnico->matricula]
                );

                $owner = Owner::updateOrCreate(
                    ['email' => $coleta->cliente->email],
                    [
                        'user_id' => $user->id,
                        'document' => $coleta->cliente->cpf_Cnpj,
                        'owner_name' => $coleta->cliente->nome,
                        'fone' => $telefones['Residencial'] ?? $telefones['Comercial'] ?? $telefones->first(),
                        'cell' => $telefones['Celular'] ?? null,
                        'whatsapp' => $telefones['Celular'] ?? null,
                        'zip_code' => $coleta->cliente->enderecos[0]->cep,
                        'address' => $coleta->cliente->enderecos[0]->logradouro,
                        'number' => $coleta->cliente->enderecos[0]->numero,
                        'complement' => $coleta->cliente->enderecos[0]->complemento,
                        'district' => $coleta->cliente->enderecos[0]->bairro,
                        'city' => $coleta->cliente->enderecos[0]->cidade,
                        'state' => $coleta->cliente->enderecos[0]->uf,
                        'status' => 1,
                        'propriety' =>  $coleta->cliente->fazendas[0]->nome ?? null,
                    ]
                );

                $order = OrderRequest::updateOrCreate(
                    ['collection_number' => $coleta->rowidColeta],
                    [
                        'origin' => 'API',
                        'user_id' => $user->id,
                        'creator' => $coleta->cliente->nome,
                        'creator_number' => $coleta->cliente->matricula,
                        'technical_manager' => $coleta->tecnico->nome,
                        'collection_date' => $coleta->dataColeta,
                        'id_tecnico' => $tecnico->id,
                        'status' => 1,
                        'owner_id' => $owner->id,
                        'parceiro' => 'ABCCMM',
                    ]
                );

                foreach ($coleta->animais as $animal) {
                    $existingAnimal = Animal::updateOrCreate(
                        ['register_number_brand' => $animal->rowidAnimal],
                        [
                            'order_id' => $order->id,
                            'animal_name' => $animal->nome,
                            'sex' => $animal->sexo,
                            'birth_date' => $animal->dataNascimento,
                            'description' => $animal->obs,
                            'status' => 1,
                            'codlab' => $this->generateUniqueCodlab('EQU'),
                            'especies' => 'EQUINA',
                            'breed' => 'MANGALARGA MARCHADOR',
                            'registro_pai' => $animal->registroPai,
                            'pai' => $animal->nomePai,
                            'registro_mae' => $animal->registroMae,
                            'mae' => $animal->nomeMae,
                            'row_id' => $animal->rowidAnimal,
                        ]
                    );

                    // Find or create parent animals
                    $pai = Animal::firstOrCreate(
                        ['number_definitive' => $animal->registroPai],
                        [
                            'animal_name' => $animal->nomePai,
                            'especies' => 'EQUINA',
                            'breed' => 'MANGALARGA MARCHADOR',
                            'codlab' => $this->generateUniqueCodlab('EQU'),
                        ]
                    );

                    $mae = Animal::firstOrCreate(
                        ['number_definitive' => $animal->registroMae],
                        [
                            'animal_name' => $animal->nomeMae,
                            'especies' => 'EQUINA',
                            'breed' => 'MANGALARGA MARCHADOR',
                            'codlab' => $this->generateUniqueCodlab('EQU'),
                        ]
                    );

                    // Create DNA verification record
                    DnaVerify::firstOrCreate(
                        ['animal_id' => $existingAnimal->id, 'order_id' => $order->id],
                        ['verify_code' => 'EQUTR']
                    );

                    // Update or create the AnimalToParent relationship
                    AnimalToParent::updateOrCreate(
                        [
                            'animal_id' => $existingAnimal->id,
                            'register_pai' => $pai->number_definitive,
                            'register_mae' => $mae->number_definitive,
                        ],
                        [
                            'animal_name' => $animal->nome,
                            'especies' => 'EQUINA',
                            'animal_register' => null,
                        ]
                    );
                }
            }

            // Log the action
            Log::create([
                'user' => 'Sistema API',
                'action' => 'Criou pedido de exame',
                'order_id' => $order->id ?? 'deu erro',
                'animal' => $animal->nome ?? 'deu erro',
            ]);

            return response()->json('ok');
        } catch (\Exception $e) {
            // Log the error
            \Log::info($e->getMessage());
            // return response()->json('Erro ao criar pedido de exame', 500);
        }
    }

    public function getResenhaRequest(Request $request)
    {
        try {
            // Fetch data from API
            $coletas = $this->fetchDataFromApi('coletas', 18, 2, ['rowidColeta' => $request->rowidcoleta]);

            foreach ($coletas as $coleta) {
                // Find or create entities
                $user = User::firstOrCreate(
                    ['name' => $coleta->cliente->nome],
                    [
                        'name' => $coleta->cliente->nome,
                        'email' => $coleta->cliente->email,
                        'password' => Hash::make($coleta->cliente->cpf_Cnpj),
                        'permission' => 1,
                    ]
                );

                // Encontrar os diferentes tipos de telefone
                $telefones = collect($coleta->cliente->telefones)->mapWithKeys(function ($telefone) {
                    return [$telefone->tipo => $telefone->telefone];
                });

                $userinfo = UserInfo::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'user_id' => $user->id,
                        'document' => $coleta->cliente->cpf_Cnpj,
                        'fone' => $telefones['Residencial'] ?? $telefones['Comercial'] ?? $telefones->first(),
                        'phone' => $telefones['Celular'] ?? null,
                        'zip_code' => $coleta->cliente->enderecos[0]->cep,
                        'address' => $coleta->cliente->enderecos[0]->logradouro,
                        'number' => $coleta->cliente->enderecos[0]->numero,
                        'complement' => $coleta->cliente->enderecos[0]->complemento,
                        'district' => $coleta->cliente->enderecos[0]->bairro,
                        'city' => $coleta->cliente->enderecos[0]->cidade,
                        'state' => $coleta->cliente->enderecos[0]->uf,
                        'status' => 1,
                        'propriety' =>  $coleta->cliente->fazendas[0]->nome ?? null,
                    ]
                );


                $tecnico = Tecnico::firstOrCreate(
                    ['professional_name' => $coleta->tecnico->nome],
                    ['name' => $coleta->tecnico->nome, 'matricula' => $coleta->tecnico->matricula]
                );

                $owner = Owner::updateOrCreate(
                    ['email' => $coleta->cliente->email],
                    [
                        'user_id' => $user->id,
                        'document' => $coleta->cliente->cpf_Cnpj,
                        'owner_name' => $coleta->cliente->nome,
                        'fone' => $telefones['Residencial'] ?? $telefones['Comercial'] ?? $telefones->first(),
                        'cell' => $telefones['Celular'] ?? null,
                        'whatsapp' => $telefones['Celular'] ?? null,
                        'zip_code' => $coleta->cliente->enderecos[0]->cep,
                        'address' => $coleta->cliente->enderecos[0]->logradouro,
                        'number' => $coleta->cliente->enderecos[0]->numero,
                        'complement' => $coleta->cliente->enderecos[0]->complemento,
                        'district' => $coleta->cliente->enderecos[0]->bairro,
                        'city' => $coleta->cliente->enderecos[0]->cidade,
                        'state' => $coleta->cliente->enderecos[0]->uf,
                        'status' => 1,
                        'propriety' =>  $coleta->cliente->fazendas[0]->nome ?? null,
                    ]
                );

                $order = OrderRequest::updateOrCreate(
                    ['collection_number' => $coleta->rowidColeta],
                    [
                        'origin' => 'API',
                        'user_id' => $user->id,
                        'creator' => $coleta->cliente->nome,
                        'creator_number' => $coleta->cliente->matricula,
                        'technical_manager' => $coleta->tecnico->nome,
                        'collection_date' => $coleta->dataColeta,
                        'id_tecnico' => $tecnico->id,
                        'status' => 1,
                        'owner_id' => $owner->id,
                        'parceiro' => 'ABCCMM',
                    ]
                );

                foreach ($coleta->animais as $animal) {
                    $existingAnimal = Animal::updateOrCreate(
                        ['register_number_brand' => $animal->rowidAnimal],
                        [
                            'order_id' => $order->id,
                            'animal_name' => $animal->nome,
                            'sex' => $animal->sexo,
                            'birth_date' => $animal->dataNascimento,
                            'description' => $animal->obs,
                            'status' => 1,
                            'codlab' => $this->generateUniqueCodlab('EQU'),
                            'especies' => 'EQUINA',
                            'breed' => 'MANGALARGA MARCHADOR',
                            'registro_pai' => $animal->registroPai,
                            'pai' => $animal->nomePai,
                            'registro_mae' => $animal->registroMae,
                            'mae' => $animal->nomeMae,
                            'row_id' => $animal->rowidAnimal,
                        ]
                    );

                    $pai = Animal::firstOrCreate(
                        ['number_definitive' => $animal->registroPai],
                        [
                            'animal_name' => $animal->nomePai,
                            'especies' => 'EQUINA',
                            'breed' => 'MANGALARGA MARCHADOR',
                            'codlab' => $this->generateUniqueCodlab('EQU'),
                        ]
                    );

                    $mae = Animal::firstOrCreate(
                        ['number_definitive' => $animal->registroMae],
                        [
                            'animal_name' => $animal->nomeMae,
                            'especies' => 'EQUINA',
                            'breed' => 'MANGALARGA MARCHADOR',
                            'codlab' => $this->generateUniqueCodlab('EQU'),
                        ]
                    );

                    DnaVerify::firstOrCreate(
                        ['animal_id' => $existingAnimal->id, 'order_id' => $order->id],
                        ['verify_code' => 'EQUTR']
                    );

                    AnimalToParent::updateOrCreate(
                        ['animal_id' => $existingAnimal->id, 'register_pai' => $pai->number_definitive, 'register_mae' => $mae->number_definitive],
                        [
                            'animal_name' => $animal->nome,
                            'especies' => 'EQUINA',
                            'animal_register' => null,
                        ]
                    );
                }
            }

            Log::create([
                'user' => 'Sistema API',
                'action' => 'Criou pedido de exame',
                'order_id' => $order->id ?? 'deu erro',
                'animal' => $animal->nome ?? 'deu erro',
            ]);

            return redirect()->back()->with('success', 'Pedido de exame criado com sucesso');
        } catch (\Exception $e) {
            // Log error
            \Log::info($e->getMessage());
            return redirect()->back()->with('error', 'Erro ao criar pedido de exame');
        }
    }

    public function getColetaRequest(Request $request)
    {
        try {
            // Fetch data from API
            $coletas = $this->fetchDataFromApi('coletas', 18, 1, ['rowidColeta' => $request->rowidcoleta]);

            foreach ($coletas as $coleta) {
                // Find or create entities
                $user = User::firstOrCreate(
                    ['name' => $coleta->cliente->nome],
                    [
                        'name' => $coleta->cliente->nome,
                        'email' => $coleta->cliente->email,
                        'password' => Hash::make($coleta->cliente->cpf_Cnpj),
                        'permission' => 1,
                    ]
                );
                $userinfo = UserInfo::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'user_id' => $user->id,
                        'document' => $coleta->cliente->cpf_Cnpj,
                        'fone' => $coleta->cliente->telefones[0]->telefone,
                        'phone' => $coleta->cliente->telefones[1]->telefone ?? null,
                        'zip_code' => $coleta->cliente->enderecos[0]->cep,
                        'address' => $coleta->cliente->enderecos[0]->logradouro,
                        'number' => $coleta->cliente->enderecos[0]->numero,
                        'complement' => $coleta->cliente->enderecos[0]->complemento,
                        'district' => $coleta->cliente->enderecos[0]->bairro,
                        'city' => $coleta->cliente->enderecos[0]->cidade,
                        'state' => $coleta->cliente->enderecos[0]->uf,
                        'status' => 1,
                        'propriety' =>  $coleta->cliente->fazendas[0]->nome ?? null,
                    ]
                );


                $tecnico = Tecnico::firstOrCreate(
                    ['professional_name' => $coleta->tecnico->nome],
                    ['name' => $coleta->tecnico->nome, 'matricula' => $coleta->tecnico->matricula]
                );

                $owner = Owner::updateOrCreate(
                    ['email' => $coleta->cliente->email],
                    [
                        'user_id' => $user->id,
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
                    ]
                );

                $order = OrderRequest::updateOrCreate(
                    ['collection_number' => $coleta->rowidColeta],
                    [
                        'origin' => 'API',
                        'user_id' => $user->id,
                        'creator' => $coleta->cliente->nome,
                        'creator_number' => $coleta->cliente->matricula,
                        'technical_manager' => $coleta->tecnico->nome,
                        'collection_date' => $coleta->dataColeta,
                        'id_tecnico' => $tecnico->id,
                        'status' => 1,
                        'owner_id' => $owner->id,
                        'parceiro' => 'ABCCMM',
                    ]
                );

                foreach ($coleta->animais as $animal) {
                    $existingAnimal = Animal::updateOrCreate(
                        ['register_number_brand' => $animal->rowidAnimal],
                        [
                            'order_id' => $order->id,
                            'animal_name' => $animal->nome,
                            'sex' => $animal->sexo,
                            'birth_date' => $animal->dataNascimento,
                            'description' => $animal->obs,
                            'status' => 1,
                            'codlab' => $this->generateUniqueCodlab('EQU'),
                            'especies' => 'EQUINA',
                            'breed' => 'MANGALARGA MARCHADOR',
                            'registro_pai' => $animal->registroPai,
                            'pai' => $animal->nomePai,
                            'registro_mae' => $animal->registroMae,
                            'mae' => $animal->nomeMae,
                            'row_id' => $animal->rowidAnimal,
                        ]
                    );

                    // Find or create parent animals
                    $pai = Animal::firstOrCreate(
                        ['number_definitive' => $animal->registroPai],
                        [
                            'animal_name' => $animal->nomePai,
                            'especies' => 'EQUINA',
                            'breed' => 'MANGALARGA MARCHADOR',
                            'codlab' => $this->generateUniqueCodlab('EQU'),
                        ]
                    );

                    $mae = Animal::firstOrCreate(
                        ['number_definitive' => $animal->registroMae],
                        [
                            'animal_name' => $animal->nomeMae,
                            'especies' => 'EQUINA',
                            'breed' => 'MANGALARGA MARCHADOR',
                            'codlab' => $this->generateUniqueCodlab('EQU'),
                        ]
                    );

                    // Create DNA verification record
                    DnaVerify::firstOrCreate(
                        ['animal_id' => $existingAnimal->id, 'order_id' => $order->id],
                        ['verify_code' => 'EQUTR']
                    );

                    // Update or create the AnimalToParent relationship
                    AnimalToParent::updateOrCreate(
                        [
                            'animal_id' => $existingAnimal->id,
                            'register_pai' => $pai->number_definitive,
                            'register_mae' => $mae->number_definitive,
                        ],
                        [
                            'animal_name' => $animal->nome,
                            'especies' => 'EQUINA',
                            'animal_register' => null,
                        ]
                    );
                }
            }

            // Log the action
            Log::create([
                'user' => 'Sistema API',
                'action' => 'Criou pedido de exame',
                'order_id' => $order->id ?? 'deu erro',
                'animal' => $animal->nome ?? 'deu erro',
            ]);

            return redirect()->back()->with('success', 'Pedido de exame criado com sucesso');
        } catch (\Exception $e) {
            // Log the error
            \Log::info($e->getMessage());
            return redirect()->back()->with('error', 'Erro ao criar pedido de exame');
        }
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
        // Buscar o último animal criado com esta sigla, ordenado pela data de criação
        $lastAnimal = Animal::latest('created_at')
            ->first();

        if ($lastAnimal) {
            // Extrair o número do codlab do último animal
            $lastNumber = (int) substr($lastAnimal->codlab, 3);
            $nextNumber = $lastNumber + 1;
        } else {
            // Se não existir nenhum animal com esta sigla, começar do 200000
            $nextNumber = 200000;
        }

        // Verificar se o próximo número já existe (por segurança)
        while (Animal::where('codlab', $sigla . strval($nextNumber))->exists()) {
            $nextNumber++;
        }

        return $sigla . strval($nextNumber);
    }

    public function fetchDataFromApi($resource, $id, $tipo, $query = [])
    {
        $url = "http://laboratorios.abccmm.org.br/api/$resource/$id/$tipo" . '?' . http_build_query($query);
        $response =  Http::get($url);
        return json_decode($response->body());
    }
}
