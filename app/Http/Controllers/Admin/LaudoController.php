<?php

namespace App\Http\Controllers\Admin;

use TCPDF;

use Dompdf\Dompdf;
use App\Models\Laudo;

use App\Models\Owner;
use App\Models\Animal;
use App\Models\Tecnico;
use BaconQrCode\Writer;
use App\Models\Parceiro;
use phpseclib\Crypt\RSA;
// use X509\CertificationPath;
use phpseclib\File\ASN1;
use phpseclib\File\X509;
use App\Models\DnaVerify;
use App\Models\DataColeta;
use Spatie\PdfToImage\Pdf;
use App\Models\OrdemServico;
use App\Models\OrderRequest;
use Illuminate\Http\Request;
use App\Mail\EnviarLaudoMail;
use BaconQrCode\Renderer\Image\Png;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\QrCode as ModelQrCode;
use BaconQrCode\Renderer\ImageRenderer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;
use DOMDocument;


class LaudoController extends Controller
{


    public function index()
    {
        $laudos = Laudo::with('animal')->get();
        return view('admin.laudos.index', get_defined_vars());
    }

    public function store(Request $request)
    {
        $ordem = OrdemServico::find($request->ordem);
        $order = OrderRequest::find($ordem->order);
        $animal = Animal::find($ordem->animal_id);
        $pai = Animal::where('animal_name', $animal->pai)->first();
        $mae = Animal::where('animal_name', $animal->mae)->first();
        $datas = DataColeta::where('id_animal', $ordem->animal_id)->first();
        $laudo = Laudo::where('animal_id', $ordem->animal_id)->first();
        $codigo = rand(100000000, 999999999);



        $laudoData = [
            'animal_id' => $ordem->animal_id,
            'mae_id' => $mae->id ?? null,
            'pai_id' => $pai->id ?? null,
            'veterinario' => $ordem->tecnico,
            'owner_id' => $ordem->owner_id,
            'data_coleta' => $datas->data_coleta,
            'data_realizacao' => $datas->data_recebimento,
            'data_lab' => $datas->data_laboratorio,
            'codigo_busca' => Laudo::where('codigo_busca', $codigo)->exists() ? rand(100000000, 999999999) : $codigo,
            'observacao' => $request->obs,
            'conclusao' => $request->conclusao,
            'tipo' => $datas->tipo,
            'veterinario_id' => $order->id_tecnico,
            'ordem_id' => $ordem->id,
            'order_id' => $order->id,
        ];



        if ($laudo) {
            $laudo->update($laudoData);
        } else {
            $laudo = Laudo::create($laudoData);
            // Crie uma instância do escritor



            $content = 'https://i.locilab.com.br/' . $laudo->codigo_busca;
            // Crie uma instância do escritor
            $renderer = new ImageRenderer(
                new RendererStyle(400),
                new ImagickImageBackEnd()
            );
            $writer = new Writer($renderer);
            //Escreve o r code em algum diretório
            //                  codigo          arquivo saida
            $filePath = public_path('qrcodes/' . time() . '.png');
            $writer->writeFile($content, $filePath);

            $imageData = file_get_contents($filePath);
            $base64Image = base64_encode($imageData);



            $qrCode = ModelQrCode::create([
                'laudo_id' => $laudo->id,
                'qrcode' => $base64Image
            ]);
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
        $qrCode = ModelQrCode::where('laudo_id', $laudo->id)->first();

        return view('admin.ordem-servico.laudo', get_defined_vars());
    }
    public function gerarPdf($id)
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
        $qrCode = ModelQrCode::where('laudo_id', $laudo->id)->first();
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
        $certificadoPath = 'file://' . public_path('certificado/LOCI_BIOTECNOLOGIA_LTDA_18496213000111_1661426936642166100.pfx');
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

        $laudo->update([
            'pdf' => $filename
        ]);

        // Gera a resposta de download
        $path = Storage::disk('public')->path($filename);
        return response()->download($path, $filename);
    }

    public function preConfirm(Request $request)
    {
        $laudo = Laudo::find($request->laudo);
        $order = OrderRequest::find($laudo->order_id);
        $parceiro = Parceiro::where('nome', $order->parceiro)->first();

        return response()->json(['parceiro' => $parceiro, 'laudo' => $request->laudo], 200);
    }

    public function finalizar(Request $request)
    {
        $laudo = Laudo::find($request->laudo);
        $order = OrderRequest::find($laudo->order_id);
        $owner = Owner::find($laudo->owner_id);
        $parceiro = Parceiro::where('nome', $order->parceiro)->first();


        Mail::to($parceiro->email)->send(new EnviarLaudoMail($laudo->pdf));
        Mail::to($owner->email)->send(new EnviarLaudoMail($laudo->pdf));

        return response()->json([get_defined_vars()], 200);
    }
    public function gerarXML()
    {
        $doc = new DOMDocument();
        $doc->loadXML('<root></root>'); // Carregue o XML que deseja assinar

        // Crie uma nova instância de segurança
        $objDSig = new XMLSecurityDSig();

        // Use a canonização exclusiva (remove espaços em branco desnecessários, etc.)
        $objDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);

        // Assine usando SHA-1
        $objDSig->addReference(
            $doc,
            XMLSecurityDSig::SHA1,
            array('http://www.w3.org/2000/09/xmldsig#enveloped-signature'),
            array('force_uri' => true)
        );

        // Carregue a chave privada
        $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array('type' => 'private'));

        // Se você tiver uma chave de string, use isto para carregar a chave ao invés
        //$objKey->loadKey('-----BEGIN PRIVATE KEY-----...');

        // Se a chave privada tem uma passphrase, adicione-a.
        //$objKey->passphrase = 'passphrase';

        // Adicione a chave privada ao objeto de assinatura
        $objDSig->add509Cert(file_get_contents(storage_path('app/public.pem'))); // public.pem é a sua chave pública
        $objDSig->sign($objKey);

        // Anexe a assinatura ao XML
        $objDSig->appendSignature($doc->documentElement);

        // Salve o XML assinado
        $doc->save(storage_path('app/signed.xml')); // signed.xml é o seu XML assinado

        // Retorna o XML assinado como uma resposta
        return response($doc->saveXML())->header('Content-Type', 'text/xml');
    }

    public function verify($codigo)
    {
        $laudo = Laudo::where('codigo_busca', $codigo)->first();
        if ($laudo) {
            $animal = Animal::find($laudo->animal_id);
            $owner = Owner::find($laudo->owner_id);
            $datas = DataColeta::where('id_animal', $laudo->animal_id)->first();
            $tecnico = Tecnico::find($laudo->veterinario_id);
            $mae = Animal::with('alelos')->find($laudo->mae_id);
            $pai = Animal::with('alelos')->find($laudo->pai_id);
            return view('admin.ordem-servico.laudo', get_defined_vars());
        } else {
            return response()->json(['message' => 'Laudo não encontrado'], 404);
        }
    }

    public function downloadLaudo($id)
    {
        $laudo = Laudo::find($id);
        // dd($laudo);
        if ($laudo) {
            $pathToFile = storage_path('app/public/' . $laudo->pdf); // Obtém o caminho completo para o arquivo PDF
            // dd($pathToFile);
            // Verifica se o arquivo existe
            if (file_exists($pathToFile)) {
                return Response::download($pathToFile); // Faz o download do arquivo
            } else {
                return response()->json(['error' => 'Arquivo não encontrado.']);
            }
        } else {
            return response()->json(['error' => 'Laudo não encontrado.']);
        }
    }
}
