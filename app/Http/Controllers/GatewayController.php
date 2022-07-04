<?php

namespace App\Http\Controllers;

use App\Models\UserInfo;
use Illuminate\Http\Request;
use App\Models\OrderRequestPayment;
use Illuminate\Support\Facades\Http;

class GatewayController extends Controller
{
    public $gateway_data_token;
    public $bearer_token;

    public function __construct()
    {
        $this->gateway_data_token = $data = [
            "email" => env('IOPAY_EMAIL'),
            "secret" => env('IOPAY_SECRET'),
            "io_seller_id" => env('IOPAY_IO_SELLER_ID')
        ];
        $response = Http::post(env('IOPAY_URL').'auth/login', $data)->object();
        $this->bearer_token = "Bearer $response->access_token";
    }

    public function listPayment(Request $request, $id = null)
    {
        $data['page'] = $request->page ?? 1;
        if(!isset($id)) $response = Http::withHeaders(['Authorization' => $this->bearer_token])->get(env('IOPAY_URL').'v1/transaction/list', $data)->object();
        if(isset($id)) $response = Http::withHeaders(['Authorization' => $this->bearer_token])->get(env('IOPAY_URL').'v1/transaction/get/'.$id)->object();
        return response()->json($response);
    }

    public function payment(Request $request)
    {
        // \Log::info($request->all());
        $data = [
            "amount" => 10000,
            "currency" => "BRL",
            "description" => "Pagamento de Exames",
            "capture" => 1,
            "statement_descriptor" => "Lab Loci",
            "installment_plan" => [
                "number_installments" => ($request->installments ?? 1),
            ],
            "io_seller_id" => env('IOPAY_IO_SELLER_ID'),
            "payment_type" => $request->payment_type,
        ];

        $total_value = 0;
        foreach(($request->or_payment_id ?? []) as $or_payment_id){
            $or_payment = OrderRequestPayment::find($or_payment_id);
            $total_value += $orp_value = ($or_payment->value+($or_payment->extra_request*((float)$or_payment->extra_value)));
            $data['product'][] = [
                "name" => $or_payment->title,
                "code" => (string)$or_payment->exam_id,
                "amount" => (int)number_format($orp_value, 2, '', ''),
                "quantity" => 1
            ];
        }

        $data['amount'] = (int)number_format($total_value, 2, '', '');
        $data['reference_id'] = collect($request->or_payment_id ?? [])->join('-');

        if($request->payment_type == 'credit'){
            $data['token'] = $this->cardToken($request->card);
        }
        $id_customer = $this->buyer($request->info_add ?? null);

        $response = Http::withHeaders(['Authorization' => $this->bearer_token])->post(env('IOPAY_URL').'v1/transaction/new/'.$id_customer, $data)->object();
        // \Log::info(collect($response)->toArray());
        \Log::channel('iopay_response_payment')->info(collect($response)->toArray());
        if(isset($response->error)) return response()->json($response, 402);
        OrderRequestPayment::whereIn('id', ($request->or_payment_id ?? []))->update(['payment_id' => $response->success->id ?? null]);
        return response()->json($response->success);
    }

    public function reverse(Request $request)
    {
        $total_value = 0;
        $payment_id = '';
        foreach(($request->or_payment_id ?? []) as $or_payment_id){
            $or_payment = OrderRequestPayment::find($or_payment_id);
            $total_value += $orp_value = ($or_payment->value+($or_payment->extra_request*((float)$or_payment->extra_value)));
            $payment_id = $or_payment->payment_id;
        }

        $data['amount'] = (int)number_format($total_value, 2, '', '');

        $response = Http::withHeaders(['Authorization' => $this->bearer_token])->post(env('IOPAY_URL').'v1/transaction/void/'.$payment_id, $data)->object();
        if(isset($response->error)) return response()->json($response, 402);
        OrderRequestPayment::whereIn('id', $or_payment_id)->update(['payment_status' => isset($response->success->id) ? 3 : 2]);
    }

    // Callback de notify
    public function callbackNotify(Request $request)
    {
        $response = Http::withHeaders(['Authorization' => $this->bearer_token])->get(env('IOPAY_URL').'v1/transaction/get/'.$request->id)->object();
        // \Log::info($request->all());
        // \Log::info(collect($response)->toArray());
        \Log::channel('iopay_notify_payment')->info(collect($response)->toArray());

        if($response->success->status == 'succeeded'){
            if(OrderRequestPayment::where('payment_id', $response->success->id)->get()->count() > 0){
                OrderRequestPayment::where('payment_id', $response->success->id)->update(['status' => 2]);
            }
        }
    }

    ##########FUNÇÕES INTERNAS##########
    public function cardToken($data)
    {
        $response = Http::post(env('IOPAY_URL').'v1/card/authentication', $this->gateway_data_token)->object();
        $bearer_token = "Bearer $response->access_token";

        $response = Http::withHeaders(['Authorization' => $bearer_token])->post(env('IOPAY_URL').'v1/card/tokenize/token', $data)->object();
        return $response->id;
    }

    public function buyer($info_add)
    {
        $user = user_token();
        if(empty($user->info)) UserInfo::create(collect($info_add)->put('user_id', $user->id)->toArray());
        if(!empty($user->info)) UserInfo::where('user_id', $user->id)->update(collect($info_add)->toArray());
        $user = user_token();
        $name = explode(' ', $user->name);

        if(!$user->info->buyer_id){
            $data = [
                "first_name" => $name[0],
                "last_name" => collect($name)->forget(0)->join(' '),
                "email" => $user->email,
                "taxpayer_id" => $user->info->document,
                "phone_number" => $user->info->phone,
                "gender" => "male",
                "address" =>  [
                    "line1" =>  $user->info->address,
                    "line2" => $user->info->number,
                    "line3" =>  $user->info->complement,
                    "neighborhood"  =>  $user->info->district,
                    "city"  =>  $user->info->city,
                    "state"  =>   $user->info->state,
                    "postal_code" => $user->info->zip_code
                ]
            ];

            $response = Http::withHeaders(['Authorization' => $this->bearer_token])->post(env('IOPAY_URL').'v1/customer/new', $data)->object();

            UserInfo::where('user_id', $user->id)->update(['buyer_id' => ($response->success->id ?? $response->id)]);
            return ($response->success->id ?? $response->id);
        }

        return $user->info->buyer_id;
    }
    ####################################
}
