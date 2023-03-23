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
                        <label for="name">CPF/CNPJ</label>
                        <input id="cpf" type="text" name="cpf" autofocus autocomplete="">
                    </div>
                    <div class="cadastro-vet-content-input">
                        <label for="name">Nº PORTARIA MORMO</label>
                        <input id="number_mormo" type="text" name="number_mormo" autofocus autocomplete="">
                    </div>
                    <button type="button" class="btn-next" id="prev1">Anterior</button>
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
            });
        </script>
    @endsection
