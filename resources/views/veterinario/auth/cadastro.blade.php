@extends('layouts.veterinario')
@section('content')
    <div class="cadastro-vet">
        <div class="cadastro-vet-content">
            <h1 class="cadastro-vet-content-title">Cadastro</h1>
                <form id="myForm">
                    <div id="step1">
                        <div class="cadastro-vet-content-input">
                            <label for="name">Nome</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required
                                autofocus>
                        </div>
                        <div class="cadastro-vet-content-input">
                            <label for="email">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                autofocus>
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
                            <button type="button" id="next1">Próximo</button>
                        </div>
                    </div>

                    <div id="step2" style="display:none;">
                        <h2>Step 2</h2>
                        <input type="email" name="email" placeholder="Email">
                        <button type="button" id="prev1">Previous</button>
                        <button id="submit">Submit</button>
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
                            url: "#",
                            type: "POST",
                            data: {
                                name: $('input[name=name]').val(),
                                email: $('input[name=email]').val(),
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(data) {
                                console.log(data);
                                if (data.status == 200) {
                                    window.location.href = "#";
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Algo deu errado!',
                                        footer: '<a href>Why do I have this issue?</a>'
                                    })
                                }
                            },
                            error: function(data) {
                                console.log(data);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Login ou senha incorretos!',
                                    footer: '<a href>Why do I have this issue?</a>'
                                })
                            }
                        });
                    });
                });
            </script>
        @endsection
