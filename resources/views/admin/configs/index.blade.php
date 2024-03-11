@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Configurações</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        Criar usuário adm
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="post">
                                            @csrf
                                            <div class="form-group mb-3">
                                                <label for="name">Nome</label>
                                                <input type="text" name="name" id="name" class="form-control">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="name">E-mail</label>
                                                <input type="email" name="email" id="email" class="form-control">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="name">Senha</label>
                                                <input type="password" name="password" id="password" class="form-control">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="name">Senha</label>
                                                <input type="password" name="password-confirm" id="password-confirm"
                                                    class="form-control">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="name">Selecionar permissão</label>
                                                <select class="form-select select-permission"
                                                    aria-label="Default select example" name="permission">

                                                    <option value="10">Administrador</option>
                                                    <option value="8">Associação</option>

                                                </select>
                                            </div>
                                            <div class="form-group mb-3 d-none association">
                                                <label for="name">Selecionar associação</label>
                                                <select class="form-select association-select" aria-label="Default select example">
                                                    <option value="1">PAMPA</option>
                                                    <option value="2">PEGA</option>
                                                    <option value="3">QUARTO MILHA</option>
                                                    <option value="4">CAMPOLINA</option>
                                                    <option value="5">ABCCMM</option>
                                                </select>
                                            </div>

                                            <div class="my-3 text-center">
                                                <button type="button" class="btn btn-success create-adm">Criar
                                                    usuário</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">ID</th>
                                                <th scope="col">Nome</th>
                                                <th scope="col">Acões</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($admins as $user)
                                                <tr>
                                                    <th scope="row">{{ $user->id }}</th>
                                                    <td>{{ $user->name }}</td>
                                                    <td>
                                                        <a href="{{ route('config.edit.admin', $user->id) }}"
                                                            class="btn btn-primary">Editar</a>
                                                        <a href="{{ route('admin.delete', $user->id) }}"
                                                            class="btn btn-danger">Excluir</a>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.create-adm', function() {

                var name = $('#name').val();
                var email = $('#email').val();
                var password = $('#password').val();
                var password_confirm = $('#password-confirm').val();
                var permission = $('.select-permission').val();
                var association = $('.association-select').val();

                $.ajax({
                    url: "{{ route('admin.login.store') }}",
                    type: "POST",
                    data: {
                        name: name,
                        email: email,
                        password: password,
                        password_confirm: password_confirm,
                        permission: permission,
                        association: association,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Sucesso!',
                            text: 'Usuário criado com sucesso',
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    },
                    error: function(er) {
                        Swal.fire({
                            title: 'Oops!',
                            text: 'Encontramos um erro, verifique se o usuário já existe',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    }
                });
            });
            $(document).on('change', '.select-permission', function() {
                var permission = $(this).val();
                if (permission == 8) {

                    $('.create-adm').text('Criar associação');
                    $('.association').removeClass('d-none');
                } else {
                    $('.create-adm').text('Criar usuário');
                    $('.association').addClass('d-none');
                }

            });
        });
    </script>
@endsection
