<?php

namespace App\Http\Controllers\Admin;


use DOMDocument;
use Dompdf\Dompdf;
use App\Models\User;
use App\Models\Laudo;
use App\Models\Owner;
use App\Models\Animal;
use App\Models\Result;
use GuzzleHttp\Client;
// use X509\CertificationPath;
use App\Models\Tecnico;
use BaconQrCode\Writer;
use App\Models\Parceiro;
use phpseclib\Crypt\RSA;
use phpseclib\File\ASN1;
use phpseclib\File\X509;
use App\Models\DnaVerify;
use App\Models\DataColeta;
use Spatie\PdfToImage\Pdf;
use App\Models\OrdemServico;
use App\Models\OrderRequest;
use Illuminate\Http\Request;
use App\Mail\EnviarLaudoMail;
use App\Models\AnimalToParent;
use BaconQrCode\Renderer\Image\Png;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\QrCode as ModelQrCode;
use BaconQrCode\Renderer\ImageRenderer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use RobRichards\XMLSecLibs\XMLSecurityKey;
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class LaudoController extends Controller
{

    public function __construct()
    {
        ini_set('max_execution_time', 600000);
    }
    public function index()
    {
        $laudos = Laudo::with('animal')->where('status', 1)->paginate();
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
            'ret' => $request->ret,
            'data_retificacao' => $request->data_ret,
        ];



        if ($laudo) {
            $laudo->update($laudoData);
        } else {
            $laudo = Laudo::create($laudoData);
            // Crie uma instância do escritor
            $content = 'https://i.locilab.com.br/validacao/' . $laudo->codigo_busca;
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
        $dna_verify = DnaVerify::where('animal_id', $animal->id)->latest('created_at')->first();
        $sigla = substr($animal->especies, 0, 3);
        $examType = substr($dna_verify->verify_code, 3, 2);
        $ordem = OrdemServico::where('order', $laudo->order_id)
            ->where('animal_id', $laudo->animal_id)
            ->latest()
            ->first();
        $pai = null;
        $mae = null;
        $relation = AnimalToParent::where('animal_id', $animal->id)->first();
        switch ($dna_verify->verify_code) {
            case $sigla . 'PD':
                if ($relation) {
                    if ($relation->register_pai) {
                        $pai = Animal::with('alelos')->where('number_definitive', $relation->register_pai)->first();
                    }
                    if (!$pai && $relation->pai_id) {
                        $pai = Animal::with('alelos')->find($relation->pai_id);
                    }
                }
                break;
            case $sigla . 'MD':
                if ($relation) {

                    if ($relation->register_mae) {
                        $mae = Animal::with('alelos')->where('number_definitive', $relation->register_mae)->first();
                    }
                    if (!$mae && $relation->mae_id) {
                        $mae = Animal::with('alelos')->find($relation->mae_id);
                    }
                }
                break;
            case $sigla . 'TR':
                if ($relation) {
                    if ($relation->register_pai) {
                        $pai = Animal::with('alelos')->where('number_definitive', $relation->register_pai)->first();
                    }
                    if (!$pai && $relation->pai_id) {
                        $pai = Animal::with('alelos')->find($relation->pai_id);
                    }

                    if ($relation->register_mae) {
                        $mae = Animal::with('alelos')->where('number_definitive', $relation->register_mae)->first();
                    }
                    if (!$mae && $relation->mae_id) {
                        $mae = Animal::with('alelos')->find($relation->mae_id);
                    }
                }
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
        $dna_verify = DnaVerify::where('animal_id', $animal->id)->latest('created_at')->first();
        $sigla = substr($animal->especies, 0, 3);
        $examType = substr($dna_verify->verify_code, 3, 2);
        $ordem = OrdemServico::where('order', $laudo->order_id)
            ->where('animal_id', $laudo->animal_id)
            ->latest()
            ->first();
        $pai = null;
        $mae = null;
        $relation = AnimalToParent::where('animal_id', $animal->id)->first();
        switch ($dna_verify->verify_code) {
            case $sigla . 'PD':
                if ($relation) {
                    if ($relation->register_pai) {
                        $pai = Animal::with('alelos')->where('number_definitive', $relation->register_pai)->first();
                    }
                    if (!$pai && $relation->pai_id) {
                        $pai = Animal::with('alelos')->find($relation->pai_id);
                    }
                }
                break;
            case $sigla . 'MD':
                if ($relation) {

                    if ($relation->register_mae) {
                        $mae = Animal::with('alelos')->where('number_definitive', $relation->register_mae)->first();
                    }
                    if (!$mae && $relation->mae_id) {
                        $mae = Animal::with('alelos')->find($relation->mae_id);
                    }
                }
                break;
            case $sigla . 'TR':
                if ($relation) {
                    if ($relation->register_pai) {
                        $pai = Animal::with('alelos')->where('number_definitive', $relation->register_pai)->first();
                    }
                    if (!$pai && $relation->pai_id) {
                        $pai = Animal::with('alelos')->find($relation->pai_id);
                    }

                    if ($relation->register_mae) {
                        $mae = Animal::with('alelos')->where('number_definitive', $relation->register_mae)->first();
                    }
                    if (!$mae && $relation->mae_id) {
                        $mae = Animal::with('alelos')->find($relation->mae_id);
                    }
                }
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
        $codlabMae = $mae ? substr($mae->codlab, 3) : 'N/A';
        $codlabPai = $pai ? substr($pai->codlab, 3) : 'N/A';
        $codlabAnimal = substr($animal->codlab, 3);
        $siglaPais = ($pai || $mae) ? 'VP' : 'AP';

        // Cria a string para a sigla representando as informações de Pai e/ou Mãe
        $codlabAnimal = str_replace(['/', '\\'], '_', $codlabAnimal);
        $codlabMae = str_replace(['/', '\\'], '_', $codlabMae);
        $codlabPai = str_replace(['/', '\\'], '_', $codlabPai);

        if ($siglaPais == 'AP') {
            $codlabMae = str_replace('N_A', '', $codlabMae);
            $codlabPai = str_replace('N_A', '', $codlabPai);
            $filename = "LO23-{$codlabAnimal}" . '.pdf'; // Remova os pontos aqui
        } else {
            $filename = "LO{$siglaPais}23-{$codlabMae}.{$codlabAnimal}.{$codlabPai}" . '.pdf';
        }

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
        $animal = Animal::with('alelos')->find($laudo->animal_id);
        $pai = Animal::with('alelos')->where('animal_name', $animal->pai)->first();
        $mae = Animal::with('alelos')->where('animal_name', $animal->mae)->first();
        $results = Result::where('ordem_servico', $laudo->ordem_id)->latest()->first();
        $user = User::where('id', $order->user_id)->first();
        $tecnico = Tecnico::where('professional_name', $order->technical_manager)->first();
        // dd($tecnico);
        // dd($user->email);
        if ($order->parceiro == 'ABCCMM') {
            $xml = $this->gerarXML($animal, $laudo, $order, $results, $pai, $mae, $owner, $tecnico);
            // Mail::to($user->email)->send(new EnviarLaudoMail($laudo->pdf));
            // Mail::to('laudosdna.lfda-mg@agro.gov.br')->send(new EnviarLaudoMail($laudo->pdf));
            return response()->json([$xml, $parceiro], 200);
        } else {
            Mail::to($parceiro->email)->send(new EnviarLaudoMail($laudo->pdf));
            Mail::to($user->email)->send(new EnviarLaudoMail($laudo->pdf));
            Mail::to('laudosdna.lfda-mg@agro.gov.br')->send(new EnviarLaudoMail($laudo->pdf));
            $laudo->update([
                'status' => 1
            ]);
            return response()->json([$laudo, $parceiro], 200);
        }
    }

    public function alteraStatus(Request $request)
    {
        $laudo = Laudo::find($request->laudo);
        $laudo->update([
            'status' => 1
        ]);
        return response()->json($laudo, 200);
    }

    public function gerarXML($animal, $laudo, $order, $results, $pai, $mae, $owner, $tecnico)
    {
        $microssatellites = ["AHT4", "AHT5", "ASB2", "ASB23", "HMS2", "HMS3", "HMS6", "HMS7", "HTG10", "HTG4", "HTG7", "VHL20"];
        $excluidos = str_split($results->excluido);
        $incluidos = str_split($results->incluido);

        $animalId = $this->removePrefix($animal->codlab);
        $paiId = $this->removePrefix($pai->codlab);
        $maeId = $this->removePrefix($mae->codlab);

        // dd($laudo);

        if ($pai == null && $mae == null) {
            $subtipo = 1;
        } else {
            $subtipo = 2;
        }

        // Cria sequências de animais
        $animalSequencesXml = "";
        foreach ($microssatellites as $microsatellite) {
            foreach ($animal->alelos as $alelo) {
                if ($alelo->marcador == $microsatellite) {
                    $marcador = $alelo->alelo1 . '/' . $alelo->alelo2;
                    $animalSequencesXml .= '<SEQUENCIA Microssatelite="' . $alelo->marcador . '" Marcador="' . $marcador . '" />';
                    break;
                }
            }
        }

        // Cria sequências para pai
        $seqXmlPai = "";
        for ($i = 0; $i < count($microssatellites); $i++) {
            $marcador = $pai->alelos[$i]->alelo1 . '/' . $pai->alelos[$i]->alelo2;
            $exclusao = ($excluidos[$i] == "P" || $excluidos[$i] == "MP") ? 0 : 1;
            $seqXmlPai .= '<SEQUENCIA Microssatelite="' . $microssatellites[$i] . '" Marcador="' . $marcador . '" Exclusao="' . $exclusao . '" />';
        }

        // Cria sequências para mãe
        $seqXmlMae = "";
        for ($i = 0; $i < count($microssatellites); $i++) {
            $marcador = $mae->alelos[$i]->alelo1 . '/' . $mae->alelos[$i]->alelo2;
            $exclusao = ($excluidos[$i] == "M" || $excluidos[$i] == "MP") ? 0 : 1;
            $seqXmlMae .= '<SEQUENCIA Microssatelite="' . $microssatellites[$i] . '" Marcador="' . $marcador . '" Exclusao="' . $exclusao . '" />';
        }

        // Determine se a paternidade e a maternidade são confirmadas
        $confirmaPaternidade = !in_array("P", $excluidos) && !in_array("MP", $excluidos) ? 1 : 0;
        $confirmaMaternidade = !in_array("M", $excluidos) && !in_array("MP", $excluidos) ? 1 : 0;

        $paiXml = '<PAI CodigoLaboratorio="' . $pai->identificador . '" ConfirmaPaternidade="' . $confirmaPaternidade . '">' . $seqXmlPai . '</PAI>';
        $maeXml = '<MAE CodigoLaboratorio="' . $mae->identificador . '" ConfirmaMaternidade="' . $confirmaMaternidade . '">' . $seqXmlMae . '</MAE>';
        $xml = '<?xml version="1.0" encoding="iso-8859-1" ?>
        <document>
          <CASO>
            <NUMERO><![CDATA[LOVP23-' . $maeId . '.' . $animalId . '.' . $paiId . ']]></NUMERO> 		
            <ANIMAL><![CDATA[' . $animal->animal_name . ']]></ANIMAL> 	
            <REGISTRO><![CDATA[0]]></REGISTRO> 		
            <DATACONCLUSAO><![CDATA[' . date('d/m/Y', strtotime($laudo->created_at)) . ']]></DATACONCLUSAO> 
            <LABORATORIO><![CDATA[18]]></LABORATORIO> 		
            <PROPRIETARIO><![CDATA[' . $order->creator_number . ']]></PROPRIETARIO>
            <TIPOEXAME><![CDATA[2]]></TIPOEXAME> 		
            <SUBTIPOEXAME><![CDATA[' . $subtipo . ']]></SUBTIPOEXAME> 		
            <TECNICO><![CDATA[' . $tecnico->parceiro_id . ']]></TECNICO> 		
            <DATACOLETA><![CDATA[' . $laudo->data_coleta . ']]></DATACOLETA> 	
            <TIPOMATERIAL><![CDATA[2]]></TIPOMATERIAL> 	
            <NOMEIMAGEM><![CDATA[' . $laudo->pdf . ']]></NOMEIMAGEM> 
            <OBSERVACOES><![CDATA[' . $laudo->observacao . ']]></OBSERVACOES> 
            <DATAENVIO><![CDATA[' . date('d/m/Y') . ']]></DATAENVIO>	
            <HORAENVIO><![CDATA[' . date('H:i') . ']]></HORAENVIO>
            <ROWIDANIMAL><![CDATA[' . $animal->register_number_brand . ']]></ROWIDANIMAL>
          </CASO>
   <REGISTRO CodigoLaboratorio="' . $animal->identificador . '">
    ' . $animalSequencesXml . '
  </REGISTRO>
          <VP>
            ' . $paiXml
            . $maeXml . '
        </VP>
        </document>';

        $animalId = substr($animal->codlab, 3);
        $xml = str_replace('﻿', '', $xml);
        $name = 'LOVP23-' . $animalId . '.xml';
        $saveXml = public_path('xml/' . $name);
        file_put_contents($saveXml, $xml);
        $pemContent = file_get_contents(public_path('certificado/certw.pem'));

        // Caminho para o arquivo PDF que você deseja converter
        $nomeArquivo = storage_path('app/public/' . $laudo->pdf);

        $pdf = file_get_contents($nomeArquivo);
        // dd($pdf);
        try {

            // $client = new \SoapClient('http://weblab.abccmm.org.br:8087/service.asmx?wsdl'); //produção
            $client = new \SoapClient('http://webserviceteste.abccmm.org.br:8083/service.asmx?wsdl'); //teste


            $params = array(
                'objBinaryCertificate' => $pdf,  // Binary data for certificate
                'strXmlData' => $xml  // XML data as a string
            );

            $response = $client->SetCertificate($params);
            return $response;
        } catch (\SoapFault $fault) {
            trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
        }
    }
    private function removePrefix($identificador)
    {
        $prefix = "EQU";
        if (strpos($identificador, $prefix) === 0) {
            return substr($identificador, strlen($prefix));
        }
        return $identificador;
    }

    public function enviaXML()
    {
        $signedXml = file_get_contents(public_path('xml/arquivo.xml'));
        $pemContent = file_get_contents(public_path('certificado/key.pem'));
        try {
            $options = array(
                'trace' => true,
                'exceptions' => true,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
            );

            $client = new \SoapClient('http://webserviceteste.abccmm.org.br:8083/service.asmx?wsdl', $options);

            $params = array(
                'objBinaryCertificate' => $pemContent,  // Binary data for certificate
                'strXmlData' => $signedXml  // XML data as a string
            );

            $response = $client->SetCertificate($params);

            print_r($response);
        } catch (\SoapFault $fault) {
            trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
        }
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

    public function verLaudoQrCode($code)
    {
        $laudo = Laudo::where('codigo_busca', $code)->first();
        $pathToFile = storage_path('app/public/' . $laudo->pdf);

        // verifica se o arquivo existe antes de retorná-lo
        if (file_exists($pathToFile)) {
            return response()->file($pathToFile, [
                'Content-Disposition' => 'inline; filename="' . $laudo->pdf . '"',
                'Content-Type' => 'application/pdf',
            ]);
        } else {
            return response()->json(['message' => 'Laudo não encontrado'], 404);
        }
    }

    public function searchByAnimal(Request $request)
    {
        if ($request->ajax()) {
            $busca = $request->busca;
            $animal = Animal::where('animal_name', 'LIKE', '%' . $busca . '%')->where('status', 9)->first();
            $laudos = Laudo::where('status', 1)->where('animal_id', $animal->id)
                ->get();

            $viewRender = view('admin.laudos.include.search', compact('laudos'))->render();

            return response()->json(['viewRender' => $viewRender]);
        }
    }
}
