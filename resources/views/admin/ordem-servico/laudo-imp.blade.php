<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt" lang="pt">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>LAUDO</title>
</head>
<style>
    * {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;

    }

    a {
        text-decoration: none;
        color: #000;
    }

    strong {
        font-size: 0.6rem;
    }

    span,
    p {
        color: #000;
        font-size: 0.6rem;
    }

    /* BootStrap  */
    .text-center {
        text-align: center;
    }


    .container {
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
        margin-right: auto;
        margin-left: auto;
    }

    @media (min-width: 576px) {
        .container {
            max-width: 540px;
        }
    }

    @media (min-width: 768px) {
        .container {
            max-width: 720px;
        }
    }

    @media (min-width: 992px) {
        .container {
            max-width: 960px;
        }
    }

    @media (min-width: 1200px) {
        .container {
            max-width: 1140px;
        }
    }



    page {
        background: white;
        display: block;
        margin: 0 auto;
        margin-bottom: 0.5cm;
        box-shadow: 0 0 0.5cm rgba(0, 0, 0, 0.5);
    }

    page[size="A4"] {
        width: 21cm;

    }

    @page {
        margin: 0em;
        margin-left: 0.5em;
        margin-right: 0em;
        margin-top: 0em;

    }

    .cabecalho {
        width: 100%;
        position: relative;
    }

    .logo {
        width: 200px;
        position: absolute;
        top: 0;
        left: 100px;
    }

    .info {
        position: absolute;
        top: 25px;
        left: 240px;
        width: 265px;
    }

    .qr {
        width: 230px;
        position: absolute;
        top: 0;
        right: 0;
        text-align: center;
    }

    .qr img {
        padding-bottom: 10px;
        text-align: center;
    }

    .btn {
        display: inline-block;
        font-weight: 400;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        user-select: none;
        border: 1px solid transparent;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: 0.25rem;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .btn-primary {
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-secondary {
        color: #fff;
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .btn-success {
        color: #fff;
        background-color: #28a745;
        border-color: #28a745;
    }

    .btn-danger {
        color: #fff;
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .mx {
        margin-left: 10px;
        margin-right: 10px;
    }

    .my-1 {
        margin-top: 0.25rem !important;
        margin-bottom: 0.25rem !important;
    }

    .my-2 {
        margin-top: 0.5rem !important;
        margin-bottom: 0.5rem !important;
    }

    .my-3 {
        margin-top: 1rem !important;
        margin-bottom: 1rem !important;
    }

    .my-4 {
        margin-top: 1.5rem !important;
        margin-bottom: 1.5rem !important;
    }

    .my-5 {
        margin-top: 3rem !important;
        margin-bottom: 3rem !important;
    }

    .my-6 {
        margin-top: 4rem !important;
        margin-bottom: 4rem !important;
    }

    .my-7 {
        margin-top: 5rem !important;
        margin-bottom: 5rem !important;
    }

    .my-8 {
        margin-top: 6rem !important;
        margin-bottom: 6rem !important;
    }

    .mt-8 {
        margin-top: 6rem !important;
    }

    .text-end {
        text-align: right !important;
    }

    .text-decoration-underline {
        text-decoration: underline;
    }

    .position-relative {
        position: relative !important;
    }

    .informacoes {
        width: 100%;
        position: relative;
        margin-bottom: 75px;
    }

    .content_1 {
        position: absolute;
        top: 0;
        left: 0;
    }

    .content_2 {
        position: absolute;
        top: 0;
        right: 0;
    }

    .content_3 {
        position: relative;
    }

    .float-right {
        float: right;
    }

    .float-left {
        float: left;
    }

    .d-block {
        display: block;
    }

    .rodape {
        margin-top: 10px;
        width: 100%;
    }

    .rodape span {
        display: inline-block
    }

    .table {
        width: 100%;
        margin-bottom: .6rem;
        color: #212529;
        border-collapse: collapse;
    }

    .table td,
    .table tbody td {
        /* padding: 0.75rem; */
        text-align: center;
        vertical-align: top;
        border-top: 1px solid #dee2e6;
    }

    .table tbody tr:nth-child(odd) {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #dee2e6;
    }

    .table-bordered {
        border: 1px solid #dee2e6;
    }

    .table-bordered th,
    .table-bordered thead th {
        border: 1px solid #dee2e6;
    }

    .table-bordered td,
    .table-bordered tbody td {
        border: 1px solid #dee2e6;
    }

    .table-bordered tbody tr:nth-child(odd) {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .border-dark {
        border-color: #343a40 !important;
    }

    .table-sm {
        font-size: 0.6rem;
        /* Reduz o tamanho da fonte */
    }

    .table-sm th,
    .table-sm td {
        padding: 0.1rem;
    }

    .table-responsive {
        display: block;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        -ms-overflow-style: -ms-autohiding-scrollbar;
    }

    .assinatura {
        width: 200px;
        margin: 50px auto 0 auto;
        border-bottom: #000 1px solid;
        position: relative;
    }

    .assinatura img {
        position: absolute;
        height: 40px;
        top: -30px;
        transform: translateX(-50%);
        left: 50%;
    }

    @media (max-width: 575.98px) {
        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            -ms-overflow-style: -ms-autohiding-scrollbar;
        }

        .table-responsive>.table {
            margin-bottom: 0;
        }

        .table-responsive>.table>thead>tr>th,
        .table-responsive>.table>tbody>tr>th,
        .table-responsive>.table>tfoot>tr>th,
        .table-responsive>.table>thead>tr>td,
        .table-responsive>.table>tbody>tr>td,
        .table-responsive>.table>tfoot>tr>td {
            white-space: nowrap;
        }
    }

    #animalinfo span {
        padding: 0;
        margin: 0;
        line-height: 0;
    }
</style>

<body>
    {{-- <div class="container my-4 d-flex">
        <div class="mx">
            <button class="btn btn-primary">ASSINAR E CONCLUIR</button>
        </div>
        <div class="mx" style="float: left">
            <button class="btn btn-secondary">IMPRIMIR</button>
        </div>
    </div> --}}
    <page size="A4">
        <div class="container my-3">
            <div class="row cabecalho mb-5">

                <div class="iso">
                    <img
                        src="data:image/jpg;base64,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" />
                </div>
                <div class="logo">
                    <img
                        src="data:image/jpg;base64,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" />
                </div>
                <div class="info">
                    <strong>LOCI GENÉTICA LABORATORIAL</strong>
                    <p>
                        Rua Coronel Durães, 170, Bela Vista Lagoa Santa - MG - CEP:
                        33239-206 Tel: (31) 3681-4331 – e-mail: atendimento@locilab.com.br
                    </p>
                </div>
                <div class="qr">
                    <img width="66" height="66" src="data:image/png;base64,{{ $qrCode->qrcode ?? '' }}" />
                    <p>
                        Utilize um leitor de QRCode ou acesse o site:
                        <u>https://i.locilab.com.br/validacao/{{ $laudo->codigo_busca }}</u>
                        para validar este laudo.
                    </p>

                </div>
            </div>


            <div class="text-center">
                <strong>RELATÓRIO DE ENSAIO</strong>
                <br>
                <strong>@if($examType == 'TR') Verificação de Parentesco com Mãe e Pai @elseif($examType == 'MD') Verificação de Parentesco com Mãe @elseif($examType == 'PD') Verificação de Parentesco com Pai @elseif($examType == 'GN') Genotipagem @endif</strong>
            </div>
            <div class="text-end">
                <span><strong>Relat. n</strong>
                    @if ($mae != null)
                        {{ substr($mae->codlab, 3) }}.
                    @endif
                    {{ substr($animal->codlab, 3) }}. @if ($pai != null)
                        {{ substr($pai->codlab, 3) }}
                    @endif
                </span>
            </div>
            <div class="text-center my-1 text-decoration-underline">
                <strong>Dados Relativos à Amostra</strong>
            </div>
            <div class="informacoes">
                <div class="content_1">
                    <p>
                        <strong>Nome do Animal Testado:</strong>
                        <span>{{ $animal->animal_name }}</span>
                        <br>
                        <strong>Número do Registro:</strong>
                        <span>{{ $animal->number_definitive ?? 'Não informado' }}</span>
                        <br>
                        <strong>Raça:</strong>
                        <span>{{ $animal->breed ?? 'Não informado' }}</span>
                        <br>
                        <strong>Sexo:</strong>
                        <span>{{ $animal->sex ?? 'Não informado' }}</span>
                        <br>
                        <strong>Cód. Barras:</strong>
                        <span>{{ $ordem->bar_code ?? 'Não informado' }}</span>
                        <br>
                        <strong>Endereço:</strong>
                        <span>{{ $owner->address ?? 'Não informado' }}, {{ $owner->number ?? 'Não informado' }} <br>
                            {{ $owner->complement }} - 
                            {{ $owner->city ?? 'Não informado' }} -
                            {{ $owner->state ?? 'Não informado' }}
                        </span>
                    </p>
                </div>
                <div class="content_2">
                    <p>
                        <strong>Tipo Amostra:</strong>
                        <span>{{ $datas->tipo ?? 'Não informado' }}</span>
                        <br>
                        <strong>Espécie:</strong>
                        <span>{{ $animal->especies ?? 'Não informado' }}</span>
                        <br>
                        <strong>Data de Nascimento:</strong>
                        <span>{{ date( 'd/m/Y' , strtotime($animal->birth_date)) ?? 'Não informado' }}</span>
                        <br>
                        <strong>Código Interno:</strong>
                        <span>{{ $animal->codlab ?? 'Não informado' }}</span>
                        <br>
                        <strong>Proprietário:</strong>
                        <span>{{ $owner->owner_name ?? 'Não informado' }}</span>
                        <br>
                        <strong>Data da Coleta:</strong>
                        <span>{{ $datas->data_coleta ?? 'Não informado' }}</span>
                    </p>
                </div>
            </div>
            <div class="content_3">
                <p>
                    <strong>Responsável pela Coleta/Registro Profissional ou CPF:</strong>
                    <span>{{ $tecnico->professional_name }} - {{ $tecnico->document }}</span>
                    <br>
                    <strong>Data do Recebimento</strong>
                    <span>{{ $datas->data_recebimento }}</span>
                    <br>
                    <strong>Data de Entrada na Área Técnica:</strong>
                    <span>{{ date( 'd/m/Y' , strtotime($ordem->data_bar)) }}</span>
                    <br>
                    <strong>OBSERVAÇÃO:</strong>
                    <span>A amostragem foi de exclusiva responsabilidade do cliente.</span>
                </p>
            </div>

            <div class="text-center my-1 text-decoration-underline">
                <strong>Dados Relativos ao Ensaio</strong>
            </div>

            <div class="content_4">
                <p>
                    <strong>Data da Realização:</strong>
                    <span>{{date( 'd/m/Y' , strtotime($ordem->data_analise)) }}</span>
                    <br>
                    <strong>Metodologia Utilizada:</strong>
                    <span>
                        Identificação Genética e Pesquisa de Vínculo Genético pela amplificação das regiões STRs
                        pela
                        técnica PCR e
                        detecção por eletroforese capilar em sistema automatizado por fluorescência laser-induzida
                    </span>
                </p>
            </div>
            <div class="text-center my-1 text-decoration-underline">
                <strong>Tabela de Resultados</strong>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered border-dark table-sm">
                    <thead>
                        <tr>
                            <th scope="col">Nome</th>
                            @if ($mae != null)
                                <th scope="col">
                                    {{ $mae->animal_name }}
                                </th>
                            @endif
                            <th scope="col">{{ $animal->animal_name }}</th>
                            @if ($pai != null)
                                <th scope="col">

                                    {{ $pai->animal_name }}

                                </th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>N Relatório de Ensaio</th>
                            @if ($mae != null)
                                <th>
                                    {{ $mae->identificador }}
                                </th>
                            @endif
                            <th>{{ $animal->identificador }}</th>
                            @if ($pai != null)
                                <th>
                                    {{ $pai->identificador }}
                                </th>
                            @endif
                        </tr>
                        <tr>
                            <th>Microssatélites</th>
                            @if ($mae != null)
                                <th>Alelos</th>
                            @endif
                            <th>Alelos</th>
                            @if ($pai != null)
                                <th>Alelos</th>
                            @endif
                        </tr>
                        @foreach ($animal->alelos as $key => $item)
                            <tr>
                                <td>{{ $item->marcador }}</td>
                                @if ($mae != null)
                                    <td>

                                        @if ($mae->alelos[$key]->alelo1 == '')
                                            *
                                        @else
                                            {{ $mae->alelos[$key]->alelo1 }}
                                            @endif - @if ($mae->alelos[$key]->alelo2 == '')
                                                *
                                            @else
                                                {{ $mae->alelos[$key]->alelo2 }}
                                            @endif

                                    </td>
                                @endif
                                <td>
                                    @if ($item->alelo1 == '')
                                        *
                                    @else
                                        {{ $item->alelo1 }}
                                        @endif - @if ($item->alelo2 == '')
                                            *
                                        @else
                                            {{ $item->alelo2 }}
                                        @endif
                                </td>
                                @if ($pai != null)
                                    <td>

                                        @if ($pai->alelos[$key]->alelo1 == '')
                                            *
                                        @else
                                            {{ $pai->alelos[$key]->alelo1 }}
                                            @endif - @if ($pai->alelos[$key]->alelo2 == '')
                                                *
                                            @else
                                                {{ $pai->alelos[$key]->alelo2 }}
                                            @endif

                                    </td>
                                @endif
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <div>
                <strong>Conclusão</strong>
                <span>
                    {{ $laudo->conclusao }}
                </span> <br>
                <span>As opiniões e interpretações expressas acima não fazem parte do escopo de acreditação deste
                    laboratório</span>
            </div>
            <div>
                <strong>Observação</strong>
                <span>
                    O resultado da análise de vínculo genético apresentado aqui foi definido
                    com base nos seguintes laudos:
                </span>
            </div>
            <div id="animalinfo mb-1">
                <p class="spn">
                    @if ($mae != null)
                        <span>
                            GENITORA: animal {{ $mae->animal_name }}, número {{ $mae->identificador }}, emitido pelo laboratório
                            {{ $mae->alelos[0]->lab }} em {{ date('d/m/Y', strtotime($mae->alelos[0]->data)) }}.
                        </span>
                    @endif
                    <br>
                    <span>
                        FILHO(A): animal {{ $animal->animal_name }}, número {{ $animal->identificador }}, emitido pelo laboratório
                        {{ $animal->alelos[0]->lab }} em {{ date('d/m/Y', strtotime($animal->alelos[0]->data)) }}.

                    </span>
                    @if ($pai != null)
                        <br>
                        <span>
                            GENITOR: {{ $pai->animal_name }}, número {{ $pai->identificador }},
                            emitido pelo laboratório {{ $pai->alelos[0]->lab }} em
                            {{ date('d/m/Y', strtotime($pai->alelos[0]->data)) }}.
                        </span>
                    @endif

                </p>
            </div>
            <div>
                <span>
                    Esses laudos são de exclusiva responsabilidade dos laboratórios
                    emissores.
                    <br>
                    {{ $laudo->observacao }}
                </span>
            </div>

            @php
                setlocale(LC_TIME, 'ptb');
                $date = \Carbon\Carbon::now();
                $textDate = $date->formatLocalized('%d de %B de %Y');
            @endphp
            <p>Lagoa Santa, {{ $textDate }}</p>
            <p>Conferido, liberado e assinado eletronicamente por:</p>
            <div class="assinatura text-center">
                <img
                    src="data:image/png;base64,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" />
            </div>
            <div class="text-center">
                <p class="mb-0">Dra. RENATA BOTTREL</p>
                <p>Responsável Técnica <br> CR-Bio 37845/04-D</p>
            </div>
            <div class="my-1">
                <p>Este relatório é válido apenas para a amostra analisada.</p>
                <p>
                    Laboratório credenciado ao MAPA (Ministério da Agricultura, Pecuária e
                    Abastecimento), portaria Nº 112, de 20 de outubro de 2016.
                </p>
            </div>
            <div class="rodape">
                <span style="float: left;">@if($examType == 'TR') FOR.LRE.02 v.03 @elseif($examType == 'MD') FOR.LRE.02 v.01 @elseif($examType == 'PD') FOR.LRE.01 v.02 @elseif($examType == 'GN') FOR.LRE.01 v.03 @endif</span>
                <span style="float: none; margin: 0 auto; text-align:center;">Documento assinado eletronicamente por
                    LOCI BIOTECNOLOGIA LTDA, em {{ date('d/m/Y H:i:s') }}</span>
                <span style="float: right;">Página 1 de 1</span>
            </div>
        </div>
    </page>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
</body>

</html>
