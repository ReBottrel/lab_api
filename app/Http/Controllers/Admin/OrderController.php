<?php

namespace App\Http\Controllers\Admin;

use App\Models\Exam;
use App\Models\User;
use App\Models\Owner;
use App\Models\Animal;
use App\Models\Tecnico;
use App\Models\OrderRequest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use App\Models\OrderRequestPayment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Rap2hpoutre\FastExcel\FastExcel;

class OrderController extends Controller
{

    public function order()
    {
        $orders = OrderRequest::where('status', '!=', 0)->get();
        return view('admin.order', get_defined_vars());
    }

    public function recivedOrder(Request $request, $id)
    {

        $order = OrderRequest::find($id);

        $order->update([
            'status' => $request->value,
        ]);

        return response()->json($order);
    }

    public function animal(Request $request)
    {
        $order = OrderRequest::find($request->id);
        foreach ($order->data_g['data_table'] as $data) {

            Animal::create([
                'user_id' => $request->owner,
                'order_id' => $order->id,
                'animal_name' => $data['produto'],
                'register_number_brand' => $data['id'],
                'sex' => $data['sexo'],
                'birth_date' => $data['nascimento'],
                'registro_pai' => $data['registro_pai'],
                'pai' => $data['pai'],
                'registro_mae' => $data['registro_mae'],
                'mae' => $data['mae'],
            ]);
        }

        $user = Owner::find($request->owner);

        $order->update([
            'user_id' => $user->user_id
        ]);

        return redirect()->route('order.detail', $order->id);
    }

    public function amostra(Request $request, $id)
    {
        $animal = Animal::find($id);
        $animal->update([
            'status' => $request->value,
        ]);


        if ($request->order) {
            $order = OrderRequest::with('tecnico')->find($request->order);
            $order->update([
                'status' => 4,
            ]);
           
            $telefone = str_replace(['(', ')', '-', ' '], ['', '', '', ''],  $order->tecnico->cell);
      
            if ($request->value == 7) {
                $response = Http::post('https://api.z-api.io/instances/3B30881EC3E99084D3D3B6927F6ADC67/token/66E633717A0DCDD3D4A1BC19/send-text', [
                    "phone" => "55$telefone",
                    "message" => "Prezado Criador, Recebemos a amostra do animal $animal->animal_name que foi inspecionada e APROVADA para realização do exame de DNA. Em breve você receberá o link para pagamento do(s) exame (s) recebido e aprovado. Agradecemos a escolha pelo Laboratório Loci e nos colocamos a disposição para qualquer dúvida ou necessidade! NO LABORATÓRIO LOCI VOCÊ PODE CONFIAR!
                    "
                ]);
            }
            if ($request->value == 6) {
                $response = Http::post('https://api.z-api.io/instances/3B30881EC3E99084D3D3B6927F6ADC67/token/66E633717A0DCDD3D4A1BC19/send-text', [
                    "phone" => "55$telefone",
                    "message" => "Prezado Criador, Recebemos a amostra do animal $animal->animal_name que foi REPROVADA para a execução do exame de DNA. Solicitamos recoletar uma nova amostra, abrir um novo chamado junto a ABCCMM informando que se trata de uma RECOLETA solicitada pelo laboratório e nos encaminhar novamente para execução. Pedimos que sinalize como uma nova amostra para tratarmos com prioridade. Agradecemos a escolha pelo Laboratório Loci e nos colocamos a disposição para qualquer dúvida ou necessidade! NO LABORATÓRIO LOCI VOCÊ PODE CONFIAR!
                    "
                ]);
            }
        }



        return response()->json($animal);
    }

    public function cpfTechnical(Request $request, $id)
    {
        $order = OrderRequest::find($id);
        $order->update([
            'cpf_technical' => $request->cpf,
        ]);

        return response()->json($order);
    }

    public function chip(Request $request, $id)
    {
        $animal = Animal::find($id);
        $animal->update([
            'chip_number' => $request->chip,

        ]);
        return response()->json($animal);
    }

    public function orderDetail($id)
    {
        $order = OrderRequest::find($id);
        return view('admin.order-detail', get_defined_vars());
    }

    public function owner($id)
    {
        $order = OrderRequest::find($id);
        $owners = Owner::get();
        return view('admin.owner', get_defined_vars());
    }
    public function technical($id)
    {
        $order = OrderRequest::find($id);
        $tecnicos = Tecnico::get();
        return view('admin.tecnico', get_defined_vars());
    }

    public function technicalStore(Request $request)
    {
        $order = OrderRequest::find($request->id);
        $order->update([
            'id_tecnico' => $request->tecnico,
        ]);

        return redirect()->route('orders.all')->with('success', 'Técnico adicionado com sucesso!');
    }

