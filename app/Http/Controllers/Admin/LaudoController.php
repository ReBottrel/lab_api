<?php

namespace App\Http\Controllers\Admin;

use TCPDF;
use Dompdf\Dompdf;
use App\Models\Alelo;
use App\Models\Laudo;
use App\Models\Owner;
use App\Models\Animal;
use App\Models\Tecnico;
use phpseclib\Crypt\RSA;
use phpseclib3\File\X509;
use App\Models\DataColeta;
use App\Models\OrdemServico;
use App\Models\OrderRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use LSNepomuceno\LaravelA1PdfSign\Sign\ManageCert;

class LaudoController extends Controller
{
    public function store(Request $request)
    {
        $ordem = OrdemServico::find($request->ordem);
        $order = OrderRequest::find($ordem->order);
        $animal = Animal::find($ordem->animal_id);
        $pai = Animal::where('animal_name', $animal->pai)->first();
        $mae = Animal::where('animal_name', $animal->mae)->first();
        $datas = DataColeta::where('id_order', $order->id)->first();
        $laudo = Laudo::where('animal_id', $ordem->animal_id)->first();

        $laudoData = [
            'animal_id' => $ordem->animal_id,
            'mae_id' => $mae->id,
            'pai_id' => $pai->id,
            'veterinario' => $ordem->tecnico,
            'owner_id' => $ordem->owner_id,
            'data_coleta' => $datas->data_coleta,
            'data_realizacao' => $datas->data_recebimento,
            'data_lab' => $datas->data_laboratorio,
            'codigo_busca' => '123456789',
            'observacao' => $request->obs,
            'conclusao' => $request->conclusao,
            'tipo' => $datas->tipo,
            'veterinario_id' => $order->id_tecnico
        ];

        if ($laudo) {
            $laudo->update($laudoData);
        } else {
            $laudo = Laudo::create($laudoData);
        }

        return response()->json($laudo, 200);
    }

    public function show($id)
    {
        $laudo = Laudo::find($id);
        $animal = Animal::find($laudo->animal_id);
        $owner = Owner::find($laudo->owner_id);
        $datas = DataColeta::where('id_animal', $laudo->animal_id)->first();
        $tecnico = Tecnico::find($laudo->veterinario_id);
        $mae = Animal::with('alelos')->find($laudo->mae_id);
        $pai = Animal::with('alelos')->find($laudo->pai_id);
        return view('admin.ordem-servico.laudo', get_defined_vars());
    }

    public function gerarPdf($id)
    {
        $laudo = Laudo::find($id);
        $animal = Animal::find($laudo->animal_id);
        $owner = Owner::find($laudo->owner_id);
        $datas = DataColeta::where('id_animal', $laudo->animal_id)->first();
        $tecnico = Tecnico::find($laudo->veterinario_id);
        $mae = Animal::with('alelos')->find($laudo->mae_id);
        $pai = Animal::with('alelos')->find($laudo->pai_id);

        // Cria uma instância do Dompdf
        $dompdf = new Dompdf();

        // Define o tamanho e a orientação da página como A4
        $dompdf->setPaper('A4', 'portrait');

        // Renderiza o HTML em PDF
        $html = view('admin.ordem-servico.laudo-imp', get_defined_vars());
        $dompdf->loadHtml($html);
        $dompdf->render();

        // Obtém o conteúdo do PDF gerado
        $output = $dompdf->output();

        // Carrega o certificado A1 e a chave privada correspondente
        try {
            $cert = new ManageCert;
            $cert->setPreservePfx() // If you need to preserve the PFX certificate file
                ->fromPfx(Storage::path('certificado/LOCI_BIOTECNOLOGIA_LTDA_18496213000111_1661426936642166100.pfx'), 'Loci4331');
            dd($cert->getCert());
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
