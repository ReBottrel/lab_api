<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\UserInfo;
use App\Models\OrderRequest;
use Illuminate\Http\Request;
use App\Models\OrderRequestPayment;
use App\Models\PaymentReturn;
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
        $response = Http::post(env('IOPAY_URL') . 'auth/login', $data)->object();
        $this->bearer_token = "Bearer $response->access_token";
    }

    public function listPayment(Request $request, $id = null)
    {
        $data['page'] = $request->page ?? 1;
        if (!isset($id)) $response = Http::withHeaders(['Authorization' => $this->bearer_token])->get(env('IOPAY_URL') . 'v1/transaction/list', $data)->object();
        if (isset($id)) $response = Http::withHeaders(['Authorization' => $this->bearer_token])->get(env('IOPAY_URL') . 'v1/transaction/get/' . $id)->object();
        return response()->json($response);
    }

    public function payment(Request $request)
    {

        // dd($request->all());



        $order = OrderRequest::with('orderRequestPayment', 'tecnico', 'owner')->find($request->order_id);
        $data = [
            "amount" => 10000,
            "currency" => "BRL",
            "description" => "Pagamento de Exames Numero do pedido: $order->id",
            "capture" => 1,
            "statement_descriptor" => "Lab Loci",
            "installment_plan" => [
                "number_installments" => ($request->installments ?? 1),
            ],
            "io_seller_id" => env('IOPAY_IO_SELLER_ID'),
            "expiration_date" => $request->payment_type === 'boleto' ? date('Y-m-d', strtotime('+170 day')) : null,
            "payment_limit_date" => $request->payment_type === 'boleto' ? date('Y-m-d', strtotime('+170 day')) : null,
            "payment_type" => $request->payment_type,
        ];



        $total_value = 0;
        foreach ($order->orderRequestPayment as  $or_payment) {
            $or_payment = OrderRequestPayment::find($or_payment->id);
            $total_value += $orp_value = ($or_payment->value + ($or_payment->extra_request * ((float)$or_payment->extra_value)));
            $data['product'][] = [
                "name" => $or_payment->title,
                "code" => (string)$or_payment->exam_id,
                "amount" => (int)number_format($orp_value, 2, '', ''),
                "quantity" => 1
            ];
        }

        // $data['amount'] = (int)number_format($total_value, 2, '', '');
        $data['amount'] = (int)number_format($order->total, 2, '', '');
        $data['reference_id'] = collect($request->or_payment_id ?? [])->join('-');

        if ($request->payment_type == 'credit') {
            $data['token'] = $this->cardToken($request->card);
        }
        $id_customer = $this->buyer($request->info_add ?? null);

        $response = Http::withHeaders(['Authorization' => $this->bearer_token])->post(env('IOPAY_URL') . 'v1/transaction/new/' . $id_customer, $data)->object();
        // \Log::info(collect($response)->toArray());
        \Log::channel('iopay_response_payment')->info(collect($response)->toArray());
        if (isset($response->error)) return response()->json($response, 402);
        if ($request->payment_type == 'credit') {
            foreach ($order->orderRequestPayment as $or_payment) {
                if ($or_payment->paynow == 1) {
                    $or_payment->update([
                        'payment_id' => $response->success->id ?? null,
                        'payment_status' => 1 ?? null,
                        // 'value' => $response->success->amount ?? null,
                    ]);
                    $animal = Animal::where('id', $or_payment->animal_id)->first();
                    $animal->update([
                        'status' => 9
                    ]);
                    $days = '';

                    if ($or_payment->days == 0) {
                        $days = '20 Dias';
                    } elseif ($or_payment->days == 4) {
                        $days = '24 Horas';

                        $responseOwner = Http::post('https://api.z-api.io/instances/3B30881EC3E99084D3D3B6927F6ADC67/token/66E633717A0DCDD3D4A1BC19/send-text', [
                            "phone" => "5531989911569",
                            "message" => "Esse exame foi pago com sucesso, e o prazo e de 24 horas para o animal: $animal->animal_name"
                        ]);
                    } elseif ($or_payment->days == 3) {
                        $days = '2 Dias';
                        $responseOwner = Http::post('https://api.z-api.io/instances/3B30881EC3E99084D3D3B6927F6ADC67/token/66E633717A0DCDD3D4A1BC19/send-text', [
                            "phone" => "5531989911569",
                            "message" => "Esse exame foi pago com sucesso, e o prazo e de 2 dias para o animal: $animal->animal_name"
                        ]);
                    } elseif ($or_payment->days == 2) {
                        $days = '5 Dias';
                        $responseOwner = Http::post('https://api.z-api.io/instances/3B30881EC3E99084D3D3B6927F6ADC67/token/66E633717A0DCDD3D4A1BC19/send-text', [
                            "phone" => "5531989911569",
                            "message" => "Esse exame foi pago com sucesso, e o prazo e de 5 dias para o animal: $animal->animal_name"
                        ]);
                    } elseif ($or_payment->days == 1) {
                        $days = '10 Dias';
                        $responseOwner = Http::post('https://api.z-api.io/instances/3B30881EC3E99084D3D3B6927F6ADC67/token/66E633717A0DCDD3D4A1BC19/send-text', [
                            "phone" => "5531989911569",
                            "message" => "Esse exame foi pago com sucesso, e o prazo e de 10 dias para o animal: $animal->animal_name"
                        ]);
                    }
                    $telefone = str_replace(['(', ')', '-', ' '], ['', '', '', ''],  $order->tecnico->cell);
                    $response = Http::post('https://api.z-api.io/instances/3B30881EC3E99084D3D3B6927F6ADC67/token/66E633717A0DCDD3D4A1BC19/send-text', [
                        "phone" => "55$telefone",
                        "message" => "Prezado Técnico,
                        Confirmamos o pagamento do exame de DNA do(s) animal(ais) $animal->animal_name e informamos que o exame já se encontra em execução.
                        "
                    ]);
                    $telefoneOwner = str_replace(['(', ')', '-', ' '], ['', '', '', ''],  $order->owner->cell);
                    $responseOwner = Http::post('https://api.z-api.io/instances/3B30881EC3E99084D3D3B6927F6ADC67/token/66E633717A0DCDD3D4A1BC19/send-text', [
                        "phone" => "55$telefoneOwner",
                        "message" => "Prezado Criador,
                        Confirmamos o pagamento do exame de DNA do(s) animal(ais) $animal->animal_name e informamos que o exame já se encontra em execução.
                        "
                    ]);
                }
            }
        }

        if ($request->payment_type == 'pix') {
            foreach ($order->orderRequestPayment as $or_payment) {
                if ($or_payment->paynow == 1) {
                    $or_payment->update([
                        'payment_id' => $response->success->id ?? null,
                        // 'value' => $response->success->amount ?? null,
                    ]);
                }
            }

            $datapix = PaymentReturn::create([
                'order_request_id' => $request->order_id,
                'user_id' => auth()->user()->id,
                'payment_id' => $response->success->id ?? null,
                'payment_type' => $request->payment_type,
                'payment_status' => 1 ?? null,
                'pixqrcode' => $response->success->pix_qrcode_url ?? null,
                'pixcode' => $response->success->payment_method->qr_code->emv ?? null,

            ]);
        }

        if ($request->payment_type == 'boleto') {
            foreach ($order->orderRequestPayment as $or_payment) {
                if ($or_payment->paynow == 1) {
                    $or_payment->update([
                        'payment_id' => $response->success->id ?? null,
                        // 'value' => $response->success->amount ?? null,
                    ]);
                }
            }
            $datapix = PaymentReturn::create([
                'order_request_id' => $request->order_id,
                'user_id' => auth()->user()->id,
                'payment_id' => $response->success->id ?? null,
                'payment_type' => $request->payment_type,
                'payment_status' => 1 ?? null,
                'pixcode' => $response->success->payment_method->barcode ?? null,
                'boleto' => $response->success->payment_method->url ?? null,
            ]);
        }


        return response()->json($response);
    }

    public function success($id)
    {
        $pixreponse = PaymentReturn::where('order_request_id', $id)->orderBy('created_at', 'desc')->firstOrFail();
        // $pixreponse = OrderRequest::with('payments')->find($id);
        return view('user.success_order', get_defined_vars());
    }
    public function reverse(Request $request)
    {
        $total_value = 0;
        $payment_id = '';
        foreach (($request->or_payment_id ?? []) as $or_payment_id) {
            $or_payment = OrderRequestPayment::find($or_payment_id);
            $total_value += $orp_value = ($or_payment->value + ($or_payment->extra_request * ((float)$or_payment->extra_value)));
            $payment_id = $or_payment->payment_id;
        }

        $data['amount'] = (int)number_format($total_value, 2, '', '');

        $response = Http::withHeaders(['Authorization' => $this->bearer_token])->post(env('IOPAY_URL') . 'v1/transaction/void/' . $payment_id, $data)->object();
        if (isset($response->error)) return response()->json($response, 402);
        OrderRequestPayment::whereIn('id', $or_payment_id)->update(['payment_status' => isset($response->success->id) ? 3 : (OrderRequestPayment::whereIn('id', $or_payment_id)->first()->status)]);
    }

    // Callback de notify
    public function callbackNotify(Request $request)
    {
        $response = Http::withHeaders(['Authorization' => $this->bearer_token])->get(env('IOPAY_URL') . 'v1/transaction/get/' . $request->id)->object();
        // \Log::info($request->all());
        // \Log::info(collect($response)->toArray());
        \Log::channel('iopay_notify_payment')->info(collect($response)->toArray());

        if ($response->success->status == 'succeeded') {

            $orderRequest = OrderRequestPayment::where('payment_id', $response->success->id)->get();

            \Log::channel('iopay_notify_payment')->info(['order request', $orderRequest]);
            foreach ($orderRequest as $item) {
                if ($item->paynow == 1) {
                    $order = OrderRequest::where('id', $item->order_request_id)->firstOrFail();

                    if ($order->requests == null) {
                        $animal = Animal::where('id', $item->animal_id)->firstOrFail();
                        $animal->update([
                            'status' => 9
                        ]);
                        $item->update([
                            'payment_status' => 1
                        ]);
                        $days = '';

                        if ($item->days == 0) {
                            $days = '20 Dias';
                        } elseif ($item->days == 4) {
                            $days = '24 Horas';
                            $responseOwner = Http::post('https://api.z-api.io/instances/3B30881EC3E99084D3D3B6927F6ADC67/token/66E633717A0DCDD3D4A1BC19/send-text', [
                                "phone" => "5531989911569",
                                "message" => "Esse exame foi pago com sucesso, e o prazo e de 24 horas para o animal: $animal->animal_name"
                            ]);
                        } elseif ($item->days == 3) {
                            $days = '2 Dias';
                            $responseOwner = Http::post('https://api.z-api.io/instances/3B30881EC3E99084D3D3B6927F6ADC67/token/66E633717A0DCDD3D4A1BC19/send-text', [
                                "phone" => "5531989911569",
                                "message" => "Esse exame foi pago com sucesso, e o prazo e de 2 dias para o animal: $animal->animal_name"
                            ]);
                        } elseif ($item->days == 2) {
                            $days = '5 Dias';
                            $responseOwner = Http::post('https://api.z-api.io/instances/3B30881EC3E99084D3D3B6927F6ADC67/token/66E633717A0DCDD3D4A1BC19/send-text', [
                                "phone" => "5531989911569",
                                "message" => "Esse exame foi pago com sucesso, e o prazo e de 5 dias para o animal: $animal->animal_name"
                            ]);
                        } elseif ($item->days == 1) {
                            $days = '10 Dias';
                            $responseOwner = Http::post('https://api.z-api.io/instances/3B30881EC3E99084D3D3B6927F6ADC67/token/66E633717A0DCDD3D4A1BC19/send-text', [
                                "phone" => "5531989911569",
                                "message" => "Esse exame foi pago com sucesso, e o prazo e de 10 dias para o animal: $animal->animal_name"
                            ]);
                        }
                        $telefone = str_replace(['(', ')', '-', ' '], ['', '', '', ''],  $order->tecnico->cell);
                        $response = Http::post('https://api.z-api.io/instances/3B30881EC3E99084D3D3B6927F6ADC67/token/66E633717A0DCDD3D4A1BC19/send-text', [
                            "phone" => "55$telefone",
                            "message" => "Prezado Técnico,
                            Confirmamos o pagamento do exame de DNA do(s) animal(ais) $animal->animal_name e informamos que o exame já se encontra em execução.
                            "
                        ]);
                        $telefoneOwner = str_replace(['(', ')', '-', ' '], ['', '', '', ''],  $order->owner->cell);
                        $responseOwner = Http::post('https://api.z-api.io/instances/3B30881EC3E99084D3D3B6927F6ADC67/token/66E633717A0DCDD3D4A1BC19/send-text', [
                            "phone" => "55$telefoneOwner",
                            "message" => "Prezado Criador,
                            Confirmamos o pagamento do exame de DNA do(s) animal(ais) $animal->animal_name e informamos que o exame já se encontra em execução.
                            "
                        ]);

                        $order->update([
                            'requests' => 1
                        ]);
                    }
                }
            }
        }
    }

    ##########FUNÇÕES INTERNAS##########
    public function cardToken($data)
    {
        $response = Http::post(env('IOPAY_URL') . 'v1/card/authentication', $this->gateway_data_token)->object();
        $bearer_token = "Bearer $response->access_token";

        $response = Http::withHeaders(['Authorization' => $bearer_token])->post(env('IOPAY_URL') . 'v1/card/tokenize/token', $data)->object();
        return $response->id;
    }

    public function buyer($info_add)
    {
        $user = auth()->user();
        if (empty($user->info)) UserInfo::create(collect($info_add)->put('user_id', $user->id)->toArray());
        if (!empty($user->info)) UserInfo::where('user_id', $user->id)->update(collect($info_add)->toArray());
        // $user = user_token();
        // $name = explode(' ', $user->name);

        $user = UserInfo::where('user_id', auth()->user()->id)->first();
        $documents = str_replace(['.', '-', '/'], ['', '', ''],  $user->document);

        if (!$user->buyer_id) {
            $data = [
                "first_name" => auth()->user()->name,
                "last_name" => collect(auth()->user()->name)->forget(0)->join(' '),
                "email" => 'locilab@gmail.com',
                "taxpayer_id" => $documents,
                "customer_type" => strlen($documents) == 11 ? "person_natural" : "person_legal",
                "phone_number" => $user->phone,
                "gender" => "male",
                "address" =>  [
                    "line1" =>  $user->address,
                    "line2" => $user->number,
                    "line3" =>  null,
                    "neighborhood"  =>  $user->district,
                    "city"  =>  $user->city,
                    "state"  =>   'PR',
                    "postal_code" => $user->zip_code
                ]
            ];

            $response = Http::withHeaders(['Authorization' => $this->bearer_token])->post(env('IOPAY_URL') . 'v1/customer/new', $data)->object();
            \Log::info(json_encode($response));
            UserInfo::where('user_id', auth()->user()->id)->update(['buyer_id' => ($response->success->id ?? $response->id)]);
            return ($response->success->id ?? $response->id);
        }

        return $user->buyer_id;
    }
    ####################################
}
