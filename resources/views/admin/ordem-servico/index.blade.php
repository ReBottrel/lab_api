@extends('layouts.admin')
@section('content')
    <div class="container">
        <div>
            <h3>Todas a ordens de serviço</h3>
        </div>
        <div class="row">
            <div class="mb-3 col-6">
                <label for="exampleFormControlInput1" class="form-label">Buscar por proprietário ou por numero</label>
                <input type="text" class="form-control" id="busca">
            </div>
            <div class="mb-3 col-4">
                <label for="exampleFormControlInput1" class="form-label">Buscar por codlab</label>
                <input type="text" class="form-control" id="codlab">
            </div>
            <div class="col-2 mt-4">
                <button class="btn btn-primary" id="buscar-codlab" style="margin-top: 6px;">BUSCAR CODLAB</button>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Numero/Pedido</th>
                    <th scope="col">Proprietario</th>
                    <th>Data de criação</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody class="table-busca">
                @foreach ($ordemServicos as $item)
                    <tr>
                        <th scope="row">{{ $item->order_id }}</th>

                        <td>{{ $item->owner }}</td>
                        <th>{{ date('d/m/Y', strtotime($item->created_at)) }}</th>
                        <td>
                            <div class="row">
                                <div class="col-4"><a href="{{ route('ordem.servico.show', $item->id) }}"><button
                                            class="btn btn-primary"><i class="fa-solid fa-eye"></i>
                                        </button></a>
                                </div>
                                <div class="col-4"><button type="button" class="btn btn-danger delete"
                                        data-id="{{ $item->id }}"><i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $ordemServicos->links() }}
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.delete', function() {
                var id = $(this).data('id');

                Swal.fire({
                    title: 'Você tem certeza?',
                    text: "Você não poderá reverter isso!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sim, delete!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('ordem.delete') }}",
                            type: "POST",
                            data: {
                                id: id,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Deletado!',
                                    'Ordem de serviço deletada com sucesso.',
                                    'success'
                                )
                                location.reload();
                            }
                        });
                    }
                });

            });
            $('#busca').keyup(function() {
                var busca = $(this).val();

                if (busca === '') {
                    // Ação a ser realizada quando o campo de busca estiver vazio
                    // Por exemplo, recarregar a página ou redefinir os resultados da busca
                    $('.table-busca').html(''); // Limpa os resultados da busca
                    return; // Sai da função para evitar a requisição AJAX
                }

                $.ajax({
                    url: "{{ route('ordem.search') }}",
                    type: "POST",
                    data: {
                        busca: busca,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        $('.table-busca').html(response.viewRender);
                    }
                });
            });
            $('#buscar-codlab').click(function() {
                var codlab = $('#codlab').val();

                if (codlab === '') {
                    // Ação a ser realizada quando o campo de busca estiver vazio
                    // Por exemplo, recarregar a página ou redefinir os resultados da busca
                    $('.table-busca').html(''); // Limpa os resultados da busca
                    return; // Sai da função para evitar a requisição AJAX
                }

                $.ajax({
                    url: "{{ route('search.by.codlab') }}",
                    type: "POST",
                    data: {
                        codlab: codlab,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        $('.table').html(response.viewRender);
                    }
                });
            });
        });
    </script>
@endsection