    public function orderRequestPost(Request $request)
    {
        // $user = user_token();
        // $order_request = collect($request->all())->put('origin', 'site')->put('user_id', $user->id);
        $order_request = OrderRequest::with('user', 'tecnico')->find($request->order);

        $owner = Owner::where('user_id', $order_request->user_id)->first();


        $animals = Animal::where('order_id', $request->order)->where('status', 7)->get();
        foreach ($animals as $animal) {
            $exam = Exam::find(4);
            $orderPay = OrderRequestPayment::create([
                'order_request_id' => $request->order,
                'owner_name' => $order_request->user->name,
                'email' => $order_request->user->email,
                'location' => $owner->propriety,
                'exam_id' => $exam->id,
                'category' => $exam->category,
                'animal' => $animal->animal_name,
                'title' => $exam->title,
                'short_description' => $exam->short_description,
                'value' => $exam->value,
                'requests' => $exam->requests,
                'extra_value' => $exam->extra_value,
                'extra_requests' => $request->extra_requests ?? 0,
                'animal_id' => $animal->register_number_brand,
            ]);
        }

        $order_request->update([
            'status' => 2,
        ]);
        $ordernew = OrderRequest::with('user', 'tecnico')->find($request->id);
        $data = [];
        $email = $owner->email;
    
        $senha = str_replace(['.', '-', '/'], ['', '', ''], $owner->document);
        $telefone = str_replace(['(', ')', '-', ' '], ['', '', '', ''],  $order_request->tecnico->cell);
        $url = route('user.dashboard');
        $response = Http::post('https://api.z-api.io/instances/3B30881EC3E99084D3D3B6927F6ADC67/token/66E633717A0DCDD3D4A1BC19/send-text', [
            "phone" => "55$telefone",
            "message" => "Prezado, Criador! Segue abaixo o Link de acesso para clicar, efetuar o pagamento e liberar o(s) exame(s) para execução. Ao acessar, digite seu E-MAIL no campo USUÁRIO e o seu CPF em senha. Selecione os animais para pagamento e defina o prazo de liberação do resultado (temos opções de 20 dias úteis a 24 horas)*. *os valores variam conforme o prazo de liberação Assim que confirmarmos o pagamento no nosso sistema o exame estará liberado para execução internamente. Agradecemos a escolha pelo Laboratório Loci e nos colocamos a disposição para qualquer dúvida ou necessidade! NO LABORATÓRIO LOCI VOCÊ PODE CONFIAR!
            ",
            "linkUrl" => $url,
            "title" => "Locilab",
            "linkDescription" => "LociLab e a melhor plataforma de exames de DNA do Brasil",
        ]);

        Mail::to($email)->send(new \App\Mail\NewOrder($email, $senha));
        return view('admin.success-page', get_defined_vars());
    }


    public function orderRequestDetail($id)
    {
        $order = OrderRequest::with('user', 'orderRequestPayment')->find($id);
        $userInfo = User::with('info')->find($order->user_id);
        return view('admin.order-request-detail', get_defined_vars());
    }

    public function exportExcel(Request $request)
    {
        $order = OrderRequest::with('user', 'orderRequestPayment')->find($request->id);
        // $owner = Owner::find($order->user_id);

        $newdata = [];
        foreach ($order->orderRequestPayment as $data) {
            $animal = Animal::where('register_number_brand', $data->animal_id)->first();

            $newdata[]  = [
                'COD LAB' => '',
                'Nome' => $data->animal,
                'RG' => '',
                'id' => $animal->register_number_brand,
                'Sexo' => $animal->sex,
                'Exame' => 'EQUTR',
                'Data Nascimento' => $animal->birth_date,
                'Raça' => 'MANGALARGA MARCHADOR ',
                'Cód Lab' => '',
                'ID' => '',
                'Registro Touro' => $animal->registro_pai,
                'Nome touro' => $animal->pai,
                'Cód Lab' => '',
                'ID' => '',
                'Registro Doadora' => $animal->registro_mae,
                'Nome matriz' => $animal->mae,
                'Fazenda' => $data->location,
                'Proprietário' => $order->creator,
                'Nº Pedido' => $order->collection_number,
                'Data Cadastro' => date('d/m/Y', strtotime($order->created_at)),
                'Prioridade' => '',
                'Responsável pela Coleta' => $order->cpf_technical,
                'Data da Coleta' => date('d/m/Y', strtotime($order->collection_date)),
                'TECNICO' => $order->technical_manager,
                'DATA RECEBIMENTO' => date('d/m/Y', strtotime($order->updated_at)),
            ];
        }

        $name = 'Pedido-' . $order->creator . '.xlsx';

        $orders = collect($newdata);

        $http_response_header = [
            'Content-Type' => 'application/vnd.ms-excel',

        ];

        (new FastExcel($orders))->export('arquivos/' . $name);

        return response()->download(public_path('arquivos/' . $name), $name, $http_response_header)->deleteFileAfterSend(true);
    }

    public function filter(Request $request)
    {
        $animals = Animal::with('order')->where('status', 'LIKE', '%' . $request->status . "%")->get();
        $viewRender = view('admin.includes.filter-status', get_defined_vars())->render();
        return response()->json([get_defined_vars()]);
    }

    public function search(Request $request)
    {
        if ($request->ajax()) {
            $orders = OrderRequest::with('user', 'orderRequestPayment')->where('status', '!=', 0)->where('creator', 'LIKE', '%' . $request->search . "%")->get();;
            $viewRender = view('admin.includes.filter-search', get_defined_vars())->render();
            return response()->json([get_defined_vars()]);
        }
    }
}
