@extends('layouts.admin')
@section('content')
<div class="ordem-servico-container">
    <div class="page-header">
        <h3>Todas as Ordens de Serviço</h3>
    </div>

    <div class="search-filters">
        <div class="search-row">
            <div class="search-group">
                <label for="busca">Buscar por proprietário ou número</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="busca" placeholder="Digite sua busca...">
                    <span class="input-icon">
                        <i class="fas fa-user"></i>
                    </span>
                </div>
            </div>

            <div class="search-group">
                <label for="codlab">Buscar por codlab</label>
                <div class="input-with-button">
                    <div class="input-group">
                        <input type="text" class="form-control" id="codlab" placeholder="Digite o codlab...">
                        <span class="input-icon">
                            <i class="fas fa-barcode"></i>
                        </span>
                    </div>
                    <button class="btn btn-search" id="buscar-codlab">
                        <i class="fas fa-search"></i>
                        Buscar
                    </button>
                </div>
            </div>
        </div>

        <div class="search-row">
            <div class="search-group">
                <label for="animal">Buscar por animal</label>
                <div class="input-with-button">
                    <div class="input-group">
                        <input type="text" class="form-control" id="animal" placeholder="Digite o nome do animal...">
                        <span class="input-icon">
                            <i class="fas fa-horse"></i>
                        </span>
                    </div>
                    <button class="btn btn-search" id="buscar-animal">
                        <i class="fas fa-search"></i>
                        Buscar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Número/Pedido</th>
                    <th>Proprietário</th>
                    <th>Data de Criação</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody class="table-busca">
                @foreach ($ordemServicos as $item)
                <tr>
                    <td>
                        <span class="order-id" title="Número do Pedido">
                            {{ str_pad($item->order_id, 6, '0', STR_PAD_LEFT) }}
                        </span>
                    </td>
                    <td>
                        <div class="owner-name">
                            <div class="owner-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <span>{{ $item->owner }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="date">{{ date('d/m/Y', strtotime($item->created_at)) }}</span>
                    </td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('ordem.servico.show', $item->id) }}" 
                               class="btn btn-primary" 
                               title="Visualizar Ordem">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button type="button" 
                                    class="btn btn-danger delete" 
                                    data-id="{{ $item->id }}" 
                                    title="Excluir Ordem">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $ordemServicos->links() }}
    </div>
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
                        console.log(response);
                        $('.table').html(response.viewRender);
                    },
                    error: function(response) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Codlab não encontrado!, ou imcompleto',
                        })
                    }
                });
            });
            $('#buscar-animal').click(function() {
                var animal = $('#animal').val();

                if (animal === '') {
                    // Ação a ser realizada quando o campo de busca estiver vazio
                    // Por exemplo, recarregar a página ou redefinir os resultados da busca
                    $('.table-busca').html(''); // Limpa os resultados da busca
                    return; // Sai da função para evitar a requisição AJAX
                }

                $.ajax({
                    url: "{{ route('search.by.animal') }}",
                    type: "POST",
                    data: {
                        animal: animal,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        $('.table').html(response.viewRender);
                    },
                    error: function(response) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Animal não encontrado!, ou imcompleto',
                        })
                    }
                });
            });
        });
    </script>
@endsection
