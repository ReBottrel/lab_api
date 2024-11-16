@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Usuários</h1>
                <div class="row">
                    {{-- <div class="col-4">
                        <a href="{{ route('owner.create') }}" class="btn btn-primary">Criar Proprietario</a>
                    </div> --}}
                    <div class="col-8">
                        <form action="" method="POST" class="form form-inline">
                            @csrf
                            <input type="search" name="filter" placeholder="Buscar por nome, email "
                                class="form-control buscar-owner" value="{{ $filters['filter'] ?? '' }}">
                        </form>
                    </div>
                </div>

            </div>
            <div class="card-body">
                <div class="">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>CPF/CNPJ</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody class="filter">
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ strtolower($user->email) }}</td>
                                    <td>{{ $user->info->document }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-alt-loci text-white dropdown-toggle" href="#"
                                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                Ações
                                            </a>

                                            <ul class="dropdown-menu">
                                                <a href="{{ route('owner.user', $user->id) }}"
                                                    class="dropdown-item">Editar</a>

                                                <a data-id="{{ $user->id }}" class="dropdown-item delete">Excluir</a>
                                            </ul>
                                        </div>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $users->links() }}
            </div>
        </div>

    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.create-access', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: "{{ route('owner.access') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        // console.log(data)

                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso',
                            text: 'Acesso criado com sucesso',
                        });
                        window.reload();

                    },
                    error: function(data) {
                        console.log(data)
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Algo deu errado!',
                        });
                    }
                });
            });
            $('.buscar-owner').keyup(function() {
                var search = $(this).val();
                $.ajax({
                    url: "{{ route('users.search') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        search: search
                    },
                    success: function(data) {
                        console.log(data);
                        $('.filter').html(data.html);
                    }
                });
            });
            $(document).on('click', '.delete', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Você tem certeza?',
                    text: "Você não poderá reverter isso!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sim, deletar!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('users.delete') }}",
                            type: "POST",
                            data: {
                                id: id,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(data) {
                                // console.log(data)

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sucesso',
                                    text: 'Proprietario deletado com sucesso',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.reload();
                                    }
                                });


                            },
                            error: function(data) {
                                console.log(data)
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Algo deu errado!',
                                });
                            }
                        });
                    }
                })
            });
            // $(document).ready(function() {
            //     $('.buscar-owner').on('keyup', function() {
            //         var value = $(this).val().toLowerCase();
            //         console.log(value);
            //         $('.filter').filter(function() {
            //             $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            //         });
            //     });
            // });
        });
    </script>
@endsection
