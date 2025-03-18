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
       
         
        </header>
    </div>
    <div class="d-flex flex-column wrapper mb-4">
        <main class="flex-fill">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-sm-10 col-md-8 col-lg-6">
                        <div class="login-card">
                            <div class="login-header text-center mb-4">
                                <img src="{{ asset('loja/assets/img/logo.svg') }}" alt="Logo" class="login-logo mb-3">
                                <h3 class="text-primary">Bem-vindo de volta!</h3>
                                <p class="text-muted">Por favor, faça login para continuar</p>
                            </div>
                            
                            <form id="loginForm">
                                @csrf
                                <div class="form-floating mb-3">
                                    <input type="email" name="email" id="email" class="form-control" placeholder=" " autofocus>
                                    <label for="txtEmail">E-mail</label>
                                    <span id="emailError" class="error"></span>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="password" name="password" id="password" class="form-control" placeholder=" ">
                                    <label for="txtSenha">Senha</label>
                                    <span id="passwordError" class="error"></span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" value="" id="chkLembrar">
                                        <label for="chkLembrar" class="form-check-label">Lembrar de mim</label>
                                    </div>
                                    <a href="/recuperarsenha.html" class="text-primary text-decoration-none">Esqueceu a senha?</a>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 btn-lg mb-3">Entrar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <footer class="text-center text-lg-start bg-white text-muted">
        <!-- Section: Social media -->

        <!-- Section: Social media -->

        <!-- Section: Links  -->
        <section class="">
            <div class="container text-center text-md-start mt-5">
                <!-- Grid row -->
                <div class="row mt-3">
                    <!-- Grid column -->
                    <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
                        <!-- Content -->
                        <h6 class="text-uppercase fw-bold mb-4">
                            <i class="fas fa-gem me-3 text-primary"></i>LABLOCI
                        </h6>
                        <p>
                            Somos uma equipe de cientistas fiéis aos princípios da ética, da competência técnica e da
                            segurança dos resultados produzidos;
                        </p>
                    </div>
                    <!-- Grid column -->

                    <!-- Grid column -->
                    <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
                        <!-- Links -->
                        <h6 class="text-uppercase fw-bold mb-4">
                            Parceiros
                        </h6>
                        <div class="imgs-footer">
                            <div>
                                <img class="img-fluid" src="{{ asset('loja/assets/img/isag.jpg') }}" alt="">
                            </div>
                            <div>
                                <img class="img-fluid" src="{{ asset('loja/assets/img/mpa.jpg') }}" alt="">
                            </div>
                            <div>
                                <img class="img-fluid" src="{{ asset('loja/assets/img/inmetro.jpg') }}" alt="">
                            </div>
                        </div>
                    </div>
                    <!-- Grid column -->

                    <!-- Grid column -->
                    <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
                        <!-- Links -->
                        <h6 class="text-uppercase fw-bold mb-4">
                            Links Úteis
                        </h6>
                        <p>
                            <a href="#!" class="text-reset">Politicas de privacidade</a>
                        </p>
                        <p>
                            <a href="#!" class="text-reset">Termos e condições</a>
                        </p>
                        <p>
                            <a href="#!" class="text-reset">Pedidos</a>
                        </p>
                        <p>
                            <a href="#!" class="text-reset">Suporte</a>
                        </p>
                    </div>
                    <!-- Grid column -->

                    <!-- Grid column -->
                    <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                        <!-- Links -->
                        <h6 class="text-uppercase fw-bold mb-4">Contato</h6>
                        <p><i class="fas fa-home me-3 text-primary"></i>Rua Coronel Durães, 170, slj 01, Bela Vista -
                            Lagoa Santa l MG</p>
                        <p>
                            <i class="fas fa-envelope me-3 text-primary"></i>
                            atendimento@locilab.com.br
                        </p>
                        <p><i class="fas fa-phone me-3 text-primary"></i> 31 3681-4331</p>
                        <p><i class="fa-brands me-3 fa-whatsapp text-primary"></i> 31 99737-0135</p>
                    </div>
                    <!-- Grid column -->
                </div>
                <!-- Grid row -->
            </div>
        </section>
        <!-- Section: Links  -->

        <!-- Copyright -->
        <div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.025);">
            © 2022 Todos os Direitos Reservados
            <a class="text-reset fw-bold" href="https://locilab.com.br/">Labloci</a>
        </div>
        <!-- Copyright -->
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
