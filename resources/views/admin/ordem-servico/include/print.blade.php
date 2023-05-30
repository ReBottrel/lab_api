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
             
    </style>
    <page size="A4">
        <h5>Numero de solicitação: {{ $ordem->id }}</h5>
        <h5>Proprietário: {{ $ordem->owner }}</h5>
        <h5>Data de solicitção: {{ date('d/m/Y', strtotime($ordem->created_at)) }}</h5>
        <p>Dados dos envolvidos nos exames/lote</p>
        <hr>
        <table class="table table-alt">
            <thead>
                <tr>
                    <th scope="col">Código</th>
                    <th scope="col">Animal</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Rg</th>
                    <th scope="col">Data de entrega</th>
                    <th scope="col">Identificação</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ordemServicos as $item)
                    <tr>
                        <th scope="row">{{ $item->codlab }}</th>
                        <td>{{ $item->animal }}</td>
                        <td>{{ $item->tipo_exame }}</td>
                        <td>{{ $item->rg ?? '' }}</td>
                        <td>{{ date('d/m/Y', strtotime($item->data)) }}</td>
                        <td>{{ $item->id_abccmm }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </page>

</body>

</html>
