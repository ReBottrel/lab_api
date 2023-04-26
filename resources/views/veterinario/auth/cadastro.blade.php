@extends('layouts.veterinario')
@section('content')
    <div class="cadastro-vet">
        <div class="cadastro-vet-content">
            <h1 class="cadastro-vet-content-title">Cadastro</h1>
            <form id="myForm">
                <div id="step1">
                    <div class="cadastro-vet-content-input">
                        <label for="name">Nome</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
                    </div>
                    <div class="cadastro-vet-content-input">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                    </div>
                    <div class="cadastro-vet-content-input">
                        <label for="password">Senha</label>
                        <input id="password" type="password" name="password" required>
                    </div>
                    <div class="cadastro-vet-content-input">
                        <label for="password-confirm">Confirmar Senha</label>
                        <input id="password-confirm" type="password" name="password_confirmation" required>
                    </div>
                    <div>
                        <button type="button" class="btn-next" id="next1">Próximo</button>
                    </div>
                </div>
                <div id="step2" style="display:none;">
                    <div class="cadastro-vet-content-input">
                        <label for="name">CEP</label>
                        <input id="cep" type="text" name="cep" autofocus autocomplete="">
                    </div>
                    <div class="cadastro-vet-content-input">
                        <label for="name">Endereço</label>
                        <input id="address" type="text" name="address" autofocus autocomplete="">
                    </div>
                    <div class="cadastro-vet-content-input">
                        <label for="name">Numero</label>
                        <input id="number" type="text" name="number" autofocus autocomplete="">
                    </div>
                    <div class="cadastro-vet-content-input">
                        <label for="name">Bairro</label>
                        <input id="district" type="text" name="district" autofocus autocomplete="">
                    </div>
                    <div class="cadastro-vet-content-input">
                        <label for="name">Cidade</label>
                        <input id="city" type="text" name="city" autofocus autocomplete="">
                    </div>
                    <div class="cadastro-vet-content-input">
                        <label for="name">Estado</label>
                        <input id="state" type="text" name="state" autofocus autocomplete="">
                    </div>
                    <button type="button" class="btn-next" id="prev1">Anterior</button>
                    <button type="button" class="btn-next" id="next2">Próximo</button>
                </div>
                <div id="step3" style="display:none;">
                    <div class="cadastro-vet-content-input">
                        <label for="name">Telefone</label>
                        <input id="phone" type="text" name="phone" autofocus autocomplete="">
                    </div>
                    <div class="cadastro-vet-content-input">
                        <label for="name">CPF/CNPJ</label>
                        <input id="cpf" type="text" name="cpf" autofocus autocomplete="">
                    </div>
                    <div class="cadastro-vet-content-input">
                        <label for="name">Nº PORTARIA MORMO</label>
                        <input id="number_mormo" type="text" name="number_mormo" autofocus autocomplete="">
                    </div>
                    <div class="cadastro-vet-content-input">
                        <label for="name">CRMV</label>
                        <input id="cmrv" type="text" name="crmv" autofocus autocomplete="">
                    </div>
                    <button type="button" class="btn-next" id="prev3">Anterior</button>
                    <button class="btn-save" type="button" id="submit">CADASTRAR</button>
                </div>

            </form>
        </div>
    @endsection
    @section('js')
        <script>
            $(document).ready(function() {
                $('#next1').click(function() {
                    $('#step1').hide();
                    $('#step2').show();
                });
                $('#prev1').click(function() {
                    $('#step2').hide();
                    $('#step1').show();
                });
                $('#next2').click(function() {
                    $('#step2').hide();
                    $('#step3').show();
                });
                $('#prev2').click(function() {
                    $('#step3').hide();
                    $('#step2').show();
                });
                $('#prev3').click(function() {
                    $('#step3').hide();
                    $('#step2').show();
                });

                $('#submit').click(function() {
                    $.ajax({
                        url: "{{ route('vet.register.submit') }}",
                        type: "POST",
                        data: {
                            name: $('#name').val(),
                            email: $('#email').val(),
                            password: $('#password').val(),
                            password_confirmation: $('#password-confirm').val(),
                            cpf: $('#cpf').val(),
                            portaria: $('#number_mormo').val(),
                            crmv: $('#cmrv').val(),
                            cep: $('#cep').val(),
                            address: $('#address').val(),
                            number: $('#number').val(),
                            district: $('#district').val(),
                            city: $('#city').val(),
                            state: $('#state').val(),
                            phone: $('#phone').val(),
                            _token: "{{ csrf_token() }}"
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Aguarde...',
                                allowOutsideClick: false,
                                onBeforeOpen: () => {
                                    Swal.showLoading()
                                },
                            })
                        },
                        success: function(data) {
                            console.log(data);
                            Swal.fire({
                                icon: 'success',
                                title: 'Sucesso!',
                                text: 'Cadastro realizado com sucesso!',
                                showConfirmButton: false,
                                timer: 1500
                            }).then((result) => {
                                window.location.href = "{{ route('vet.login') }}";
                            })


                        },
                        error: function(data) {
                            console.log(data);
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Algo deu errado!',

                            })
                        }
                    });
                });
                $('#phone').mask('(00) 00000-0000');
                $('#cpf').mask('000.000.000-00', {
                    reverse: true
                });
                // $('#cep').mask('00000-000');
                $(document).on('blur', '#cep', function() {
                    var cep = $(this).val();
                    if (cep.length == 8) {
                        $.ajax({
                            url: "https://viacep.com.br/ws/" + cep + "/json/",
                            type: "GET",
                            dataType: "json",
                            success: function(data) {
                                console.log(data);
                                $('input[name=address]').val(data.logradouro);
                                $('input[name=district]').val(data.bairro);
                                $('input[name=city]').val(data.localidade);
                                $('input[name=state]').val(data.uf);
                            }
                        });
                    }
                });
            });
        </script>
    @endsection
