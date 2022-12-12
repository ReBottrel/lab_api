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
        $orders = OrderRequest::where('status', '!=', 0)->paginate(10);
        return view('admin.order', get_defined_vars());
    }
    public function orderEmail()
    {
        $orders = OrderRequest::where('origin', 'email')->where('status', '!=', 0)->paginate(10);
        return view('admin.order', get_defined_vars());
    }
    public function orderSistema()
    {
        $orders = OrderRequest::where('origin', 'sistema')->where('status', '!=', 0)->get();
        return view('admin.orders.orders-sistema', get_defined_vars());
    }
    public function orderSistemaDetail($id)
    {
        $order = OrderRequest::find($id);
        $animals = Animal::where('order_id', $id)->get();
        return view('admin.orders.order-sistema-detail', get_defined_vars());
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
        $order = OrderRequest::with('owner')->find($request->id);
        $user = Owner::find($request->owner);
        if ($user->user_id != null) {
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

            $order->update([
                'user_id' => $user->user_id,
                'owner_id' => $request->owner
            ]);

            return redirect()->back()->with('success', 'Proprietário vinculado com sucesso');
        } else {
            return redirect()->back()->with('error', 'Proprietário não possui acesso ao sistema, por favor crie um acesso e repita o processo');
        }
    }

    public function amostra(Request $request, $id)
    {
        $animal = Animal::find($id);

        if ($request->order) {
            $order = OrderRequest::with('tecnico', 'owner')->find($request->order);
            $order->update([
                'status' => 4,
            ]);

            $telefoneTecnico = str_replace(['(', ')', '-', ' '], ['', '', '', ''],  $order->tecnico->cell);
            $telefoneOwner = str_replace(['(', ')', '-', ' '], ['', '', '', ''],  $order->owner->cell);

            if ($request->value == 7) {
                $response = Http::post('https://api.z-api.io/instances/3B30881EC3E99084D3D3B6927F6ADC67/token/66E633717A0DCDD3D4A1BC19/send-text', [
                    "phone" => "55$telefoneTecnico",
                    "message" => "Prezado Técnico,
                    A amostra do animal $animal->animal_name foi recebida e APROVADA para realização do exame de DNA no Laboratório Loci.
                    "
                ]);
            }
            if ($request->value == 6) {
                $response = Http::post('https://api.z-api.io/instances/3B30881EC3E99084D3D3B6927F6ADC67/token/66E633717A0DCDD3D4A1BC19/send-text', [
                    "phone" => "55$telefoneTecnico",
                    "message" => "Prezado Técnico,
                    A amostra do animal $animal->animal_name  foi REPROVADA para a execução do exame de DNA no laboratório Loci.
                    Solicitamos RECOLETAR uma nova amostra, abrir um novo chamado junto a ABCCMM informando que se trata de uma RECOLETA solicitada pelo laboratório e nos encaminhar novamente para execução.
                    "
                ]);
            }

            if ($request->value == 7) {
                $response = Http::post('https://api.z-api.io/instances/3B30881EC3E99084D3D3B6927F6ADC67/token/66E633717A0DCDD3D4A1BC19/send-text', [
                    "phone" => "55$telefoneOwner",
                    "message" => "Prezado Criador,
                    A amostra do animal $animal->animal_name foi recebida e APROVADA para realização do exame de DNA no Laboratório Loci"
                ]);
            }
            if ($request->value == 6) {
                $response = Http::post('https://api.z-api.io/instances/3B30881EC3E99084D3D3B6927F6ADC67/token/66E633717A0DCDD3D4A1BC19/send-text', [
                    "phone" => "55$telefoneOwner",
                    "message" => "Prezado Criador,
                    A amostra do animal $animal->animal_name  foi REPROVADA para a execução do exame de DNA no laboratório Loci.
                    Solicitamos RECOLETAR uma nova amostra, abrir um novo chamado junto a ABCCMM informando que se trata de uma RECOLETA solicitada pelo laboratório e nos encaminhar novamente para execução.
                    "
                ]);
            }
        }
        $animal->update([
            'status' => $request->value,
        ]);

        \Log::channel('admins_actions')->info(['Usuário', auth()->user()->name], ['Alterou status de:', $animal->animal_name]);

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
        $order = OrderRequest::with('tecnico')->find($id);
        $tecnicos = Tecnico::get();
        return view('admin.tecnico', get_defined_vars());
    }

    public function technicalStore(Request $request)
    {
        $order = OrderRequest::find($request->id);
        $order->update([
            'id_tecnico' => $request->tecnico,
        ]);

        return redirect()->back()->with('success', 'Técnico adicionado com sucesso!');
    }

    public function orderRequestPost(Request $request)
    {
        // $user = user_token();
        // $order_request = collect($request->all())->put('origin', 'site')->put('user_id', $user->id);
        $order_request = OrderRequest::with('user', 'tecnico', 'owner')->find($request->order);

        $owner = Owner::where('user_id', $order_request->user_id)->first();


        $animals = Animal::where('order_id', $request->order)->where('status', 7)->get();
        foreach ($animals as $animal) {
            if ($animal->especies == 'BOVINA') {
                $exam = Exam::find(4);
            } elseif ($animal->especies == 'MUARES') {
                $exam = Exam::find(20);
            } elseif ($animal->especies == 'ASININO') {
                $exam = Exam::find(20);
            } elseif ($animal->especies == 'EQUINO_PEGA') {
                $exam = Exam::find(20);
            } elseif ($animal->especies == 'EQUINA') {
                $exam = Exam::find(4);
            } else {
                $exam = Exam::find(4);
            }
            $orderPay = OrderRequestPayment::create([
                'order_request_id' => $request->order,
                'owner_name' => $order_request->owner->owner_name,
                'email' => $order_request->owner->email,
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
                'animal_id' => $animal->id,
                'category_exam' => $animal->especies
            ]);
            $animal->update([
                'status' => 11,
            ]);
        }

        $order_request->update([
            'status' => 2,
        ]);
        $ordernew = OrderRequest::with('user', 'tecnico')->find($request->id);
        $data = [];
        $email = $owner->email;

        $senha = str_replace(['.', '-', '/'], ['', '', ''], $owner->document);
        $telefone = str_replace(['(', ')', '-', ' '], ['', '', '', ''],  $order_request->owner->cell);
        $url = route('user.dashboard');
        $response = Http::post('https://api.z-api.io/instances/3B30881EC3E99084D3D3B6927F6ADC67/token/66E633717A0DCDD3D4A1BC19/send-text', [
            "phone" => "55$telefone",
            "message" => "Prezado, Criador!
            Segue abaixo o Link de acesso para clicar, efetuar o pagamento e liberar o(s) exame(s) para execução.
            Ao acessar, digite seu E-MAIL: $email no campo USUÁRIO e o seu CPF: $senha em senha.
            Selecione os animais para pagamento e defina o prazo de liberação do resultado (temos opções de 20 dias úteis a 24 horas)*.
            *os valores variam conforme o prazo de liberação.
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
        $order = OrderRequest::with('user', 'orderRequestPayment', 'owner')->find($id);
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
                'Cód Lab1' => '',
                'ID1' => '',
                'Registro Doadora' => $animal->registro_mae,
                'Nome matriz' => $animal->mae,
                'Fazenda' => $data->location,
                'Proprietário' => $order->user->name,
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
        $animals = Animal::with('order')->where('status', $request->status)->whereNotNull('order_id')->get();
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

    public function orderCreate()
    {
        $owners = Owner::get();
        // $animals = Animal::get();
        $tecnicos = Tecnico::get();
        return view('admin.order-create', get_defined_vars());
    }

    public function requestPost(Request $request)
    {
        $owner = Owner::find($request->owner);
        $tecnico = Tecnico::find($request->tecnico);

        if ($owner->user_id != null) {
            $order_request = OrderRequest::create([
                'user_id' => $owner->user_id,
                'collection_number' => $request->collection_number,
                'collection_date' => $request->collection_date,
                'technical_manager' => $tecnico->professional_name,
                'creator' => $owner->owner_name,
                'owner_id' => $request->owner,
                'id_tecnico' => $request->tecnico,
                'status' => 0,
                'origin' => 'sistema',
                'uid' => $request->uid,
                'creator_number' => 0,
                'tipo' => $request->tipo
            ]);
            return redirect()->route('admin.order-add-animal', $order_request->id);
        }
        return redirect()->back()->with('error', 'Proprietário não possui cadastro no sistema');
    }
    public function orderAddAnimal($id)
    {
        $order = OrderRequest::find($id);
        $animals = Animal::where('order_id', $id)->get();
        return view('admin.order-add-animal', get_defined_vars());
    }

    public function orderAddAnimalPost(Request $request)
    {
        $order = OrderRequest::find($request->order);
        $owner = Owner::find($order->owner_id);
        $create = Animal::create([
            'user_id' => $owner->user_id,
            'order_id' => $request->order,
            'register_number_brand' => $request->register_number_brand,
            'animal_name' => $request->animal_name,
            'especies' => $request->especies,
            'breed' => $request->breed,
            'sex' => $request->sex,
            'age' => $request->age,
            'birth_date' => $request->birth_date,
            'chip_number' => $request->chip_number,
            'registro_pai' => $request->registro_pai,
            'pai' => $request->pai,
            'registro_mae' => $request->registro_mae,
            'mae' => $request->mae,
            'owner_id' => $owner->id
        ]);
        return redirect()->back()->with('success', 'Produto adicionado com sucesso');
    }
    public function orderAddAnimalDelete($id)
    {
        $animal = Animal::find($id);
        $animal->delete();
        return redirect()->back()->with('success', 'Produto removido com sucesso');
    }
    public function orderEnd($id)
    {
        $code = '';
        $number = 1000;
        if (auth()->user()->association_id == null) {
            $code = "SI";
        }
        if (auth()->user()->association_id == 1) {
            $code = "PA";
        }
        if (auth()->user()->association_id == 2) {
            $code = "PE";
        }
        if (auth()->user()->association_id == 3) {
            $code = "QM";
        }
        if (auth()->user()->association_id == 4) {
            $code = "CA";
        }
        $order = OrderRequest::find($id);
        $order->status = 1;
        $order->creator_number = '' . $code . '00' . $order->id . '';
        $order->save();
        return redirect()->route('orders.sistema')->with('success', 'Pedido finalizado com sucesso');
    }
    public function orderListSistem()
    {
        $orders = OrderRequest::with('user', 'orderRequestPayment')->where('origin', 'sistema')->where('status', '!=', 0)->get();
        return view('admin.orders.list-sistem-orders', get_defined_vars());
    }
    public function filterPayment(Request $request)
    {
        $animals = Animal::with('order')->where('status', $request->status)->where('order_id', '!=', null)->get();

        $viewRender = view('admin.includes.filter-payment', get_defined_vars())->render();
        return response()->json([get_defined_vars()]);
    }
    public function orderDelete($id)
    {
        $orders = OrderRequest::with('orderRequestPayment')->find($id);
        $animals = Animal::where('order_id', $id)->get();
        foreach ($animals as $animal) {
            $animal->delete();
        }
        \Log::channel('admins_actions')->info(['deletou', auth()->user()->name], ['Order Deletada', $orders->creator]);
        $orders->orderRequestPayment()->delete();
        $orders->delete();

        return redirect()->back()->with('success', 'Pedido removido com sucesso');
    }
    public function massUpdate()
    {
        $orders = OrderRequest::where('tipo', null)->update([
            'tipo' => 1,
        ]);

        return response()->json($orders);
    }
    public function exportPay()
    {
        $orders = OrderRequestPayment::where('payment_status', 1)->get();

        $newdata = [];
        foreach ($orders as $order) {
            $newdata[] = [
                'id' => $order->id,
                'Id de pagamento' => $order->payment_id ? $order->payment_id : 'Pago fora do sistema',
                'Criador' => $order->owner_name,
                'Status de pagamento' => 'Pago',
                'E-mail' => $order->email,
                'Categoria de Exame' => $order->category,
                'Produto' => $order->animal,
                'Valor do pagamento' => $order->value,
                'Data de pagamento' => date('d/m/Y', strtotime($order->updated_at)),
            ];
        }
        $date = date('d-m-y h:i:s');
        $name =  'relatorio-' . $date . '.xlsx';

        $orders = collect($newdata);

        $http_response_header = [
            'Content-Type' => 'application/vnd.ms-excel',

        ];

        (new FastExcel($orders))->export('arquivos/' . $name);

        return response()->download(public_path('arquivos/' . $name), $name, $http_response_header)->deleteFileAfterSend(true);
    }
    public function dateFilter(Request $request)
    {
        $animals = OrderRequestPayment::whereBetween('updated_at', [$request->get('from'), $request->get('to')])->where('payment_status', 1)->get();
        $viewRender = view('admin.includes.filter-date', get_defined_vars())->render();
        return response()->json([get_defined_vars()]);
    }
}
