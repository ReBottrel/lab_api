<?php

namespace App\Http\Controllers\Admin;

use App\Models\Fur;
use App\Models\Exam;
use App\Models\User;
use App\Models\Owner;
use App\Models\Animal;
use App\Models\Specie;
use App\Models\Tecnico;
use App\Models\OrderRequest;
use App\Models\PedidoAnimal;
use Illuminate\Http\Request;
use App\Models\OrderRequestPayment;
use App\Http\Controllers\Controller;
use App\Models\DataColeta;
use App\Models\DnaVerify;
use App\Models\ExamToAnimal;
use App\Models\Parceiro;
use App\Models\Sample;
use App\Models\UserInfo;
use App\Models\Veterinario;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Rap2hpoutre\FastExcel\FastExcel;

class OrderController extends Controller
{

    public function order()
    {
        $orders = OrderRequest::where('status', '!=', 0)->where('status', '!=', 5)->where('status', '!=', 7)->orderBy('id', 'desc')->paginate(10);
        return view('admin.order', get_defined_vars());
    }
    public function getNewOrders()
    {
        $orders = OrderRequest::where('status', '!=', 1)->latest()->get();
        return response()->json($orders);
    }
    public function orderEmail()
    {
        $orders = OrderRequest::where('origin', 'API')->where('status', '!=', 0)->paginate(10);
        return view('admin.order', get_defined_vars());
    }
    public function orderSistema()
    {
        $orders = OrderRequest::where('origin', 'sistema')->where('status', '!=', 0)->get();

        return view('admin.orders.orders-sistema', get_defined_vars());
    }

    public function orderVet()
    {
        $orders = OrderRequest::where('origin', 'app')->where('status', '!=', 0)->where('status', '!=', 5)->where('status', '!=', 7)->orderBy('id', 'desc')->paginate(10);
        return view('admin.orders.orders-vet', get_defined_vars());
    }
    public function orderVetDetail($id)
    {
        $order = OrderRequest::find($id);
        $pedidos = PedidoAnimal::where('id_pedido', $id)->get();
        // $animal = Animal::where('id', $pedido->id_animal)->first();
        $samples = Sample::get();

        $stats = [
            1 => 'Aguardando amostra',
            2 => 'Amostra recebida',
            3 => 'Em análise',
            4 => 'Análise concluída',
            5 => 'Resultado disponível',
            6 => 'Análise reprovada',
            7 => 'Análise Aprovada',
            8 => 'Recoleta solicitada',
            9 => 'Amostra paga',
            10 => 'Pedido Concluído',
            11 => 'Aguardando Pagamento'

        ];
        return view('admin.orders.order-vet-detail', get_defined_vars());
    }

    public function orderSistemaDetail($id)
    {
        $order = OrderRequest::find($id);
        $samples = Sample::get();
        $stats = [
            1 => 'Aguardando amostra',
            2 => 'Amostra recebida',
            3 => 'Em análise',
            4 => 'Análise concluída',
            5 => 'Resultado disponível',
            6 => 'Análise reprovada',
            7 => 'Análise Aprovada',
            8 => 'Recoleta solicitada',
            9 => 'Amostra paga',
            10 => 'Pedido Concluído',
            11 => 'Aguardando Pagamento'

        ];
        $animals = Animal::where('order_id', $id)->get();
        return view('admin.orders.order-sistema-detail', get_defined_vars());
    }
    public function orderDetail($id)
    {
        $order = OrderRequest::find($id);
        $samples = Sample::get();
        $stats = [
            1 => 'Aguardando amostra',
            2 => 'Amostra recebida',
            3 => 'Em análise',
            4 => 'Análise concluída',
            5 => 'Resultado disponível',
            6 => 'Análise reprovada',
            7 => 'Análise Aprovada',
            8 => 'Recoleta solicitada',
            9 => 'Amostra paga',
            10 => 'Pedido Concluído',
            11 => 'Aguardando Pagamento'

        ];
        // $animals = Animal::where('order_id', $id)->get();
        return view('admin.order-detail', get_defined_vars());
    }

    public function editOrder($id)
    {
        $order = OrderRequest::with('animals', 'datacoleta', 'user', 'tecnico')->find($id);
        $samples = Sample::get();
        $users = User::get();
        $tecnicos = Tecnico::get();
        $stats = [
            1 => 'Aguardando amostra',
            2 => 'Amostra recebida',
            3 => 'Em análise',
            4 => 'Análise concluída',
            5 => 'Resultado disponível',
            6 => 'Análise reprovada',
            7 => 'Análise Aprovada',
            8 => 'Recoleta solicitada',
            9 => 'Amostra paga',
            10 => 'Pedido Concluído',
            11 => 'Aguardando Pagamento'

        ];

        return view('admin.orders.edit-order', get_defined_vars());
    }

