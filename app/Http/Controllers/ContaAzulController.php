<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TabelaGeral;
use App\Models\OrderRequest;
use Illuminate\Http\Request;
use App\Models\OrderRequestPayment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ContaAzulController extends Controller
{
    public function callbackContaAzul(Request $request)
    {
        $data = [
            "grant_type" => "authorization_code",
            "redirect_uri" => env('CA_REDIRECT_URI'),
            "code" => $request->code
        ];
        $response = Http::withHeaders(['Authorization' => 'Basic '.base64_encode(env('CA_CLIENT_ID').':'.env('CA_CLIENT_SECRET'))])->post('https://api.contaazul.com/oauth2/token', $data)->object();
        $response = collect($response)->put('expires_at', date('Y-m-d H:i:s', strtotime('+ '.$response->expires_in.' seconds')));
        if(isset($request->code)) Storage::disk('local')->put('conta_azul_T.json', $response->toJson());
    }

    public function getUrlCode(Request $request)
    {
        $redirect_uri = env('CA_REDIRECT_URI');
        $client_id = env('CA_CLIENT_ID');
        $scope = 'Sales,Customer,Product,Service,Contract';
        $state = \Str::random(20);
        $return_url = "https://api.contaazul.com/auth/authorize?redirect_uri=$redirect_uri&client_id=$client_id&scope=$scope&state=$state";
        return response()->json($return_url);
    }

    public function getToken()
    {
        $ca_token = json_decode(Storage::disk('local')->get('conta_azul_T.json'));
        // $ca_token = collect($ca_token)->toArray();
        if(date('Y-m-d H:i:s', strtotime($ca_token->expires_at)) < date('Y-m-d H:i:s', strtotime('-1 seconds'))){
            $data = [
                "grant_type" => "refresh_token",
                "redirect_uri" => env('CA_REDIRECT_URI'),
                "refresh_token" => $ca_token->refresh_token
            ];
            $response = Http::withHeaders(['Authorization' => 'Basic '.base64_encode(env('CA_CLIENT_ID').':'.env('CA_CLIENT_SECRET'))])->post('https://api.contaazul.com/oauth2/token', $data)->object();
            $response = collect($response)->put('expires_at', date('Y-m-d H:i:s', strtotime('+ '.$response->expires_in.' seconds')));
            Storage::disk('local')->put('conta_azul_T.json', $response->toJson());
            $ca_token = json_decode(Storage::disk('local')->get('conta_azul_T.json'));
        }

        return $ca_token->access_token;
    }

    ############ENVIO DE DADOS############

    public function getCategories(Request $request)
    {
        $token = $this->getToken();
        $response = Http::withHeaders(['Authorization' => 'Bearer '.$token])->get('https://api.contaazul.com/api/v1/product-categories')->object();
        return response()->json($response);
    }

    public function getSellers(Request $request)
    {
        $token = $this->getToken();
        $response = Http::withHeaders(['Authorization' => 'Bearer '.$token])->get('https://api.contaazul.com/api/v1/sales/sellers')->object();
        return response()->json($response);
    }

    public function sendSales(Request $request)
    {
        $order_request = OrderRequest::with('orderRequestPayment')->find($request->or_id);
        $token = $this->getToken();
        $customer_id = $this->getCustomer($order_request);

        $or_payment = $order_request->orderRequestPayment;
        $services = collect();
        $total_value = collect();
        $or_payment->map(function($query) use($services, $total_value){
            $service_id = $this->getService($query);
            $services->add([
                "description" => $query->title,
                "quantity" => 1,
                "service_id" => $service_id,
                "value" => ($query->value+($query->extra_requests*$query->extra_value))
            ]);
            $total_value->add($query->value+($query->extra_requests*$query->extra_value));
        });

        $data = collect([
            'emission' => str_replace(' ', 'T', date('Y-m-d H:m:s.B')).'Z',
            'status' => 'COMMITTED',
            'customer_id' => $customer_id,
            'seller_id' => $request->seller_id ?? '',
            'payment' => [
                'type' => 'CASH',
                'method' => 'CASH',
                'installments' => [
                    [
                        'number' => 1,
                        'value' => $total_value->sum(),
                        'due_date' => str_replace(' ', 'T', date('Y-m-d H:i:s.B', strtotime(date('Y-m').'-15'))).'Z'
                    ]
                ]
            ]
        ]);

        $data = $data->merge(['services' => $services->toArray()]);

        $response = Http::withHeaders(['Authorization' => 'Bearer '.$token])->post('https://api.contaazul.com/api/v1/sales', $data->toArray())->object();

        if(isset($response->id)) TabelaGeral::create([
            'tabela' => 'OrderRequest-ContaAzul',
            'coluna' => 'order_request_id',
            'valor' => $order_request->id,
            'array_text' => ['id' => $response->id],
        ]);

        return response()->json(collect($response)->toArray());
    }

    public function getCustomer($order_request)
    {
        $token = $this->getToken();
        $or_payment = $order_request->orderRequestPayment->first();
        $user = User::with('info')->where('email', $or_payment->email)->first();
        $response = Http::withHeaders(['Authorization' => 'Bearer '.$token])->get('https://api.contaazul.com/api/v1/customers?search='.($user->email ?? $or_payment->email))->object();
        if(collect($response)->count() == 0){
            $data = [
                "name" => $user->name ?? $or_payment->owner_name,
                "email" => $user->email ?? $or_payment->email,
                "mobile_phone" => str_replace(['[',']'],'', ($user->info->phone ?? '')),
                "person_type" => "NATURAL",
                "document" => str_replace(['-','.'], '', ($user->info->document ?? '')),
                "address" => [
                    "zip_code" => $user->info->zip_code ?? '',
                    "street" => $user->info->address ?? '',
                    "number" => $user->info->number ?? '',
                    "complement" => $user->info->complement ?? '',
                    "neighborhood" => $user->info->district ?? ''
                ]
            ];
            $response = Http::withHeaders(['Authorization' => 'Bearer '.$token])->post('https://api.contaazul.com/api/v1/customers', $data)->object();
        }
        return collect($response)->first()->id;
    }

    public function getService($or_payment)
    {
        $token = $this->getToken();
        $response = Http::withHeaders(['Authorization' => 'Bearer '.$token])->get('https://api.contaazul.com/api/v1/services?name='.$or_payment->title)->object();
        if(collect($response)->count() == 0){
            $data = [
                "name" => $or_payment->title,
                "type" => "PROVIDED",
                "value" => (float)$or_payment->value,
                "cost" => (float)$or_payment->value,
                "code" => (string)$or_payment->id
            ];
            $response = Http::withHeaders(['Authorization' => 'Bearer '.$token])->post('https://api.contaazul.com/api/v1/services',$data)->object();
        }
        return collect($response)->first()->id;
    }
}
