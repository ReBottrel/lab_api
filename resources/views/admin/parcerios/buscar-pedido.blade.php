@extends('layouts.admin')


@section('content')
    <div class="card">
        <div class="card-header">
            Buscar Animal
        </div>
        <div class="card-body">
            <form action="" method="post">
                @csrf
                <div class="form-group mb-3">
                    <label for="name">Buscar animal</label>
                    <input type="text" name="name" id="name" class="form-control">
                </div>
                <div>
                    <button type="button" id="buscar" class="btn btn-primary">BUSCAR</button>
                </div>
            </form>
        </div>
        <div class="pedido">

        </div>
    @endsection

    @section('js')
        <script>
            $(document).ready(function() {
                // Definimos um objeto para mapear os códigos de status para suas descrições
                const statusMap = {
                    1: 'Aguardando amostra',
                    2: 'Amostra recebida',
                    3: 'Em análise',
                    4: 'Análise concluída',
                    5: 'Resultado disponível',
                    6: 'Análise reprovada',
                    7: 'Análise Aprovada',
                    8: 'Recoleta solicitada',
                    9: 'Amostra paga',
                    10: 'Pedido Concluído',
                    11: 'Aguardando Pagamento',
                    12: 'Morto'
                };

                $(document).ready(function() {
                    $('#buscar').click(function() {
                        let name = $('#name').val();
                        $.ajax({
                            url: '/buscar-pedido-parceiro-store',
                            type: 'POST',
                            data: {
                                name: name,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(data) {
                                console.log(data);
                                // Primeiro, criamos o cabeçalho da tabela
                                let tableContent = `
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nome</th>
                                <th scope="col">Data prevista</th>
                                <th scope="col">Laudo</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                                // Então, iteramos sobre cada pedido recebido
                                data.forEach(function(pedido) {
                                    // Aqui usamos o statusMap para obter a descrição do status baseado no código
                                    let statusDescription = statusMap[pedido
                                        .status] || 'Status desconhecido';

                                    let downloadLink =
                                        "{{ route('laudo.download', ':pdf') }}";
                                    downloadLink = downloadLink.replace(':pdf',
                                        pedido.pdf);

                                    tableContent += `
        <tr>
            <td>${pedido.id}</td>
            <td>${pedido.animal_name}</td>
            <td>${pedido.data}</td>
            <td><a href="${downloadLink}">Download</a></td>
            <td>${statusDescription}</td>
        </tr>
    `;
                                });


                                // Fechamos a tabela
                                tableContent += `
                        </tbody>
                    </table>
                `;

                                // E por fim, atualizamos o conteúdo do elemento que irá exibir a tabela
                                $('.pedido').html(tableContent);
                            }
                        });
                    });
                });

            });
        </script>
    @endsection
