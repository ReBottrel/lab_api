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
                                                <select class="form-select" aria-label="Default select example">

                                                    <option value="1">Administrador</option>
                                                    <option value="2">Financeiro</option>
                                                    <option value="3">Técnico</option>
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
                var permission = $('.form-select').val();
                $.ajax({
                    url: "{{ route('admin.login.store') }}",
                    type: "POST",
                    data: {
                        name: name,
                        email: email,
                        password: password,
                        password_confirm: password_confirm,
                        permission: permission,
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
        });
    </script>
@endsection
