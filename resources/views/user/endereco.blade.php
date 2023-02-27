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
                                    <form action="{{ route('user.update.address') }}" method="POST">
                                        @csrf

                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="cep"
                                                        name="zip_code" placeholder="CEP"
                                                        value="{{ $user->info->zip_code }}">
                                                    <label for="cep">CEP</label>
                                                </div>
                                            </div>
                                            <div class="col-8">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="endereco" name="address"
                                                        placeholder="Endereço" value="{{ $user->info->address }}">
                                                    <label for="endereco">Endereço</label>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="endereco" name="number"
                                                        placeholder="Endereço" value="{{ $user->info->number }}">
                                                    <label for="endereco">Numero</label>
                                                </div>
                                            </div>
                                            <div class="col-8">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="endereco"
                                                        name="complement" placeholder="Endereço"
                                                        value="{{ $user->info->complement }}">
                                                    <label for="endereco">Complemento</label>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="bairro"
                                                        name="district" placeholder="Bairro"
                                                        value="{{ $user->info->district }}">
                                                    <label for="bairro">Bairro</label>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="cidade" name="city"
                                                        placeholder="Cidade" value="{{ $user->info->city }}">
                                                    <label for="cidade">Cidade</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="estado" name="state"
                                                        placeholder="Estado" value="{{ $user->info->state }}">
                                                    <label for="estado">Estado</label>
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
