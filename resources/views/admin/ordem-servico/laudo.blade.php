<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt" lang="pt">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>LAUDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('adm/assets/laudo/css/style.min.css') }}" />
</head>

<body>
    <div class="container my-4 d-flex">
        <div class="">
            <button class="btn btn-primary">ASSINAR E CONCLUIR</button>
        </div>
        <div class="mx-3">
            <button class="btn btn-secondary">IMPRIMIR</button>
        </div>
    </div>
    <div class="container my-3">
        <div class="row mb-5">
            <div class="col">
                <div class="d-flex align-items-center gap-3 mt-3">
                    <div>
                        <span>
                            <table>
                                <tr>
                                    <td>
                                        <img height="150" width="90"
                                            src="data:image/jpg;base64,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" />
                                    </td>
                                </tr>
                            </table>
                        </span>
                    </div>
                    <div>
                        <span>
                            <table>
                                <tr>
                                    <td>
                                        <img width="200"
                                            src="data:image/jpg;base64,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" />
                                    </td>
                                </tr>
                            </table>
                        </span>
                    </div>
                    <div class="text-center">
                        <strong>LOCI GENÉTICA LABORATORIAL</strong>
                        <p>
                            Rua Coronel Durães, 170, Bela Vista Lagoa Santa - MG - CEP:
                            33239-206 Tel: (31) 3681-4331 – e-mail: <a href="mailto:atendimento@locilab.com.br">
                                atendimento@locilab.com.br
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-4 text-center">
                <span class="d-flex justify-content-center">
                    <table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td>
                                <img width="66" height="66"
                                    src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEIAAABCCAYAAADjVADoAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAA7EAAAOxAGVKw4bAAADRElEQVR4nN1b7W7EMAhLp3v/V95+7ZRrA9hg0m6WJq25hnxAiCHNMcb4HnkcRv1z+er5DEuO9x4qJ8RXptJ/xGtTOxWrYyxg9TuE80QggrxBHYv/0Ulg32dkevgeY20R6EBNoWRHvHbPZZlJgsajWBqRJrs1nV4OM3b4CETTmcFIfMMvunaNY4g0NclrhcIilCZ/W5s7lsbKR6AajnYhmaXs4hFn/A7GYqZeHa9cxiNKwoK6yO7R4QsgmeeJUKw9NsZg6lf64uKvxRptu0f3REQaqVqgbPd4jQ17tBjR7pEaz2vgzgvJF9zBKZh2zfHs2j49jSnodhnRRMz7fQWrwXb7D0quNRFRR6OJiZZUF7zUoYvs0kAGl52AiqUg7HMJL0OVWQ5WHaWFKXDpT5ShYjug8inbkeURmTS6qh00ZokU+lEW8QgLbJ3IUqzfs1trlBW7LA2GYrNZp2yWCq23em/1nIo+V8j6jLQHT8r13g0Z8NfQaPpu51jOkWZ4BJKVZupm5Fj1JBkqyxtHWadM56ND4grYcbxPuu7i/LvbcIFw8+pxPVIe9YeRm4LlI6pW0qlhz0d5RMotX8UaGQth6ntlc3nL+YUFJou9Mz+gynzBPGKONRgNPIE3SBXj8QhvYrKxxmPBEKqO7xzOstW/I/kV84sZ8+VGRGehyFkpw3gvk+JNhGcBzFFeF4OM2qVkRDwiyh9Yz3JnBvYjLWPORyARXEfukP3d66dXPv9dMFuEUoPoOmafPdkRZBmqWQirkSrK+YYIUfSp1EgF7VmtnWef7Ack3XzlY1dkJ0LVOcsXVHcbNCpe8giPPnvRJBsdZphp1nIYC3vzCMW5BpIz3Olb2LPZwzsNP/+vZoQw/RW3u8ST72tEMUelHxd3wJx9onT7TtC+YUy7RmUgHWas5Axw/5T3NaJ30Ox0tg0ErT4io4FOroDUvShGfafrjGj36bCEVB31na4VGEIU5Uaj9tPWsstHMO9HvKXCa8wcppJHoJEqEu12MVRTfuedrjtBt991p4vJNN+NVBjejawlrXgKtUs9bSLG0MUlFNR3uixknZ+naaQ+guW5RveJN/p5AfMO2p7bjyfe6VplrNEsNnsO8sYPZkLt9gC34esAAAAASUVORK5CYIIA" />
                            </td>
                        </tr>
                    </table>
                </span>
                <p>
                    Utilize um leitor de QRCode ou acesse o site:
                    <u>https://i.locilab.com.br/validacao</u> e informe o código
                    <b>Mpbisd4kVwP1 </b>para validar este laudo.
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-6 offset-3 text-center">
                <h5>
                    <strong>RELATÓRIO DE ENSAIO</strong>
                </h5>
                <h6>
                    <strong>Verificação de Parentesco com Mãe e Pai</strong>
                </h6>
            </div>
            <div class="col-3 text-end">
                <br>
                <span><strong>Relat. n</strong> 68947.68946.52503</span>
            </div>
        </div>
        <h6 class="text-center my-3 text-decoration-underline"><strong>Dados Relativos à Amostra</strong></h6>
        <div class="row ">
            <div class="col-5">
                <strong>Nome do Animal Testado:</strong>
                <span>teste data 2</span>
            </div>
            <div class="col-4 offset-3">
                <strong>Espécie:</strong>
                <span>Equina</span>
            </div>
            <div class="col-4">
                <strong>Número do Registro:</strong>
                <span>Não informado</span>
            </div>
            <div class="col-4">
                <strong>Data de Nascimento:</strong>
                <span>19/02/2022</span>
            </div>
            <div class="col-4">
                <strong>Raça:</strong>
                <span>MANGALARGA MARCHADOR</span>
            </div>
            <div class="col-4">
                <strong>Código Interno:</strong>
                <span>EQU68946</span>
            </div>
            <div class="col-4 offset-4">
                <strong>Sexo:</strong>
                <span>Macho</span>
            </div>
            <div class="col-4">
                <strong>Proprietário:</strong>
                <span>FELIPE DA CRUZ M</span>
            </div>
            <div class="col-4 offset-4">
                <strong>Cód. Barras:</strong>
                <span>934918</span>
            </div>
            <div class="col-12">
                <strong>Endereço:</strong>
                <span>Rua teste, 104 Piraquara - PR</span>
            </div>
            <div class="col-4">
                <strong>Tipo Amostra:</strong>
                <span>Pelo</span>
            </div>
            <div class="col-4">
                <strong>Data da Coleta:</strong>
                <span>15/03/2022</span>
            </div>
            <div class="col-12">
                <strong>Responsável pela Coleta/Registro Profissional ou CPF:</strong>
                <span>FELIPE CRUZ - CRMV - 827.224.987-15</span>
            </div>
            <div class="col-4">
                <strong>Data do Recebimento</strong>
                <span>23/02/2022</span>
            </div>
            <div class="col-4 offset-4">
                <strong>Data de Entrada na Área Técnica:</strong>
                <span>13/04/2022</span>
            </div>
            <div class="col-12">
                <strong>OBSERVAÇÃO:</strong>
                <span>A amostragem foi de exclusiva responsabilidade do cliente.</span>
            </div>
        </div>
        <h6 class="text-center my-3 text-decoration-underline"><strong>Dados Relativos ao Ensaio</strong></h6>
        <div class="row">

            <div class="col-12">
                <strong>Data da Realização:</strong>
                <span>19/04/2022</span>
            </div>
            <div class="col-12">
                <strong>Metodologia Utilizada:</strong>
                <span>
                    Identificação Genética e Pesquisa de Vínculo Genético pela amplificação das regiões STRs pela
                    técnica PCR e
                    detecção por eletroforese capilar em sistema automatizado por fluorescência laser-induzida
                </span>
            </div>
        </div>
        <h6 class="text-center mt-3 text-decoration-underline"><strong>Tabela de Resultados</strong></h6>
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
                        <th scope="row">N Relatório de Ensaio</th>
                        <th scope="row">EQU84990</th>
                        <th scope="row">EQU84989</th>
                        <th scope="row">EQU84992</th>
                    </tr>
                    <tr>
                        <th scope="row">Microssatélites</th>
                        <th scope="row">Alelos</th>
                        <th scope="row">Alelos</th>
                        <th scope="row">Alelos</th>
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
        <div class="mb-3">
            <strong>Observação</strong>
            <span>
                O resultado da análise de vínculo genético apresentado aqui foi definido
                com base nos seguintes laudos:
            </span>
        </div>
        <div class="mb-3">
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
        <p>
            Conferido, liberado e assinado eletronicamente por:
        </p>
        <div class="row justify-content-center">
            <div class="col-4 text-center my-3">
                <hr>
                <p class="mb-0">Dra. RENATA BOTTREL</p>
                <p>Responsável Técnica <br> CR-Bio 37845/04-D</p>

            </div>
        </div>
        <div class="text-center">
            <p>
                Laboratório credenciado ao MAPA (Ministério da Agricultura, Pecuária e
                Abastecimento), portaria Nº 112, de 20 de outubro de 2016.
            </p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
</body>

</html>
