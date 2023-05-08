<?php

namespace App\Http\Controllers\Admin;

use App\Models\OrderRequest;
use App\Models\PedidoAnimal;
use Illuminate\Http\Request;
use App\Models\OrderRequestPayment;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\SorologiaDate;
use Illuminate\Support\Facades\Http;

class AppOrderController extends Controller
{
    public function status(Request $request, $id)
    {

        $status = PedidoAnimal::where('id', $id)->first();
        $animal = Animal::where('id', $status->id_animal)->first();
        $status->update([
            'status' => $request->value,
        ]);
        if ($request->order) {
            $order = OrderRequest::with('tecnico', 'owner')->find($request->order);
            $order->update([
                'status' => 4,
            ]);
            $telefoneTecnico = str_replace(['(', ')', '-', ' '], ['', '', '', ''],  $order->tecnico->cell);
            $telefoneOwner = str_replace(['(', ')', '-', ' '], ['', '', '', ''],  $order->owner->cell);


            if ($request->value == 6) {
                $response = Http::post('https://api.z-api.io/instances/3B30881EC3E99084D3D3B6927F6ADC67/token/66E633717A0DCDD3D4A1BC19/send-text', [
                    "phone" => "55$telefoneOwner",
                    "message" => "Prezado Veterinário, a amostra do animal $animal->animal_name foi reprovada para realização do exame de XXXXXXXXXXXXXX. Solicitamos a coleta de uma nova amostra.
                    Quando a amostra for APROVADA, nenhuma mensagem é enviada e libera-se para GERAR PAGAMENTO."
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

    public function updateData(Request $request)
    {

        $data = [];
        if ($request->has('data_recebimento')) {
            $data['data_recebimento'] = $request->data_recebimento;
        }
        if ($request->has('data_resultado')) {
            $data['data_resultado'] = $request->data_resultado;
        }
        if ($request->has('numero_aie')) {
            $data['numero_aie'] = $request->numero_aie;
        }
        if ($request->has('numero_mormo')) {
            $data['numero_mormo'] = $request->numero_mormo;
        }
        if ($request->has('pedido')) {
            $data['pedido_id'] = $request->pedido;
        }

        $datas = SorologiaDate::updateOrCreate(
            ['animal_id' => $request->id_animal],
            $data
        );
        return response()->json($datas);
    }

    public function filter(Request $request)
    {
        $pedidos = PedidoAnimal::with('order', 'animal')->where('status', $request->status)->where('origin', 'app')->get();
        // dd($pedidos);
        $viewRender = view('admin.includes.vet-filter', get_defined_vars())->render();
        return response()->json([get_defined_vars()]);
    }
}
