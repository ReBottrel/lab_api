@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Usuário</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('owner.user.update', $user->id) }}" class="validatedForm" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="name">Nome</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    value="{{ $user->name }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control"
                                    value="{{ $user->email }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="email">Senha</label>
                                <input type="password" name="password" id="#password" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="email">Confirmar senha</label>
                                <input type="password" name="password-confirm" id="password_confirm" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">CPF/CNPJ</label>
                                <input type="text" name="document" value="{{ $user->info->document }}" id="cpfcnpj"
                                    class="form-control">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">CEP</label>
                                <input type="text" id="cep" name="zip_code" value="{{ $user->info->zip_code }}"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Endereço</label>
                                <input type="text" name="address" value="{{ $user->info->address }}" class="form-control"
                                    id="address">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Numero</label>
                                <input type="text" name="number" value="{{ $user->info->number }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Complemento</label>
                                <input type="text" name="complement" value="{{ $user->info->complement }}"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">UF</label>
                                <input type="text" name="state" value="{{ $user->info->state }}" class="form-control"
                                    id="uf">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Bairro</label>
                                <input type="text" name="district" value="{{ $user->info->district }}"
                                    class="form-control" id="bairro">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Cidade</label>
                                <input type="text" name="city" value="{{ $user->info->city }}"
                                    class="form-control" id="cidade">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Celular</label>
                                <input type="text" name="phone" value="{{ $user->info->phone }}" id="fone"
                                    class="form-control">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3 text-center">
                                <button class="btn btn-success" type="submit">Salvar</button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        @endsection

        @section('js')
            <script>
                jQuery('.validatedForm').validate({
                    rules: {
                        password: {
                            minlength: 5,
                        },
                        password_confirm: {
                            minlength: 5,
                            equalTo: "#password"
                        }
                    }
                });
            </script>
        @endsection
