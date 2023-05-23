@extends('layouts.admin')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Parceiros</h1>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Criar Parceiro
                </button>
            </div>
            <div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Situação</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse ($parceiros as $parceiro)
                            <tr>
                                <td>{{ $parceiro->nome }}</td>

                                <td>Ativa</td>
                                <td>
                                    <button data-id="{{ $parceiro->id }}" type="button" id="delete"
                                        class="btn btn-sm btn-danger">Excluir</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td>Sem parceiro</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Cadastrar parceiro</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('parceiros.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Nome</label>
                                <input type="text" class="form-control" name="nome">
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Email do parceiro</label>
                                <input type="email" class="form-control" name="email">
                            </div>

                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Telefone do parceiro</label>
                                <input type="text" class="form-control" name="telefone">
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $(document).on('click', '#delete', function() {
                var id = $(this).data('id');
                var token = $("meta[name='csrf-token']").attr("content");
                Swal.fire({
                    title: 'Tem certeza que deseja deletar?',
                    showDenyButton: false,
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    denyButtonText: `Cancelar`,
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('parceiros.delete') }}",
                            type: 'POST',
                            data: {
                                "id": id,
                                "_token": token,
                            },
                            success: function() {
                                location.reload();
                                Swal.fire(
                                    'Deletado!',
                                    'Parceiro deletado!',
                                    'success'
                                )
                            }
                        });
                    } else if (result.isDenied) {
                        Swal.fire('Changes are not saved', '', 'info')
                    }
                })

            });
        });
    </script>
@endsection
