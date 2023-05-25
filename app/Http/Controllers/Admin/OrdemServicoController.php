<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Exam;
use App\Models\Animal;
use App\Models\OrderLote;
use App\Models\OrdemServico;
use App\Models\OrderRequest;
use Illuminate\Http\Request;
use App\Models\OrderRequestPayment;
use App\Http\Controllers\Controller;
use Picqer\Barcode\BarcodeGeneratorPNG;


class OrdemServicoController extends Controller
{
    public function store(Request $request)
    {

        $order = OrderRequest::find($request->order);
        $orderRequest = OrderRequestPayment::where('order_request_id', $order->id)->get();
        $lote = OrderLote::create([
            'order_id' => $order->id,
            'owner' => $order->creator,
        ]);
        foreach ($orderRequest as $item) {
            $exame = Exam::find($item->exam_id);
            $animal = Animal::find($item->animal_id);
            $data = Carbon::now()->addWeekdays($exame->days);
            $randomNumber = mt_rand(0, 1000000);

            if ($animal->codlab == null) {
                $animal->update([
                    'codlab' => Animal::where('codlab', $randomNumber)->exists() ? rand(0, 1000000) : $randomNumber,
                ]);
            }

            $ordemServico = OrdemServico::create([
                'order' => $order->id,
                'animal_id' => $animal->id,
                'owner_id' => $order->owner_id,
                'lote' => $lote->id,
                'animal' => $animal->animal_name,
                'codlab' => $animal->codlab,
                'id_abccmm' => $animal->register_number_brand,
                'tipo_exame' => $item->title,
                'proprietario' => $order->creator,
                'tecnico' => $order->technical_manager,
                'data' => $data,
                'status' => 1,

            ]);
        }



        return response()->json($ordemServico);
    }

    public function index()
    {
        $ordemServicos = OrderLote::paginate(10);
        return view('admin.ordem-servico.index', get_defined_vars());
    }

    public function show($id)
    {
        $ordem = OrderLote::find($id);
        $ordemServicos = OrdemServico::where('lote', $id)->get();
        return view('admin.ordem-servico.show', get_defined_vars());
    }

    public function gerarBarCode($id)
    {
        $ordem = OrdemServico::find($id);
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($ordem->codlab, $generator::TYPE_CODE_128);

        $barcodex = base64_encode($barcode);

        return view('admin.ordem-servico.bar-code', get_defined_vars());
    }
}
