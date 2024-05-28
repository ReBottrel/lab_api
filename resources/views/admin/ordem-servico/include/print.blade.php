<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Ordem de Serviço</title>
    <style>
        h5 {
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <page size="A4">
        <h5>Número do pedido: {{ $ordem->order_id }}</h5>
        <h5>Proprietário: {{ $ordem->owner }}</h5>
        <h5>Data de pagamento:
            @if (isset($ordemServicos) && count($ordemServicos) > 0 && isset($ordemServicos[0]->data_payment))
                {{ date('d/m/Y', strtotime($ordemServicos[0]->data_payment)) }}
            @else
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro 2001',
                            html: 'Data de pagamento não encontrada. <a href="{{route('ajuda.erro.2001')}}" target="_blank">Clique aqui para ajuda</a>',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.history.back();
                            }
                        });
                    });
                </script>
                <span>Data de pagamento não encontrada</span>
            @endif
        </h5>

        @if (isset($ordemServicos) && count($ordemServicos) > 0)
            <h5>Técnico: {{ $ordemServicos[0]->tecnico ?? '' }}</h5>
        @else
            <h5>Técnico: {{ $ordem->tecnico ?? 'Nenhum técnico encontrado' }}</h5>
        @endif

        <p>Dados dos envolvidos nos exames/lote</p>
        <hr>
        <table class="table table-alt">
            <thead>
                <tr>
                    <th scope="col">Código</th>
                    <th scope="col">Animal</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Rg pai/mãe</th>
                    <th scope="col">Data de entrega</th>
                    <th scope="col">Identificação</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($ordemServicos) && count($ordemServicos) > 0)
                    @foreach ($ordemServicos as $item)
                        <tr>
                            <th scope="row">{{ $item->codlab }}</th>
                            <td>{{ $item->animal }}</td>
                            <td>{{ $item->tipo_exame == 'PEGGN' ? 'EQUPEGGN' : $item->tipo_exame }}</td>
                            <td>{{ $item->rg_pai ?? '' }}/ {{ $item->rg_mae ?? '' }}</td>
                            <td>{{ date('d/m/Y', strtotime($item->data)) }}</td>
                            <td>{{ $item->id_abccmm }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <th scope="row">{{ $ordem->codlab ?? '' }}</th>
                        <td>{{ $ordem->animal ?? '' }}</td>
                        <td>{{ $ordem->tipo_exame == 'PEGGN' ? 'EQUPEGGN' : $ordem->tipo_exame ?? '' }}</td>
                        <td>{{ $ordem->rg_pai ?? '' }}/ {{ $ordem->rg_mae ?? '' }}</td>
                        <td>{{ isset($ordem->data) ? date('d/m/Y', strtotime($ordem->data)) : '' }}</td>
                        <td>{{ $ordem->id_abccmm ?? '' }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </page>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
