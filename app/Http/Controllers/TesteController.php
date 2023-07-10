<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use App\Models\Owner;
use App\Models\Animal;
use App\Models\OrderRequest;
use App\Models\PedidoAnimal;
use Illuminate\Http\Request;
use App\Models\ResenhaAnimal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TesteController extends Controller
{
    public function __construct()
    {
        ini_set('max_execution_time', 8000);
    }
    public function index()
    {
        return view('teste');
    }

    public function duplicate()
    {
        $owner = Owner::get();
        $ownernovo = $owner->groupBy('owner_name')->filter(function ($item) {
            return $item->count() > 1;
        })->map(function ($item) {
            return $item->count();
        });
        \Log::info($ownernovo->toArray());
    }

    public function api()
    {
        $response = Http::get('http://laboratorios.abccmm.org.br/api/Exames', ['registro' => '044806']);

        $data = $response->body();
        $data = json_decode($data, true);
        $data = collect($data);
        $animal = Animal::where('animal_name', $data['animal']['nomeAnimal'])->first();
        $animal->number_definitive = $data['animal']['registro'];
        $animal->save();
        return 'ok';
        // $data = json_decode($data);
        // $data = collect($data);

        // return view('teste', ['data' => $data]);
    }

    public function gerarPdf()
    {
        // Cria uma instância do Dompdf
        $dompdf = new Dompdf();

        // Define o tamanho e a orientação da página como A4
        $dompdf->setPaper('A4', 'portrait');

        // Renderiza o HTML em PDF
        // $html = view('admin.ordem-servico.laudo');
        $html = view('admin.ordem-servico.laudo-imp-teste');
        $dompdf->loadHtml($html);
        $dompdf->render();

        // Gera o PDF e envia para o navegador
        $dompdf->stream('documento.pdf');
    }
    // public function verPdf()
    // {
    //     return view('admin.ordem-servico.laudo-imp-teste');
    // }

    public function updateStatus()
    {
        $data = PedidoAnimal::get();

        foreach ($data as $key => $value) {
            $animal = Animal::find($value->id_animal);
            if ($animal) {
                $animal->update([
                    'order_id' => $value->id_pedido,
                ]);
            }
        }
        return 'ok';
    }

    public function getOrderNotCreate()
    {
        $pedido_animals = PedidoAnimal::whereExists(function ($query) {
            $query->select(DB::raw(1))
                  ->from('order_requests')
                  ->whereColumn('order_requests.id', 'pedido_animals.id_pedido');
        })->get();
    
        return $pedido_animals;
    }
}
