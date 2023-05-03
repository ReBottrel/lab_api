<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vet/css/resenha.min.css') }}">
    <title>Document</title>
</head>

<body>
    <page size="A4">
        <div class="panel panel-default no-print d-flex">
            <div class="panel-heading no-print">
                <a title="Imprimir"><button class="btn btn-info btn-sm no-print" id="btnImprimir"><i class="fa fa-print"
                            aria-hidden="true"></i> Imprimir</button></a>
            </div>

        </div>

        <div id="conteudo">
            @if ($exame->type == 1 || $exame->type == 3 || $exame->type == 4 || $exame->type == 6)
                <table width="95%" align="center" border="1" style="height: 200px; ">
                    <tr>
                        <td width="70%"
                            style="margin-bottom:5px; font-size: 20px; text-align: center;  font-weight: bold;">
                            <p>
                                Requisição de Exame para Diagnóstico da Anemia Infecciosa Equina
                            </p>
                            @if ($exame->type == 1)
                                Pelo Método: (X) IDGA ( ) ELISA
                            @elseif($exame->type == 2)
                                Pelo Método: () IDGA (x) ELISA
                            @endif

                        </td>
                        <td style="vertical-align: text-top; padding: 5px;">
                            <p style="border: 1px solid black;"> Nº de Série: {{ $pedido->id }}</p>
                            <br /><br /><br /><br /><br />
                            <p style="text-align: center; font-size: 30px;">
                                {{ date('d/m/Y', strtotime($pedido->created_at)) }}</p>
                        </td>
                    </tr>
                </table>

                <table width="95%" align="center" border="1" class="table-laudo2" style="margin-top: 2px;">
                    <tr>
                        <td colspan="3">Proprietário do Animal:&nbsp;{{ $owner->owner_name }}</td>
                        <td>CPF/CNPJ::&nbsp;{{ $owner->document }}</td>
                    </tr>
                    <tr>
                        <td colspan="3">Endereço completo&nbsp;
                            {{ $owner->address }} -
                            {{ $owner->number }}
                            {{-- {{ $owner->complement }} - --}}


                        </td>
                        <td>
                            Fone: &nbsp;

                            {{ $owner->fone }}


                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">Cidade:&nbsp; - {{ $owner->city }}</td>
                        <td>UF:&nbsp; - {{ $owner->state }}</td>
                    </tr>
                    <tr>
                        <td colspan="3">Médico Veterinário Requisitante:&nbsp;{{ $veterinario->name }}</td>
                        <td>CRMV/UF: {{ $veterinario->crmv }} </td>
                    </tr>
                    <tr>
                        <td colspan="4">Endereço Completo:&nbsp;
                            {{ $veterinario->address }} -
                            {{ $veterinario->number }} -
                            {{-- {{ $veterinario->complement }} - --}}
                            {{ $veterinario->district }} -
                            {{ $veterinario->city }} -
                            {{ $veterinario->state }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">CPF:&nbsp;{{ $veterinario->cpf }}</td>
                        <td>E-mail:&nbsp;<a href="/cdn-cgi/l/email-protection" class="__cf_email__"
                                data-cfemail="721306171c161b1f171c061d321e1d111b1e13105c111d1f5c1000">{{ $veterinario->email }}</a>
                        </td>
                        <td>Fone:
                            {{ $veterinario->phone }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">Nome do animal:&nbsp;{{ $animal->animal_name }}</td>
                        <td rowspan="2" class="text-center">CLASSIFICAÇÃO¹:<br>
                            {{ $animal->classification }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">Espécie:&nbsp;{{ $animal->especies }}</td>
                        <td>Raça:&nbsp;{{ $animal->breed }}</td>
                    </tr>
                    <tr>
                        <td>Sexo:&nbsp;{{ $animal->sex }}</td>
                        <td>Idade:&nbsp;
                            {{ $animal->age }}
                        </td>
                        <td>Registro Nº/Marca:&nbsp;00000</td>
                        <td rowspan="2" class="text-center">Utilidade:<br>
                            {{ $animal->utility }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">Local onde se encontra:&nbsp;{{ $animal->animal_location }}</td>
                    </tr>
                    <tr>
                        <td colspan="3">Município/UF:&nbsp;
                            {{ $animal->city }} -
                            {{ $animal->state }}
                        </td>
                        <td rowspan="2" class="text-center">Nº de Equídeos Existentes:&nbsp;<br>
                            {{ $animal->number_existing_equines }}
                        </td>
                    </tr>
                </table>
                <p style="margin-bottom: 4px">
                <table width="95%" class="table-laudo2" border="0" align="center">
                    <tr>
                        <td style="border: 1px solid black;">Pelagem:&nbsp;{{ $animal->fur }} </td>
                        <td width="70%"></td>
                    </tr>
                </table>
                </p>
                <div class="image">
                    @foreach ($resenhas as $key => $resenha)
                        <div class="side-{{ $key }}">
                            <img src="{{ $resenha->localization }}" alt="">
                        </div>
                    @endforeach
                </div>

                <p style="margin-bottom: 4px">
                <table width="95%" class="table-laudo2" border="1" align="center">
                    <tr>
                        <td valign="baseline">Descrição do animal e observações</td>
                        <td width="70%">{{ $animal->description }}</td>
                    </tr>
                </table>
                </p>
                <table width="95%" class="table-laudo2" border="1" align="center">
                    <tr style="text-align: center;">
                        <td width="50%"><strong>Requisitante</strong></td>
                        <td width="50%"><strong>Uso exclusivo do Laboratório</strong></td>
                    </tr>
                    <tr>
                        <td valign="baseline">
                            <table>
                                <tr>
                                    <td style="text-align: justify;">A coleta da amostra e a resenha deste animal são de
                                        minha responsabilidade. Autorizo o laboratório a proceder o fracionamento deste
                                        material por mim coletado em prova e contraprova.</td>
                                </tr>
                                @php
                                    setlocale(LC_TIME, 'ptb');
                                    $date = \Carbon\Carbon::now();
                                    $textDate = $date->formatLocalized('%d de %B de %Y');
                                @endphp
                                <tr>
                                    <td style="text-align: center;"> {{ $owner->city }}, {{ $textDate }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;">Município e data da coleta</td>
                                </tr>
                                <tr>
                                    <td valign="bottom" height="60" style="text-align: center;"><br /><br />
                                        _________________________________________________________
                                        Assinatura e Carimbo do Médico Veterinário Requisitante

                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table cellpadding="10">
                                <tr>
                                    <td style="border-bottom-style: solid; border-bottom-width: 1px;">Nome/Lote do
                                        Antígeno:
                                        *********************************************************</td>
                                </tr>
                                <tr>
                                    <td style="border-bottom-style: solid; border-bottom-width: 1px;">Data do Resultado
                                        do
                                        Exame: ***************************************************
                                </tr>
                                <tr>
                                    <td style="border-bottom-style: solid; border-bottom-width: 1px;">
                                        <p style="margin-bottom: 4px">Resultado:</p>&nbsp;&nbsp;Relatório de ensaio
                                        emitido
                                        conforme Instrução Normativa nº 52, 26 de novembro de 2018 do MAPA.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-bottom-style: solid; border-bottom-width: 1px;">Validade do
                                        Resultado:
                                        ********************************************************</td>
                                </tr>
                                <tr>
                                    <td style="border-bottom-style: solid; border-bottom-width: 1px;">Nº Lacre da
                                        Contraprova: *****************************************************</td>
                                </tr>
                                <tr>
                                    <td height="40" valign="top">Assinatura e Carimbo do Responsável Técnico:
                                        *****************************************************</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="font-size: 9px;" align="center">
                            1 - JC: Jockey Club&nbsp;&nbsp;&nbsp;&nbsp;SH: Sociedade Hípica&nbsp;&nbsp;&nbsp;&nbsp;H:
                            Haras
                            FC: Fazenda de Criação&nbsp;&nbsp;&nbsp;&nbsp;UM: Unidade Militar&nbsp;&nbsp;&nbsp;&nbsp;CR:
                            Cancha Reta
                        </td>
                    </tr>

                </table>
                <footer id="rodape">
                    <table width='90%' align="center" style="font-size: 9px;">
                        <tr>
                            <td width='15%' align="left">1ª via – Cliente / 2ª via – Laboratório</td>
                            <td width='15%' align="right"> FOR.ATN. 16 v.9 </td>
                        </tr>
                    </table>
                </footer>
                <p style="margin-bottom: 4px">
                </p>

                <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
            @endif
            @if ($exame->type == 3 || $exame->type == 2 || $exame->type == 5 || $exame->type == 6)
                <div class="page-break"></div><br />
                <table width="95%" align="center" border="1" style="height: 200px; ">
                    <tr>
                        <td width="70%"
                            style="margin-bottom:5px; font-size: 20px; text-align: center;  font-weight: bold;">

                            <p>
                                Requisição de Exame para Diagnóstico </br>de Mormo
                            </p>
                            <!-- Comentado por solicitação da Camila no dia 23/10 devido a não disponibilização do exame FC mormo no sistema
            <p>
                Pelo Método: ( ) FC (X) ELISA
            </p>
            <p>
                TRÂNSITO INTERNACIONAL ( ) SIM ( ) NÃO
            </p>
             -->
                        </td>
                        <td style="vertical-align: text-top; padding: 5px;">
                            <p style="border: 1px solid black;"> Nº de Série:</p>
                            <br /><br /><br /><br /><br />
                            <p style="text-align: center; font-size: 30px;">
                                {{ date('d/m/Y', strtotime($pedido->created_at)) }}</p>
                        </td>
                    </tr>
                </table>

                <table width="95%" align="center" border="1" class="table-laudo2" style="margin-top: 2px;">
                    <tr>
                        <td colspan="3">Proprietário do Animal:&nbsp;{{ $owner->owner_name }}</td>
                        <td>CPF/CNPJ::&nbsp;{{ $owner->document }}</td>
                    </tr>
                    <tr>
                        <td colspan="3">Endereço completo&nbsp;
                            {{ $owner->address }} -
                            {{ $owner->number }}
                            {{-- {{ $owner->complement }} - --}}


                        </td>
                        <td>
                            Fone: &nbsp;

                            {{ $owner->fone }}


                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">Cidade:&nbsp; - {{ $owner->city }}</td>
                        <td>UF:&nbsp; - {{ $owner->state }}</td>
                    </tr>
                    <tr>
                        <td colspan="3">Médico Veterinário Requisitante:&nbsp;{{ $veterinario->name }}</td>
                        <td>CRMV/UF: {{ $veterinario->crmv }} </td>
                    </tr>
                    <tr>
                        <td colspan="4">Endereço Completo:&nbsp;
                            {{ $veterinario->address }} -
                            {{ $veterinario->number }} -
                            {{-- {{ $veterinario->complement }} - --}}
                            {{ $veterinario->district }} -
                            {{ $veterinario->city }} -
                            {{ $veterinario->state }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">CPF:&nbsp;{{ $veterinario->cpf }}</td>
                        <td>E-mail:&nbsp;<a href="/cdn-cgi/l/email-protection" class="__cf_email__"
                                data-cfemail="721306171c161b1f171c061d321e1d111b1e13105c111d1f5c1000">{{ $veterinario->email }}</a>
                        </td>
                        <td>Fone:
                            {{ $veterinario->phone }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">Nome do animal:&nbsp;{{ $animal->animal_name }}</td>
                        <td rowspan="2" class="text-center">CLASSIFICAÇÃO¹:<br>
                            {{ $animal->classification }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">Espécie:&nbsp;{{ $animal->especies }}</td>
                        <td>Raça:&nbsp;{{ $animal->breed }}</td>
                    </tr>
                    <tr>
                        <td>Sexo:&nbsp;{{ $animal->sex }}</td>
                        <td>Idade:&nbsp;
                            {{ $animal->age }}
                        </td>
                        <td>Registro Nº/Marca:&nbsp;00000</td>
                        <td rowspan="2" class="text-center">Utilidade:<br>
                            {{ $animal->utility }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">Local onde se encontra:&nbsp;{{ $animal->animal_location }}</td>
                    </tr>
                    <tr>
                        <td colspan="3">Município/UF:&nbsp;
                            {{ $animal->city }} -
                            {{ $animal->state }}
                        </td>
                        <td rowspan="2" class="text-center">Nº de Equídeos Existentes:&nbsp;<br>
                            {{ $animal->number_existing_equines }}
                        </td>
                    </tr>
                </table>
                <p style="margin-bottom: 4px">
                <table width="95%" class="table-laudo2" border="0" align="center">
                    <tr>
                        <td style="border: 1px solid black;">Pelagem:&nbsp;CASTANHA </td>
                        <td width="70%"></td>
                    </tr>
                </table>
                </p>
                <div class="image down">
                    @foreach ($resenhas as $key => $resenha)
                        <div class="side-{{ $key }}-down">
                            <img src="{{ $resenha->localization }}" alt="">
                        </div>
                    @endforeach
                </div>
                <p style="margin-bottom: 4px">
                <table width="95%" class="table-laudo2" border="1" align="center">
                    <tr style="vertical-align: text-top;">
                        <td>Descrição do animal e observações</td>
                        <td width="70%">{{ $animal->description }}</td>
                    </tr>
                </table>
                </p>
                <table width="95%" class="table-laudo2" border="1" align="center">
                    <tr style="text-align: center;">
                        <td><strong>Requisitante</strong></td>
                    </tr>
                    <tr>
                        @php
                            setlocale(LC_TIME, 'ptb');
                            $date = \Carbon\Carbon::now();
                            $textDate = $date->formatLocalized('%d de %B de %Y');
                        @endphp
                        <td valign="baseline">
                            <table>
                                <tr>
                                    <td style="text-align: justify;">A coleta da amostra e a resenha deste animal são
                                        de
                                        minha responsabilidade. Autorizo o laboratório a proceder o fracionamento deste
                                        material por mim coletado em prova e contraprova.</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;">{{ $owner->city }}, {{ $textDate }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;">Município e data da coleta</td>
                                </tr>
                                <tr>
                                    <td valign="bottom" height="60" style="text-align: center;"><br /><br /><br />
                                        _________________________________________________________<br>
                                        Assinatura e Carimbo do Médico Veterinário Requisitante
                                    </td>
                                </tr>
                            </table>
                        </td>

                    </tr>
                    <tr>
                        <td colspan="2" style="font-size: 9px;">

                            1 - JC: Jockey Club&nbsp;&nbsp;&nbsp;&nbsp;SH: Sociedade Hípica&nbsp;&nbsp;&nbsp;&nbsp;H:
                            Haras
                            FC: Fazenda de Criação&nbsp;&nbsp;&nbsp;&nbsp;UM: Unidade Militar&nbsp;&nbsp;&nbsp;&nbsp;CR:
                            Cancha Reta
                            <p style="margin-bottom:0px">
                                Obs: É obrigatória a entrega ao laboratório, juntamente com esta requisição e as
                                amostras
                                biológicas, os seguintes documentos:<br>
                                Tarjeta de Contraprova assinada pelo veterinário ou seu portador.
                            </p>
                        </td>
                    </tr>

                </table>
                <footer id="rodape">
                    <table width='90%' align="center" style="font-size: 9px;">
                        <tr>
                            <td width='15%' align="left">1ª via – Cliente / 2ª via – Laboratório</td>
                            <td width='15%' align="right"> FOR.ATN. 13 v.11 </td>
                        </tr>
                    </table>
                </footer>
                <p style="margin-bottom: 4px">
                </p>
            @endif
    </page>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#btnImprimir').on('click', function() {
                window.print();
            });
        });

        function generatePDF() {
            var doc = new jsPDF('a4');
            doc.addHTML(document.getElementById('conteudo'), function() {
                doc.save('sample-file.pdf');
            });
        }
    </script>
</body>

</html>