    public function editarProprietario(Request $request, $id)
    {
        $order = OrderRequest::find($id);
        $order->update([
            'owner_id' => $request->owner,
            'user_id' => $request->owner,
        ]);
        return redirect()->back()->with('success', 'Proprietário vinculado com sucesso');
    }
    public function editarTecnico(Request $request, $id)
    {
        $order = OrderRequest::find($id);
        $order->update([
            'id_tecnico' => $request->tecnico,
        ]);
        return redirect()->back()->with('success', 'Técnico vinculado com sucesso');
    }
    public function updateOrderData(Request $request, $id)
    {
        $order = OrderRequest::find($id);
        $order->update([
            'creator_number' => $request->creator_number,
        ]);
        return redirect()->back()->with('success', 'Dados atualizados com sucesso');
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
            if ($order->data_g != null) {

                foreach ($order->data_g['data_table'] as $data) {

                    $animal =  Animal::create([
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

                    PedidoAnimal::create([
                        'id_pedido' => $order->id,
                        'id_animal' => $animal->id,
                        'status' => 1,
                    ]);
                }
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
        $status = PedidoAnimal::where('id_animal', $animal->id)->first();


        if ($status) {
            $status->update([
                'status' => $request->value,
            ]);
        } else {
            PedidoAnimal::create([
                'id_pedido' => $animal->order_id,
                'id_animal' => $animal->id,
                'status' => $request->value,
            ]);
        }
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
                    "message" => "Prezado Criador, a amostra do animal $animal->animal_name foi recebida e APROVADA para
                    realização do exame de DNA no laboratório Loci.
                    Em breve você receberá o Link para liberação do pagamento."
                ]);
                $order->update([
                    'status' => 4,
                ]);
            }
            if ($request->value == 6) {
                $response = Http::post('https://api.z-api.io/instances/3B30881EC3E99084D3D3B6927F6ADC67/token/66E633717A0DCDD3D4A1BC19/send-text', [
                    "phone" => "55$telefoneOwner",
                    "message" => "Prezado Técnico,
                    A amostra do animal $animal->animal_name foi REPROVADA para a execução do exame de DNA no laboratório Loci.
                    Solicitamos RECOLETAR uma nova amostra, abrir um novo chamado junto a ASSOCIAÇÃO informando que se trata de uma RECOLETA solicitada pelo laboratório e nos encaminhar novamente para execução"
                ]);
            }
        }
        $animal->update([
            'status' => $request->value,
        ]);

        $orderRequest = OrderRequestPayment::where('animal_id', $animal->id)->first();
        if ($orderRequest) {
            if ($request->value == 10) {

                $orderRequest->update([
                    'payment_status' => 2,
                ]);
            }

            if ($request->value == 9) {

                $orderRequest->update([
                    'payment_status' => 1,
                ]);
            }
            if ($request->value == 11) {

                $orderRequest->update([
                    'payment_status' => 0,
                ]);
            }
        }

        \Log::channel('admins_actions')->info(['Usuário', auth()->user()->name], ['Alterou status de:', $animal->animal_name, 'para', $request->value]);

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

        $order_request = OrderRequest::with('user', 'tecnico', 'owner')->find($request->order);

