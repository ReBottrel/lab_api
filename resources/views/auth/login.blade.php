<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('loja/node_modules/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('loja/node_modules/bootstrap-icons/font/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('loja/css/style.min.css') }}">

    <title>Login</title>
</head>

<body>
    <div class="container-fluid">
        <header
            class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-around py-3 mb-4 border-bottom border-primary">

            <a href="/" class="d-flex align-items-center justify-content-sm-around col-md-3 mb-2 mb-md-0">
                <img src="{{ asset('loja/assets/img/logo.svg') }}" alt="">
            </a>
            <form class="d-flex justify-content-center col-12 col-lg-5 mb-3 mb-lg-0 me-lg-3 " role="search">
                <div class="input-group submit-line">
                    <input class="form-control " type="search" placeholder="Busque aqui" />

                    <button class="submit-lente  btn-lg " type="submit">
                        <i class="bi bi-search"></i>
                    </button>

                </div>
            </form>
            <div class="col-md-3 text-end">
                <a href="/entrar.html" type="button" class="btn btn-primary">Entrar</a>
                <a href="/cadastro.html" type="button" class="btn btn-secondary">Cadastar-se</a>
            </div>
        </header>
    </div>
    <div class="d-flex flex-column wrapper mb-4">

        <main class="flex-fill">
            <div class="container">
                <div class="row justify-content-center">
                    <form class="col-sm-10 col-md-8 col-lg-6" id="loginForm">
                        @csrf
                        <h3 class="text-primary">Identifique-se, por favor</h3>

                        <div class="form-floating mb-3">
                            <input type="email" name="email" id="email" class="form-control" placeholder=" "
                                autofocus>
                            <label for="txtEmail">E-mail</label>
                            <span id="emailError" class="error"></span>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" name="password" id="password" class="form-control" placeholder=" ">
                            <label for="txtSenha">Senha</label>
                            <span id="passwordError" class="error"></span>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" value="" id="chkLembrar">
                            <label for="chkLembrar" class="form-check-label">Lembrar de mim</label>
                        </div>

                        <button type="submit" class="btn btn-lg btn-primary">Entrar</button>

                        <p class="mt-3">
                            Ainda não é cadastrado? <a href="/cadastro.html">Clique aqui</a> para se cadastrar.
                        </p>

                        <p class="mt-3">
                            Esqueceu sua senha? <a href="/recuperarsenha.html">Clique aqui</a> para recuperá-la.
                        </p>
                    </form>
                </div>
            </div>
        </main>
    </div>
    <footer class="border-top border-primary text-muted text-bg-primary">
        <div class="container">
            <div class="row py-3 ">
                <div class="  col-5 patrocinios mb-3">
                    <img class="img-fluid" src="{{ asset('loja/assets/img/isag.jpg') }}" alt="">
                </div>
                <div class=" col-5 patrocinios mb-3">
                    <img class="img-fluid" src="{{ asset('loja/assets/img/mpa.jpg') }}" alt="">
                </div>
                <div class=" col-2 patrocinios mb-3">
                    <img class="img-fluid" src="{{ asset('loja/assets/img/inmetro.jpg') }}" alt="">
                </div>
                <div class="col-12  text-center text-bg-primary mt-3">
                    &copy; 2022 -Loci Ltda
                    Rua Tal, 111, Cidade CD
                    CPNJ 99.999.999/0001-99
                </div>

            </div>
        </div>
    </footer>
    <script src="{{ asset('adm/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('loja/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        // rota para processar o login
        const loginRoute = "{{ route('login.custom') }}";

        // função de callback para processar o login
        function handleLogin(response) {
            // verifica se o login foi bem-sucedido
            if (response.success) {
                // redireciona para a página inicial
                window.location.href = "{{ route('user.dashboard') }}";
            } else {
                // exibe uma mensagem de erro
                Swal.fire({
                    title: 'Error!',
                    text: 'Falha ao realizar login: ' + response.message,
                    icon: 'error',
                    confirmButtonText: 'Ok'
                })

            }
        }

        // quando o formulário for enviado
        $('#loginForm').submit(function(event) {

            event.preventDefault();
            $('#emailError').text('');
            $('#passwordError').text('');
            // previne o envio padrão do formulário
            let isValid = true;
            const email = $('#email').val();
            // verifica se o e-mail é válido
            if (!email || !email.match(/\S+@\S+\.\S+/)) {
                // exibe uma mensagem de erro
                $('#emailError').text('Por favor, informe um endereço de e-mail válido.');

                // indica que o formulário é inválido
                isValid = false;
            }
            const password = $('#password').val();

            // verifica se a senha é válida
            if (!password || password.length < 6) {
                // exibe uma mensagem de erro
                $('#passwordError').text('A senha deve conter pelo menos 6 caracteres.');

                // indica que o formulário é inválido
                isValid = false;
            }


            // pega os dados do formulário
            const data = $(this).serialize();
            if (!isValid) {
                event.preventDefault();
            }
            // envia os dados do formulário para a rota de login
            $.post(loginRoute, data, handleLogin);
        });
    </script>
</body>

</html>
