@extends('layouts.loja')
@section('content')
    <div class="container">
        <h1 class="text-primary">Minha Conta</h1>
        <div class="row gx-3">
            @component('layouts.partials.user-menu')
            @endcomponent
            <div class="col-8">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="text-primary">Alterar Endereço</h2>
                            <div class="row">
                                <div class="col-12">
                                    <form action="{{ route('user.update.dados') }}" method="POST">
                                        @csrf

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" name="name"
                                                        placeholder="Nome" value="{{ $user->name }}">
                                                    <label for="cep">Nome</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="endereco" name="phone"
                                                        placeholder="Celular" value="{{ $user->info->phone }}">
                                                    <label for="endereco">Celular</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-floating mb-3">
                                                    <input type="password" class="form-control" id="endereco" name="password"
                                                        placeholder="Senha" >
                                                    <label for="endereco">Senha</label>
                                                </div>
                                            </div>
                                         


                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary">Salvar</button>
                                            </div>
                                        </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endsection

                @section('scripts')
                    <script>
                        // $('#cep').mask('99999-999');
                        $('#fone').mask('(99) 99999-9999');
                        $('#cell').mask('(99) 99999-9999');

                        $(document).on('blur keyup', '#cep', function() {
                            cep = $(this).val();
                            $.ajax({
                                type: 'get',
                                url: "/user-cep-get/",
                                data: {
                                    cep: cep
                                },
                                success: function(data) {
                                    console.log(data);
                                    $('#endereco').val(data.logradouro);
                                    $('#bairro').val(data.bairro);
                                    $('#cidade').val(data.localidade);
                                    $('#uf').val(data.uf);
                                }
                            });
                        });
                    </script>
                @endsection
