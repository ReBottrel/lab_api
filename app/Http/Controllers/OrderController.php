<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Animal;
use App\Mail\ORPayment;
use App\Models\TabelaGeral;
use App\Models\OrderRequest;
use Illuminate\Http\Request;
use App\Models\OrderRequestPayment;
use Illuminate\Support\Facades\Mail;

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

    public function __construct()
    {
        $this->middleware('auth.padmin', ['except' => ['orderRequestGet', 'orderRequestPost']]);
    }

    public function orderRequestGet(Request $request, $id = null)
    {
        $user = user_token();
        $data = OrderRequest::with('user', 'orderRequestPayment')->where(function($query) use($user, $request){
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

            if(isset($request->generate_charge)) {
                $order_request = OrderRequest::find($request->id);
                OrderRequest::find($request->id)->update(['status' => 4]);
                $total_exams = 0;
                foreach(($request->or_payment_id ?? []) as $or_payment_id){
                    $or_payment = OrderRequestPayment::find($or_payment_id);
                    if($or_payment->payment_status == 0) $total_exams++;
                    OrderRequestPayment::find($or_payment_id)->update(['payment_status' => 1]);
                }

                if($total_exams > 0) Mail::to($order_request->owner->email ?? ($order_request->data_g['data_g']['email'] ?? 'zednetinformatica@gmail.com'))->send(new ORPayment($order_request, $total_exams, 'generate_charge'));
            }
            if(isset($request->resend_charge)) {
                $order_request = OrderRequest::find($request->id);
                $total_exams = 0;
                foreach(($request->or_payment_id ?? []) as $or_payment_id){
                    $or_payment = OrderRequestPayment::find($or_payment_id);
                    if($or_payment->payment_status == 1) $total_exams++;
                }

                if($total_exams > 0) Mail::to($order_request->owner->email ?? ($order_request->data_g['data_g']['email'] ?? 'zednetinformatica@gmail.com'))->send(new ORPayment($order_request, $total_exams, 'resend_charge'));
            }

            return response()->json(OrderRequest::find($request->id));
        }
    }
}
