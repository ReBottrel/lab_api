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
        margin-bottom: 113px;
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

    .float-right {
        float: right;
    }

    .float-left {
        float: left;
    }

    .d-block {
        display: block;
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
        padding: 0.3rem;
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
        margin: 0 auto;
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
                    <img width="66" height="66"
                        src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEIAAABCCAYAAADjVADoAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAA7EAAAOxAGVKw4bAAADRElEQVR4nN1b7W7EMAhLp3v/V95+7ZRrA9hg0m6WJq25hnxAiCHNMcb4HnkcRv1z+er5DEuO9x4qJ8RXptJ/xGtTOxWrYyxg9TuE80QggrxBHYv/0Ulg32dkevgeY20R6EBNoWRHvHbPZZlJgsajWBqRJrs1nV4OM3b4CETTmcFIfMMvunaNY4g0NclrhcIilCZ/W5s7lsbKR6AajnYhmaXs4hFn/A7GYqZeHa9cxiNKwoK6yO7R4QsgmeeJUKw9NsZg6lf64uKvxRptu0f3REQaqVqgbPd4jQ17tBjR7pEaz2vgzgvJF9zBKZh2zfHs2j49jSnodhnRRMz7fQWrwXb7D0quNRFRR6OJiZZUF7zUoYvs0kAGl52AiqUg7HMJL0OVWQ5WHaWFKXDpT5ShYjug8inbkeURmTS6qh00ZokU+lEW8QgLbJ3IUqzfs1trlBW7LA2GYrNZp2yWCq23em/1nIo+V8j6jLQHT8r13g0Z8NfQaPpu51jOkWZ4BJKVZupm5Fj1JBkqyxtHWadM56ND4grYcbxPuu7i/LvbcIFw8+pxPVIe9YeRm4LlI6pW0qlhz0d5RMotX8UaGQth6ntlc3nL+YUFJou9Mz+gynzBPGKONRgNPIE3SBXj8QhvYrKxxmPBEKqO7xzOstW/I/kV84sZ8+VGRGehyFkpw3gvk+JNhGcBzFFeF4OM2qVkRDwiyh9Yz3JnBvYjLWPORyARXEfukP3d66dXPv9dMFuEUoPoOmafPdkRZBmqWQirkSrK+YYIUfSp1EgF7VmtnWef7Ack3XzlY1dkJ0LVOcsXVHcbNCpe8giPPnvRJBsdZphp1nIYC3vzCMW5BpIz3Olb2LPZwzsNP/+vZoQw/RW3u8ST72tEMUelHxd3wJx9onT7TtC+YUy7RmUgHWas5Axw/5T3NaJ30Ox0tg0ErT4io4FOroDUvShGfafrjGj36bCEVB31na4VGEIU5Uaj9tPWsstHMO9HvKXCa8wcppJHoJEqEu12MVRTfuedrjtBt991p4vJNN+NVBjejawlrXgKtUs9bSLG0MUlFNR3uixknZ+naaQ+guW5RveJN/p5AfMO2p7bjyfe6VplrNEsNnsO8sYPZkLt9gC34esAAAAASUVORK5CYIIA" />
                    <p>
                        Utilize um leitor de QRCode ou acesse o site:
                        <u>https://i.locilab.com.br/validacao</u> e informe o código
                        <b>{{ $laudo->codigo_busca }} </b>para validar este laudo.
                    </p>

                </div>
            </div>


            <div class="text-center">
                <strong>RELATÓRIO DE ENSAIO</strong>
                <br>
                <strong>Verificação de Parentesco com Mãe e Pai</strong>
            </div>
            <div class="text-end">
                <span><strong>Relat. n</strong> {{ $laudo->id }}</span>
            </div>
            <div class="text-center my-1 text-decoration-underline">
                <strong>Dados Relativos à Amostra</strong>
            </div>
            <div class="informacoes">
                <div class="content_1">
                    <div class="">
                        <strong>Nome do Animal Testado:</strong>
                        <span>{{ $animal->animal_name }}</span>
                    </div>
                    <div class="">
                        <strong>Número do Registro:</strong>
                        <span>{{ $animal->number_definitive ?? 'Não informado' }}</span>
                    </div>
                    <div class="">
                        <strong>Raça:</strong>
                        <span>{{ $animal->breed }}</span>
                    </div>
                    <div class="">
                        <strong>Sexo:</strong>
                        <span>{{ $animal->sex }}</span>
                    </div>
                    <div class="">
                        <strong>Cód. Barras:</strong>
                        <span>{{ $animal->codlab }}</span>
                    </div>
                    <div class="">
                        <strong>Endereço:</strong>
                        <span>{{ $owner->address }}, {{ $owner->number }} {{ $owner->complement }} -
                            {{ $owner->city }} -
                            {{ $owner->state }}</span>
                    </div>

                </div>
                <div class="content_2">
                    <div class="">
                        <strong>Tipo Amostra:</strong>
                        <span>{{ $datas->tipo }}</span>
                    </div>
                    <div class="">
                        <strong>Espécie:</strong>
                        <span>{{ $animal->especies }}</span>
                    </div>
                    <div class="">
                        <strong>Data de Nascimento:</strong>
                        <span>{{ $animal->birth_date }}</span>
                    </div>
                    <div class="">
                        <strong>Código Interno:</strong>
                        <span>{{ $animal->codlab }}</span>
                    </div>
                    <div class="">
                        <strong>Proprietário:</strong>
                        <span>{{ $owner->owner_name }}</span>
                    </div>
                    <div class="">
                        <strong>Data da Coleta:</strong>
                        <span>{{ $animal->collect_date }}</span>
                    </div>
                </div>
            </div>
            <div>
                <div class="">
                    <strong>Responsável pela Coleta/Registro Profissional ou CPF:</strong>
                    <span>{{ $tecnico->professional_name }} - {{ $tecnico->document }}</span>
                </div>
                <div class="">
                    <strong>Data do Recebimento</strong>
                    <span>{{ $datas->data_recebimento }}</span>
                </div>
                <div class="">
                    <strong>Data de Entrada na Área Técnica:</strong>
                    <span>{{ $datas->data_laboratorio }}</span>
                </div>
                <div class="">
                    <strong>OBSERVAÇÃO:</strong>
                    <span>A amostragem foi de exclusiva responsabilidade do cliente.</span>
                </div>
            </div>
            <div class="text-center my-1 text-decoration-underline">
                <strong>Dados Relativos ao Ensaio</strong>
            </div>

            <div class="row">

                <div class="col-12">
                    <strong>Data da Realização:</strong>
                    <span>19/04/2022</span>
                </div>
                <div class="col-12">
                    <strong>Metodologia Utilizada:</strong>
                    <span>
                        Identificação Genética e Pesquisa de Vínculo Genético pela amplificação das regiões STRs
                        pela
                        técnica PCR e
                        detecção por eletroforese capilar em sistema automatizado por fluorescência laser-induzida
                    </span>
                </div>
            </div>
            <div class="text-center my-1 text-decoration-underline">
                <strong>Tabela de Resultados</strong>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered border-dark table-sm">
                    <thead>
                        <tr>
                            <th scope="col">Nome</th>
                            <th scope="col">REVISTA DA CANAÃ</th>
                            <th scope="col">teste data 2</th>
                            <th scope="col">RARO DA CANAÃ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>N Relatório de Ensaio</th>
                            <th>EQU84990</th>
                            <th>EQU84989</th>
                            <th>EQU84992</th>
                        </tr>
                        <tr>
                            <th>Microssatélites</th>
                            <th>Alelos</th>
                            <th>Alelos</th>
                            <th>Alelos</th>
                        </tr>
                        <tr>
                            <td>AHT4</td>
                            <td>H - M</td>
                            <td>K - M</td>
                            <td>K - N</td>
                        </tr>
                        <tr>
                            <td>AHT5</td>
                            <td>J - N</td>
                            <td>J - N</td>
                            <td>J - J</td>
                        </tr>
                        <tr>
                            <td>ASB2</td>
                            <td>C - I</td>
                            <td>I - M</td>
                            <td>* - *</td>
                        </tr>
                        <tr>
                            <td>ASB23</td>
                            <td>J - K</td>
                            <td>J - K</td>
                            <td>J - J</td>
                        </tr>
                        <tr>
                            <td>HMS2</td>
                            <td>H - I</td>
                            <td>I - K</td>
                            <td>K - L</td>
                        </tr>
                        <tr>
                            <td>HMS3</td>
                            <td>O - P</td>
                            <td>P - P</td>
                            <td>P - P</td>
                        </tr>
                        <tr>
                            <td>HMS6</td>
                            <td>O - P</td>
                            <td>O - O</td>
                            <td>O - P</td>
                        </tr>
                        <tr>
                            <td>HMS7</td>
                            <td>K - L</td>
                            <td>L - N</td>
                            <td>K - N</td>
                        </tr>
                        <tr>
                            <td>HTG10</td>
                            <td>* - *</td>
                            <td>M - O</td>
                            <td>* - *</td>
                        </tr>
                        <tr>
                            <td>HTG4</td>
                            <td>L - L</td>
                            <td>L - M</td>
                            <td>L - L</td>
                        </tr>
                        <tr>
                            <td>HTG7</td>
                            <td>O - O</td>
                            <td>O - O</td>
                            <td>O - O</td>
                        </tr>
                        <tr>
                            <td>VHL20</td>
                            <td>L - M</td>
                            <td>L - M</td>
                            <td>L - M</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div>
                <strong>Conclusão</strong>
                <span>
                    Conclui-se que o produto teste data 2 está qualificado pela
                    genitora OLINDA DA GROTA VIVA (149467) e está qualificado pelo genitor
                    IMPACTO DO LUAL (53753).
                </span>
            </div>
            <div>
                <strong>Observação</strong>
                <span>
                    O resultado da análise de vínculo genético apresentado aqui foi definido
                    com base nos seguintes laudos:
                </span>
            </div>
            <div>
                <span>
                    GENITORA: animal OLINDA DA GROTA VIVA, número ECVP13-14209, emitido pelo
                    laboratório Linhagen em 16/05/2017.
                </span>
                <br>
                <span>
                    FILHO(A): animal teste data 2, número LO22-68946, emitido pelo
                    laboratório Loci Genética Laboratorial em 05/05/2022. GENITOR: animal
                    IMPACTO DO LUAL, número 01516330.ECVP17-26251, emitido pelo laboratório
                    Linhagen em 30/07/2021.
                </span>
                <br>
                <span>
                    Esses laudos são de exclusiva responsabilidade dos laboratórios
                    emissores.
                </span>
            </div>
            <p>Lagoa Santa, 05 de maio de 2022.</p>
            <p>Conferido, liberado e assinado eletronicamente por:</p>
            <div class="assinatura text-center">
                <img width="100" height="30"
                    src="data:image/jpg;base64,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" />
                <hr>
                <p class="mb-0">Dra. RENATA BOTTREL</p>
                <p>Responsável Técnica <br> CR-Bio 37845/04-D</p>
            </div>
            <div class="text-center">
                <p>
                    Laboratório credenciado ao MAPA (Ministério da Agricultura, Pecuária e
                    Abastecimento), portaria Nº 112, de 20 de outubro de 2016.
                </p>
            </div>
        </div>
    </page>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
</body>

</html>
