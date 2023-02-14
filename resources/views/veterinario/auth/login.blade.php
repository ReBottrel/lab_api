@extends('layouts.veterinario')
@section('content')
    <div class="login-form">
        <div class="login-form-content">
            <h1 class="login-form-content-title">Login</h1>
            <form method="POST" action="">
                @csrf
                <div class="login-form-content-input">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="login-form-content-input">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required>
                </div>

                <div>
                    <button class="login-form-content-btn" id="login" type="button">Login</button>
                </div>

            </form>
            <div class="cadastro-link">
                <p>Não possuí cadastro? <a href="{{ route('vet.register') }}">Clique Aqui</a></p>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('#login').click(function() {
                console.log('teste');
                $.ajax({
                    url: "{{ route('vet.login.submit') }}",
                    type: "POST",
                    data: {
                        email: $('#email').val(),
                        password: $('#password').val(),
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        console.log(data);
                        if (data.status == 200) {
                            window.location.href = "{{ route('vet.index') }}";
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
                            
                        })

                    }
                });
            });
        });
    </script>
@endsection
