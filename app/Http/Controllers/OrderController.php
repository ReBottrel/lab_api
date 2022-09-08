<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\User;
use App\Models\Animal;
use App\Mail\ORPayment;
use App\Models\TabelaGeral;
use App\Models\OrderRequest;
use Illuminate\Http\Request;
use App\Models\HistoryStatus;
use App\Models\OrderRequestPayment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    ###STATUS DO ORDER REQUEST###
    ###0-ACEITAR DO PEDIDO#######
    ###1-PEDIDO REJEITADO########
    ###2-PEDIDO CANCELADO########
    ###3-PENDETE DE ANALISE######
    ###4-AGUARDANDO PAGAMENTO####
    ###5-PEDIDO FINALIZADO#######
    #############################

    ###STATUS DO OR_PAYMENTS#####
    ###0-Aguardando/Gerar Pag.###
    ###1-Aguardando Pagamento####
    ###2-Pagamento Efetuado######
    ###3-Amostra Recebida e encaminhada para inspeção######
    ###4-                    ####
    ###5-Resultado Liberado######
    #############################

    public $status_orp = [
        'Aguardando/Gerar Pagamento',
        'Aguardando Pagamento',
        'Pagamento Efetuado',
        'Amostra Recebida e encaminhada para inspeção',
        '',
        'Resultado Liberado',
    ];

    public function __construct()
    {
        $this->middleware('auth.padmin', ['except' => ['orderRequestGet', 'orderRequestPost']]);
    }

    public function orderRequestGet(Request $request, $id = null)
    {
        $user = user_token();
        $data = OrderRequest::with('user', 'orderRequestPayment.historyStatus')->where(function($query) use($user, $request){
            if(isset($request->filter)){
                foreach($request->filter as $filter){
                    $query = $query->where($filter['column'], ($filter['condition'] ?? '='), $filter['value']);
                }
            }
            if($user->permission !== 10) $query = $query->where('user_id', $user->id);
            return $query;
        });

        if($id) {
            $data = $data->where('id',$id)->first();
            if(isset($data->data_g['animal_id'])) $data->animal = Animal::with('owner', 'resenhas')->find($data->data_g['animal_id']);
            $data->order_request_conta_azul = TabelaGeral::where('tabela', 'OrderRequest-ContaAzul')->where('coluna', 'order_request_id')->where('valor', $data->id)->first();
        }else{
            $data = $data->paginate($request->per_page ?? 20);
            $data->map(function($query){
                if(isset($query->data_g['animal_id'])) $query->animal = Animal::with('owner', 'resenhas')->find($query->data_g['animal_id']);
                return $query;
            });
        }

        return response()->json($data, 200);
    }

    public function orderRequestPost(Request $request)
    {
        $user = user_token();
        $order_request = collect($request->all())->put('origin', 'site')->put('user_id', $user->id);
        $order_request = OrderRequest::create($order_request->toArray());
        foreach(($request->data_g['exam_id'] ?? []) as $exam_id){
            $animal = Animal::find($request->data_g['animal_id'] ?? 0);

            $exam = Exam::find($exam_id);
            $create_orp['order_request_id'] = $order_request->id;
            $create_orp['owner_name'] = $animal->owner->owner_name;
            $create_orp['email'] = $animal->owner->email;
            $create_orp['location'] = $animal->animal_location;
            $create_orp['exam_id'] = $exam_id;
            $create_orp['category'] = $exam->category;
            $create_orp['animal'] = $exam->animal;
            $create_orp['title'] = $exam->title;
            $create_orp['short_description'] = $exam->short_description;
            $create_orp['value'] = $exam->value;
            $create_orp['requests'] = $exam->requests;
            $create_orp['extra_value'] = $exam->extra_value;
            $create_orp['extra_requests'] = $request->extra_requests ?? 0;
            OrderRequestPayment::create($create_orp);
        }

        return response()->json(OrderRequest::with('user', 'orderRequestPayment')->find($order_request->id));
    }

    public function labOrderPut(Request $request)
    {
        if(isset($request->id)){
            if(isset($request->update_status)) OrderRequest::find($request->id)->update(['status' => $request->update_status]);

            // Gerando pagamento
            if(isset($request->generate_charge)) {
                $order_request = OrderRequest::find($request->id);
                OrderRequest::find($request->id)->update(['status' => 4]);
                $total_exams = 0;
                foreach(($request->or_payment_id ?? []) as $or_payment_id){
                    $or_payment = OrderRequestPayment::find($or_payment_id);
                    if($or_payment->payment_status == 0) $total_exams++;
                    $status_alterar = $or_payment->category == 'dna' ? 1 : 3;
                    OrderRequestPayment::find($or_payment_id)->update(['payment_status' => $status_alterar]);
                    HistoryStatus::create([
                        'reference_type' => 'OrderRequestPayment',
                        'reference_id' => $or_payment_id,
                        'type' => 'STATUS',
                        'reason' => $status_alterar.'-'.$this->status_orp[$status_alterar],
                        'description' => 'Alterado o status',
                    ]);
                    HistoryStatus::create([
                        'reference_type' => 'OrderRequestPayment',
                        'reference_id' => $or_payment_id,
                        'type' => 'PAGAMENTO',
                        'reason' => 'Link de pagamento enviado',
                        'description' => 'Link de pagamento enviado',
                    ]);
                }

                if($total_exams > 0){
                    $userClient = $this->userClient($order_request, ($request->or_payment_id ?? []));
                    Mail::to($userClient[1])->send(new ORPayment($order_request, $userClient, $total_exams, 'generate_charge'));
                }
            }
            // Reenviado pagamento
            if(isset($request->resend_charge)) {
                $order_request = OrderRequest::find($request->id);
                $total_exams = 0;
                foreach(($request->or_payment_id ?? []) as $or_payment_id){
                    $or_payment = OrderRequestPayment::find($or_payment_id);
                    if($or_payment->payment_status == 1) $total_exams++;
                    HistoryStatus::create([
                        'reference_type' => 'OrderRequestPayment',
                        'reference_id' => $or_payment_id,
                        'type' => 'PAGAMENTO',
                        'reason' => 'Reenvio de link de pagamento',
                        'description' => 'Reenvio de link de pagamento',
                    ]);
                }

                if($total_exams > 0){
                    $userClient = $this->userClient($order_request, ($request->or_payment_id ?? []));
                    Mail::to($userClient[1])->send(new ORPayment($order_request, $userClient, $total_exams, 'resend_charge'));
                }
            }
            // Atualização de status manual
            if(isset($request->update_status_ORP)){
                foreach(($request->or_payment_id ?? []) as $or_payment_id){
                    OrderRequestPayment::find($or_payment_id)->update(['payment_status' => $request->status]);
                    HistoryStatus::create([
                        'reference_type' => 'OrderRequestPayment',
                        'reference_id' => $or_payment_id,
                        'type' => 'STATUS',
                        'reason' => $request->status.'-'.$this->status_orp[$request->status],
                        'description' => $request->description ?? '',
                    ]);
                }
            }

            return response()->json(OrderRequest::with('user', 'orderRequestPayment.historyStatus')->find($request->id));
        }
    }

    // Verificação do usuario
    public function userClient($order_request, $or_payment_id)
    {
        $userEmail = $order_request->orderRequestPayment->first()->email;
        $userName = $order_request->orderRequestPayment->first()->owner_name;
        $user = User::where('email', $userEmail)->get();
        if($user->count() > 0){
            $token_generate = collect([
                'file' => (string)\Str::uuid(),
                'token_unique' => Crypt::encryptString(base64_encode($userEmail.':')),
                'order_request_id' => $order_request->id,
                'or_payment_id' => $or_payment_id
            ]);
            $encrypt_token = Crypt::encryptString($token_generate->toJson());
            Storage::disk('local')->put('user_tokens/'.$token_generate['file'].'.txt', $encrypt_token);
        }else{
            $user = User::create([
                'name' => $userName,
                'email' => $userEmail,
                'password' => Hash::make(\Str::random(8)),
            ]);
            $token_generate = collect([
                'file' => (string)\Str::uuid(),
                'token_unique' => Crypt::encryptString(base64_encode($userEmail.':')),
                'order_request_id' => $order_request->id,
                'or_payment_id' => $or_payment_id
            ]);
            $encrypt_token = Crypt::encryptString($token_generate->toJson());
            Storage::disk('local')->put('user_tokens/'.$token_generate['file'].'.txt', $encrypt_token);
        }
        ############################################################################################################
                        ######################AINDA PRECISA ACERTAR A URL##########################
        return ['https://lab-ecommerce.simetriastudio.dev.br/url-teste?token='.$token_generate['file'], $userEmail];
                        ######################AINDA PRECISA ACERTAR A URL##########################
        ############################################################################################################
    }
}
