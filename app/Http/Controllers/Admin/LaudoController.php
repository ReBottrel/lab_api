<?php

namespace App\Http\Controllers\Admin;

use TCPDF;

use Dompdf\Dompdf;
use App\Models\Laudo;

use App\Models\Owner;
use App\Models\Animal;
use App\Models\Tecnico;
use phpseclib\Crypt\RSA;
use phpseclib\File\ASN1;
use phpseclib\File\X509;
// use X509\CertificationPath;
use App\Models\DataColeta;
use Spatie\PdfToImage\Pdf;
use App\Models\OrdemServico;
use App\Models\OrderRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

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
        $html = view('admin.ordem-servico.laudo-imp', get_defined_vars())->render();
        $dompdf->loadHtml($html);
        $dompdf->render();

        // Obtém o conteúdo do PDF gerado
        $output = $dompdf->output();

        // Caminho para o certificado A1 e senha
        $certificadoPath = 'file://'. public_path('certificado/LOCI_BIOTECNOLOGIA_LTDA_18496213000111_1661426936642166100.pfx');
        $senhaCertificado = 'Loci4331';

        // Carrega o certificado A1 e a chave privada correspondente (no formato PFX)
        $pfxContent = file_get_contents($certificadoPath);

        // Extrai o certificado e a chave privada
        $x509 = new X509();
        $asn1 = new ASN1();

        // Parse o certificado
        $cert = $x509->loadX509($pfxContent);

        // Extrai a chave privada usando Crypt_RSA
        $rsa = new RSA();
        $rsa->loadKey($pfxContent, 'pfx', $senhaCertificado);
        $privateKey = $rsa->getPrivateKey();

        // Assina o conteúdo do PDF
        $signature = $rsa->sign($output);

        // Define a assinatura digital no documento PDF
        $dompdf->getCanvas()->page_text(72, 18, '', null, 10, array(0, 0, 0), 0.5, false, null, 'T', 'M');
        $dompdf->getCanvas()->page_text(72, 28, '', null, 10, array(0, 0, 0), 0.5, false, null, 'T', 'M');
        $dompdf->getCanvas()->page_text(72, 38, '', null, 10, array(0, 0, 0), 0.5, false, null, 'T', 'M');
        $dompdf->getCanvas()->page_text(72, 48, '', null, 10, array(0, 0, 0), 0.5, false, null, 'T', 'M');
        $dompdf->getCanvas()->page_text(72, 58, '', null, 10, array(0, 0, 0), 0.5, false, null, 'T', 'M');
        $dompdf->getCanvas()->page_text(72, 68, '', null, 10, array(0, 0, 0), 0.5, false, null, 'T', 'M');
        $dompdf->getCanvas()->page_text(72, 78, '', null, 10, array(0, 0, 0), 0.5, false, null, 'T', 'M');
        $dompdf->getCanvas()->page_text(72, 88, '', null, 10, array(0, 0, 0), 0.5, false, null, 'T', 'M');
        $dompdf->getCanvas()->page_text(72, 98, '', null, 10, array(0, 0, 0), 0.5, false, null, 'T', 'M');

        // Obtém o conteúdo do PDF assinado
        $outputAssinado = $dompdf->output();

        // Gera um nome de arquivo exclusivo para o PDF assinado
        $filename = 'signed-pdf-' . time() . '.pdf';

        // Salva o PDF assinado no diretório público
        Storage::disk('public')->put($filename, $outputAssinado);

        // Gera a resposta de download
        $path = Storage::disk('public')->path($filename);
        return response()->download($path, $filename);
    }

    public function finalizar(Request $request)
    {
        $laudo = Laudo::find($request->laudo);
      
        return response()->json($laudo, 200);
    }

    public function verify()
    {
        $pdfPath = public_path('pdf/etiqueta.pdf');
        // Converter o PDF para uma imagem (a primeira página)
        $pdf = new Pdf($pdfPath);
        $pdf->setResolution(300); // Define a resolução da imagem (opcional)
        $pdf->setOutputFormat('png'); // Define o formato da imagem de saída (opcional)
        $imagePath = $pdfPath . '.png'; // Caminho para a imagem gerada

        $pdf->saveImage($imagePath);

        // Verificar se o arquivo de imagem foi gerado
        if (file_exists($imagePath)) {
            // O documento possui assinatura(s)
            echo "O documento está assinado.";
        } else {
            // O documento não possui assinaturas
            echo "O documento não está assinado.";
        }
    }
}
