<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Ordem de serviço</title>
</head>

<body>
    <style>
        h5 {
            font-size: 14px;
            font-weight: bold;
        }
    </style>
    <page size="A4">
        <h5>Numero do pedido: {{ $ordem->order_id }}</h5>
        <h5>Proprietário: {{ $ordem->owner }}</h5>
        <h5>Data de pagamento:@if (isset($ordemServicos))
                {{ date('d/m/Y', strtotime($ordemServicos[0]->data_payment)) }}
            @else
                {{ date('d/m/Y', strtotime($ordemServico->data_payment)) }}
            @endif
        </h5>

        @if (isset($ordemServicos))
            @if (count($ordemServicos) > 0)
                <h5>Técnico: {{ $ordemServicos[0]->tecnico }}</h5>
            @else
                <h5>Nenhum técnico encontrado</h5>
            @endif
        @else
            <h5>Técnico: {{ $ordemServico->tecnico }}</h5>
        @endif
        <p>Dados dos envolvidos nos exames/lote</p>
        <hr>
        <table class="table table-alt">
            <thead>
                <tr>
                    <th scope="col">Código</th>
                    <th scope="col">Animal</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Rg pai/mae</th>
                    <th scope="col">Data de entrega</th>
                    <th scope="col">Identificação</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($ordemServicos))
                    @foreach ($ordemServicos as $item)
                        <tr>
                            <th scope="row">{{ $item->codlab }}</th>
                            <td>{{ $item->animal }}</td>
                            <td>{{ $item->tipo_exame }}</td>
                            <td>{{ $item->rg_pai ?? '' }}/ {{ $item->rg_mae ?? '' }}</td>
                            <td>{{ date('d/m/Y', strtotime($item->data)) }}</td>
                            <td>{{ $item->id_abccmm }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <th scope="row">{{ $ordemServico->codlab }}</th>
                        <td>{{ $ordemServico->animal }}</td>
                        <td>{{ $ordemServico->tipo_exame }}</td>
                        <td>{{ $ordemServico->rg_pai ?? '' }}/ {{ $ordemServico->rg_mae ?? '' }}</td>
                        <td>{{ date('d/m/Y', strtotime($ordemServico->data)) }}</td>
                        <td>{{ $ordemServico->id_abccmm }}</td>
                    </tr>
                @endif
       
            </tbody>
        </table>

    </page>

</body>

</html>