        if ($order_request->origin == 'sistema' || $order_request->origin == 'API' || $order_request->origin == 'email') {

            $owner = Owner::where('user_id', $order_request->user_id)->first();
            $animals = Animal::where('order_id', $request->order)->where('status', 7)->get();
            foreach ($animals as $animal) {
                switch ($animal->especies) {
                    case 'BOVINA':
                        $exam = Exam::find(4);
                        break;
                    case 'MUARES':
                    case 'ASININA':
                    case 'EQUINO_PEGA':
                        $exam = Exam::find(20);
                        break;
                    case 'EQUINA':
                        $exam = Exam::find(4);
                        break;
                    default:
                        $exam = Exam::find(4);
                        break;
                }
                $orderPay = OrderRequestPayment::create([
                    'order_request_id' => $request->order,
                    'owner_name' => $order_request->owner->owner_name,
                    'email' => $order_request->owner->email,
                    'location' => $owner->propriety ?? 'Não informado',
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
                    'category_exam' => $animal->especies,
                    'paynow' => 1,
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
            $email = $order_request->owner->email;

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
            return response()->json($ordernew);
        } elseif ($order_request->origin == 'app') {
            $owner = Owner::where('id', $order_request->owner_id)->first();
            $pedidos = PedidoAnimal::where('id_pedido', $request->order)->where('status', 7)->get();
            foreach ($pedidos as $pedido) {
                $animal = Animal::where('id', $pedido->id_animal)->first();
                $exam = ExamToAnimal::where('animal_id', $animal->id)->first();
                $exame = Exam::where('id', $exam->exam_id)->first();
                $orderPay = OrderRequestPayment::create([
                    'order_request_id' => $order_request->id,
                    'owner_name' => $owner->owner_name,
                    'email' => $owner->email,
                    'location' => $animal->animal_location ?? 'Não informado',
                    'exam_id' => $exam->exam_id,
                    'category' => $exame->category,
                    'animal' => $animal->animal_name,
                    'title' => $exame->title,
                    'short_description' => $exame->short_description,
                    'value' => $exame->value,
                    'requests' => $exame->requests,
                    'extra_value' => $exame->extra_value,
                    'animal_id' => $animal->id,
                    'category_exam' => $animal->especies,
                    'paynow' => 1,
                ]);
                $pedido->update([
                    'status' => 11,
                ]);
            }


            $veterinario = Veterinario::find($order_request->id_tecnico);
            $senha = str_replace(['.', '-', '/'], ['', '', ''], $veterinario->cpf);
            $telefone = str_replace(['(', ')', '-', ' '], ['', '', '', ''],  $veterinario->phone);
            $user = User::where('email', $veterinario->email)->first();
            if (!$user) {
                $user = User::create([
                    'name' => $veterinario->name,
                    'email' => $veterinario->email,
                    'password' => bcrypt($senha),
                    'status' => 0,
                ]);
                $userinfo = UserInfo::create([
                    'user_id' => $user->id,
                    'document' => $veterinario->cpf,
                    'aie' => $veterinario->portaria,
                    'mormo' => $veterinario->portaria,
                    'crm_uf' => $veterinario->crmv,
                    'phone' => $veterinario->portaria,
                    'zip_code' => $veterinario->cep,
                    'address' => $veterinario->address,
                    'number' => $veterinario->number,
                    'complement' => $veterinario->complement,
                    'district' => $veterinario->district,
                    'city' => $veterinario->city,
                    'state' => $veterinario->state,
                    'status' => 1,
                ]);
            }
            $email = $veterinario->email;

            $url = route('user.dashboard');
            $response = Http::post('https://api.z-api.io/instances/3B30881EC3E99084D3D3B6927F6ADC67/token/66E633717A0DCDD3D4A1BC19/send-text', [
                "phone" => "55$telefone",
                "message" => "Prezado, Veterinário!
            Segue abaixo o Link de acesso para clicar, efetuar o pagamento e liberar o(s) exame(s) para execução.
            Ao acessar, digite seu E-MAIL: $email no campo USUÁRIO e o seu CPF: $senha em senha.",
                "linkUrl" => $url,
                "title" => "Locilab",
                "linkDescription" => "LociLab e a melhor plataforma de exames de DNA do Brasil",
            ]);

            $order_request->update([
                'status' => 2,
                'user_id' => $user->id,
            ]);

            Mail::to($email)->send(new \App\Mail\NewOrder($email, $senha));
            return response()->json($order_request);
        }
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
        $animals = Animal::with('order')->where('status', $request->status)->whereBetween('created_at', [$request->get('start'), $request->get('end')])->whereNotNull('order_id')->get();
        $viewRender = view('admin.includes.filter-status', get_defined_vars())->render();
        return response()->json([get_defined_vars()]);
    }

    public function search(Request $request)
    {
        if ($request->ajax()) {
            $orders = OrderRequest::with('user', 'orderRequestPayment')->where('status', '!=', 0)->where('creator', 'LIKE', '%' . $request->search . "%")->get();
            $viewRender = view('admin.includes.filter-search', get_defined_vars())->render();
            return response()->json([get_defined_vars()]);
        }
    }
    public function searchNumber(Request $request)
    {
        if ($request->ajax()) {
            $orders = OrderRequest::with('user', 'orderRequestPayment')->where('status', '!=', 0)->where('id', $request->search)->get();
            $viewRender = view('admin.includes.filter-search', get_defined_vars())->render();
            return response()->json([get_defined_vars()]);
        }
    }
    public function searchAnimal(Request $request)
    {
        if ($request->ajax()) {
            $animals = Animal::with('order')->where('animal_name', $request->animal)->get();
            $viewRender = view('admin.includes.search-animal', get_defined_vars())->render();
            return response()->json([get_defined_vars()]);
        }
    }

    public function orderCreate()
    {
        $parceiros = Parceiro::get();

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
                'collection_date' => $request->collection_date,
                'id_tecnico' => $request->tecnico,
                'status' => 5,
                'origin' => 'sistema',
                'uid' => $request->uid,
                'creator_number' => 0,
                'tipo' => $request->tipo,
                'parceiro' => $request->parceiro
            ]);
            return redirect()->route('admin.order-animal', [$order_request->id, $request->tipo]);
        }
        return redirect()->back()->with('error', 'Proprietário não possui cadastro no sistema');
    }

    public function orderAnimal($id, $type)
    {
        $order = OrderRequest::find($id);
        $species = Specie::get();
        $animals = Animal::where('order_id', $id)->get();

        switch ($type) {
            case 1:
                $view = 'admin.order-add-animal';
                break;
            case 2:
                $view = 'admin.order-add-animal';
                break;
            case 3:
                $view = 'admin.order-add-animal';
                break;
            case 4:
                $view = 'admin.order-add-animal';
                break;
            case 5:
                $view = 'admin.order-add-animal';
                break;
            default:
                $view = 'admin.order-add-animal';
        }

        return view($view, get_defined_vars());
    }

    public function orderAddAnimalPost(Request $request)
    {
        $order = OrderRequest::findOrFail($request->order);
        $owner = Owner::findOrFail($order->owner_id);
        $randomNumber = mt_rand(0, 1000000);
        $sigla = substr($request->especies, 0, 3);

        $data = [
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
            'owner_id' => $owner->id,
            'especie_pai' => $request->especie_pai,
            'especie_mae' => $request->especie_mae,

        ];

        $data['codlab'] = Animal::where('codlab', $randomNumber)->exists() ? $sigla . rand(0, 1000000) : $sigla . $randomNumber;

        if ($request->verify_code == 'semverify') {
            $tipo = 'EQUTR';
            switch ($request->especies) {
                case 'EQUINA':
                    $tipo = 'EQUTR';
                    break;
                case 'MUARES':
                    $tipo = 'MUATR';
                    break;
                case 'ASININA':
                    $tipo = 'ASITR';
                    break;
                case 'EQUINO_PEGA':
                    $tipo = 'ASITR';
                    break;
                case 'BOVINA':
                    $tipo = 'BOVTR';
                    break;
                default:
                    $tipo = 'EQUTR';
            }
        }
        if ($request->verify_code == 'semverify') {
            $verify_code = $tipo;
        } else {
            $verify_code = $request->verify_code;
        }

        if ($request->id) {
            $animal = Animal::findOrFail($request->id);
            $animal->update($data);
            $datacoleta = DataColeta::create([
                'id_animal' => $request->id,
                'id_order' => $order->id,
                'data_coleta' => date('d/m/Y', strtotime($request->data_coleta)),
                'data_recebimento' => date('d/m/Y', strtotime($order->collection_date)),
                'data_laboratorio' => date('d/m/Y', strtotime($request->data_laboratorio)),
                'tipo' => $request->tipo
            ]);
            // Define o valor de $verify_code com base no parâmetro $request->verify_code
            $verify = DnaVerify::create([
                'animal_id' => $request->id,
                'order_id' => $order->id,
                'verify_code' => $verify_code,
            ]);
        } else {
            $create = Animal::create($data);
            $datacoleta = DataColeta::create([
                'id_animal' => $create->id,
                'id_order' => $order->id,
                'data_coleta' => date('d/m/Y', strtotime($request->data_coleta)),
                'data_recebimento' => date('d/m/Y', strtotime($order->collection_date)),
                'data_laboratorio' => date('d/m/Y', strtotime($request->data_laboratorio)),
                'tipo' => $request->tipo
            ]);

            $verify = DnaVerify::create([
                'animal_id' => $create->id,
                'order_id' => $order->id,
                'verify_code' => $verify_code,
            ]);
        }

        return redirect()->route('admin.order-animal', [$order->id, $order->tipo]);
    }
    public function orderAddAnimalEdit(Request $request)
    {
        $animal = Animal::find($request->id);
        $species = Specie::get();
        return response()->json([
            'animal' => $animal,
            'species' => $species
        ]);
    }

    public function updateAnimalOrder(Request $request)
    {
        $animal = Animal::find($request->animal);

        $animal->update([
            'order_id' => $request->order,
            'status' => 1,
        ]);
        $order = OrderRequest::find($request->order);
        return redirect()->route('admin.order-animal', [$order->id, $order->tipo]);
    }

    public function addAnimalToOrder($id)
    {
        $order = OrderRequest::find($id);
        $species = Specie::get();
        $furs = Fur::get();
        $animals = Animal::where('order_id', $id)->get();
        $samples = Sample::get();
        switch ($order->tipo) {
            case 1:
                return view('admin.orders.create-product', get_defined_vars());
                break;
            case 2:
                return view('admin.orders.create-homozigose', get_defined_vars());
                break;
            case 3:
                return view('admin.orders.create-beta', get_defined_vars());
                break;
            case 4:
                return redirect()->route('admin.order-sorologia-animal', $order->id)->with('error', 'Em desenvolvimento');
                break;
            case 5:
                return view('admin.orders.create-parentesco', get_defined_vars());
                break;
            default:
                return view('admin.orders.create-product', get_defined_vars());
                break;
        }
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
        return redirect()->route('orders.all')->with('success', 'Pedido criado com sucesso');
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
    public function exportFilter(Request $request)
    {
        $animals = OrderRequestPayment::whereBetween('created_at', [$request->get('from'), $request->get('to')])->where('payment_status', 1)->get();
        $newdata = [];
        foreach ($animals as $order) {
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
        $name =  'relatorio-filtro.xlsx';

        $orders = collect($newdata);

        $http_response_header = [
            'Content-Type' => 'application/vnd.ms-excel',

        ];

        (new FastExcel($orders))->export('arquivos/' . $name);

        return response()->download(public_path('arquivos/' . $name), $name, $http_response_header)->deleteFileAfterSend(true);
    }
    public function exportOrders()
    {
        $orders = OrderRequest::where('total', '>', 1)->get();
        $newdata = [];
        foreach ($orders as $order) {
            $newdata[] = [
                'id' => $order->id,
                'Criador' => $order->creator,
                'Status de pagamento' => 'Pago',
                'E-mail' => $order->email,
                'Categoria de Exame' => $order->category,
                'Produto' => $order->animal,
                'Valor do pagamento' => $order->total,
                'Data de criação' => date('d/m/Y', strtotime($order->created_at)),
                'Data de pagamento' => date('d/m/Y', strtotime($order->updated_at)),
            ];
        }
        $date = date('d-m-y h:i:s');
        $name =  'relatorio-order.xlsx';

        $orders = collect($newdata);

        $http_response_header = [
            'Content-Type' => 'application/vnd.ms-excel',

        ];

        (new FastExcel($orders))->export('arquivos/' . $name);

        return response()->download(public_path('arquivos/' . $name), $name, $http_response_header)->deleteFileAfterSend(true);
    }

    public function exportPedentes()
    {
        $orders = OrderRequestPayment::whereBetween('created_at', ['2023-01-16', '2023-01-21'])->whereNotNull('payment_id')->get();
        $newdata = [];
        foreach ($orders as $order) {
            $newdata[] = [
                'id' => $order->id,
                'Criador' => $order->owner_name,
                'Status de pagamento' => $order->payment_status == 1 ? 'Pago' : 'Pendente',
                'E-mail' => $order->email,
                'Categoria de Exame' => $order->category,
                'Produto' => $order->animal,
                'Valor do pagamento' => $order->value,
                'ID de Pagamento' => $order->payment_id,
                'Data de criação' => date('d/m/Y', strtotime($order->created_at)),
                'Data de pagamento' => date('d/m/Y', strtotime($order->created_at)),
            ];
        }
        $date = date('d-m-y h:i:s');
        $name =  'relatorio-pedentes.xlsx';

        $orders = collect($newdata);

        $http_response_header = [
            'Content-Type' => 'application/vnd.ms-excel',

        ];

        (new FastExcel($orders))->export('arquivos/' . $name);

        return response()->download(public_path('arquivos/' . $name), $name, $http_response_header)->deleteFileAfterSend(true);
    }
}
