<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use App\Models\Alelo;
use App\Models\Laudo;
use App\Models\Owner;
use App\Models\Animal;
use App\Models\QrCode;
use App\Models\Tecnico;
use App\Models\DnaVerify;
use App\Models\DataColeta;
use App\Models\OrdemServico;
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
    public function verPdf()
    {
        return view('admin.ordem-servico.laudo-imp-teste');
    }

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
        $id_pedidos = PedidoAnimal::whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('order_requests')
                ->whereColumn('order_requests.id', 'pedido_animals.id_pedido');
        })->distinct('id_pedido')->pluck('id_pedido');

        return $id_pedidos;
    }
    public function selectCodlabInRange()
    {
        $codlabs = Animal::select('codlab')
            ->whereRaw('CAST(SUBSTRING(codlab, 4) AS UNSIGNED) >= 100000 AND CAST(SUBSTRING(codlab, 4) AS UNSIGNED) < 200000')
            ->get();

        return $codlabs;
    }
    public function updateCodlabInRange()
    {
        $codlabs = Animal::select('id', 'codlab')
            ->whereRaw('CAST(SUBSTRING(codlab, 4) AS UNSIGNED) >= 100000 AND CAST(SUBSTRING(codlab, 4) AS UNSIGNED) < 200000')
            ->orderByRaw('CAST(SUBSTRING(codlab, 4) AS UNSIGNED)')
            ->get();

        $startValue = 100000;
        $updates = [];
        foreach ($codlabs as $animal) {
            $newCodlab = substr($animal->codlab, 0, 3) . strval($startValue);
            $updates[$animal->id] = ['codlab' => $newCodlab];
            $startValue += 1;
        }

        DB::beginTransaction();
        try {
            foreach ($updates as $id => $update) {
                Animal::where('id', $id)->update($update);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }
    }

    public function pdfLaudo($id)
    {
        $laudo = Laudo::find($id);
        $animal = Animal::find($laudo->animal_id);
        $owner = Owner::find($laudo->owner_id);
        $datas = DataColeta::where('id_animal', $laudo->animal_id)->first();
        $tecnico = Tecnico::find($laudo->veterinario_id);
        $dna_verify = DnaVerify::where('animal_id', $animal->id)->first();
        $sigla = substr($animal->especies, 0, 3);
        $examType = substr($dna_verify->verify_code, 3, 2);
        $ordem = OrdemServico::where('animal_id', $laudo->animal_id)->latest()->first();
        $pai = null;
        $mae = null;
        switch ($dna_verify->verify_code) {
            case $sigla . 'PD':
                $pai = Animal::with('alelos')->find($laudo->pai_id);
                break;
            case $sigla . 'MD':
                $mae = Animal::with('alelos')->find($laudo->mae_id);
                break;
            case $sigla . 'TR':
                $pai = Animal::with('alelos')->find($laudo->pai_id);
                $mae = Animal::with('alelos')->find($laudo->mae_id);
                break;
            default:
                break;
        }
        $qrCode = QrCode::where('laudo_id', $laudo->id)->first();

        return view('admin.ordem-servico.laudo-imp', get_defined_vars());
    }

    public function alelosDuplicados()
    {
        $duplicados = Alelo::select('animal_id', 'marcador')
            ->groupBy('animal_id', 'marcador')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        $dupli = [];
        foreach ($duplicados as $duplicado) {
            $dupli[] = "Animal ID: " . $duplicado->animal_id . " | Marcador: " . $duplicado->marcador;
        }
        return $dupli;
    }
    public function apagarAlelosDuplicados()
    {
        $duplicados = Alelo::select('animal_id', 'marcador')
            ->groupBy('animal_id', 'marcador')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicados as $duplicado) {
            $registrosDuplicados = Alelo::where('animal_id', $duplicado->animal_id)
                ->where('marcador', $duplicado->marcador)
                ->get();

            $primeiroRegistro = true;
            foreach ($registrosDuplicados as $registro) {
                if ($primeiroRegistro) {
                    $primeiroRegistro = false;
                    continue;
                }

                $registro->delete();
            }
        }
    }

    public function mudarEspecie()
    {
        $animals = Animal::where('especies', 'ASININA')->get();
        // foreach ($animals as $animal) {
        //     $animal->update([
        //         'especies' => 'ASININO',
        //     ]);
        // }
        return $animals;
    }
}
