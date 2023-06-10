@extends('layouts.admin')

@section('title', 'Laudo')

@section('content')
    <div class="container">
        <div class="my-2">
            <h1 class="text-center fs-3"><strong>Laudo Veterinário</strong></h1>
        </div>
        <div class="card mb-3">
            <div class="informacoes">
                <div class="text-center text-decoration-underline my-3">
                    <h1 class="fs-4">Dados Relativos à Amostra</h1>
                </div>
                <div class="row">
                    <div class="col-6">
                        <p><strong>Nome do Animal Testado:</strong> [Nome do Animal Testado]</p>
                    </div>
                    <div class="col-4 offset-2">
                        <p><strong>Espécie:</strong> [Espécie do Animal]</p>
                    </div>
                    <div class="col-4">
                        <p><strong>Número do Registro:</strong> [Não informado]</p>
                    </div>
                    <div class="col-4">
                        <p><strong>Data de Nascimento:</strong> [dd/mm/yyyy]</p>
                    </div>
                    <div class="col-4">
                        <p><strong>Raça:</strong> [Raça do Animal]</p>
                    </div>
                    <div class="col-6">
                        <p><strong>Código Interno:</strong> [ABC00000]</p>
                    </div>
                    <div class="col-4 offset-2">
                        <p><strong>Sexo:</strong> [Sexo do Animal]</p>
                    </div>
                    <div class="col-6">
                        <p><strong>Proprietário:</strong> [Nome do Proprietário]</p>
                    </div>
                    <div class="col-4 offset-2">
                        <p><strong>Código de barras:</strong> [000000]</p>
                    </div>
                    <div class="col-12">
                        <p><strong>Endereço:</strong> [Endereço do Proprietário]</p>
                    </div>
                    <div class="col-4">
                        <p><strong>Tipo Amostra:</strong> [Tipo de amostra]</p>
                    </div>
                    <div class="col-4 ">
                        <p><strong>Data da Coleta:</strong> [dd/mm/yyyy]</p>
                    </div>
                    <div class="col-12">
                        <p><strong>Responsável pela Coleta/Registro Profissional ou CPF:</strong> [Nome do Responsável pela
                            Coleta]</p>
                    </div>
                    <div class="col-4">
                        <p><strong>Data do Recebimento:</strong> [dd/mm/yyyy]</p>
                    </div>
                    <div class="col-6">
                        <p><strong>Data de entrada na Área Técnica:</strong> [dd/mm/yyyy]</p>
                    </div>
                    <div class="col-12">
                        <p><strong>Observações:</strong> [Observações]</p>
                    </div>
                </div>
                <div class="text-center text-decoration-underline my-3">
                    <h1 class="fs-4">Dados Relativos ao Ensaio</h1>
                </div>
                <div class="row">
                    <div class="col-12">
                        <p><strong>Data da Realização:</strong> [dd/mm/yyyy]</p>
                    </div>
                    <div class="col-12">
                        <p><strong>Metodologia Utilizada:</strong> Identificação Genética e Pesquisa de Vínculo Genético
                            pela amplificação das regiões STRs pela técnica PCR e
                            detecção por eletroforese capilar em sistema automatizado por fluorescência laser-induzida
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="text-center my-3 text-decoration-underline">
                <h1 class="fs-4">Tabela de Resultados</h1>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered border-dark table-sm">
                    <thead>
                        <tr>
                            <th scope="col">Nome</th>
                            <th scope="col">OLINDA DA GROTA VIVA</th>
                            <th scope="col">SONHADOR REAL DE MAUÁ</th>
                            <th scope="col">IMPACTO DO LUAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">N Relatório de Ensaio</th>
                            <th scope="row">ECVP13-14209</th>
                            <th scope="row">LO22-68946</th>
                            <th scope="row">01516330.ECVP17-26251</th>
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
        </div>

        <div class="card mb-3">
            <div class="text-center my-3 text-decoration-underline">
                <h1 class="fs-4">Resultados</h1>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <p>
                            <strong>Conclusão:</strong> Conclui-se que o produto SONHADO REAL DE MAUÁ está qualificado pela
                            genitora OLINDA DA GROTA VIVA (149467) e está qualificado
                            pelo genitor IMPACTO DO LUAL (53753).
                        </p>
                    </div>
                    <div class="col-12">
                        <p>
                            <strong>Observações:</strong> O Resultado da anàlise de vinculo apresentado aqui foi definido
                            com base nos seguintes laudos:
                        </p>
                        <p>
                            GENITORA: animal OLINDA DA GROTA VIVA, número ECVP13-14209, emitido pelo laboratório Linhagen
                            em
                            16/05/2017.
                            FILHO(A): animal SONHADO REAL DE MAUÁ, número LO22-68946, emitido pelo laboratório Loci Genética
                            Laboratorial em 05/05/2022.
                            GENITOR: animal IMPACTO DO LUAL, número 01516330.ECVP17-26251, emitido pelo laboratório Linhagen
                            em 30/07/2021.
                            Esses laudos são de exclusiva responsabilidade dos laboratórios emissores.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endsection
