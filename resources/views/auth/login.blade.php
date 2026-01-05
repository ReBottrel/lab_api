<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('loja/node_modules/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('loja/node_modules/bootstrap-icons/font/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('loja/css/style.min.css') }}">

    <title>Login - Labloci</title>

    <style>
        :root {
            --primary-color: #0d6efd;
            --primary-hover: #0b5ed7;
            --error-color: #dc3545;
            --success-color: #198754;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #4facfe 75%, #00f2fe 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow-x: hidden;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        .login-header-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 1.5rem 0;
            position: relative;
            z-index: 10;
        }

        .login-header-section img {
            max-height: 50px;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }

        .login-header-section img:hover {
            transform: scale(1.08) rotate(2deg);
        }

        .wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 0;
            min-height: calc(100vh - 200px);
            position: relative;
            z-index: 1;
        }

        .wrapper main {
            width: 100%;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow:
                0 20px 60px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.5) inset;
            padding: 3.5rem 2.5rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .login-card:hover::before {
            left: 100%;
        }

        .login-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow:
                0 30px 80px rgba(0, 0, 0, 0.2),
                0 0 0 1px rgba(255, 255, 255, 0.6) inset;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            margin-bottom: 2.5rem;
            position: relative;
        }

        .login-logo {
            max-height: 80px;
            margin-bottom: 1.5rem;
            filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.15));
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            animation: logoFloat 3s ease-in-out infinite;
        }

        @keyframes logoFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .login-logo:hover {
            transform: scale(1.05) rotate(5deg);
            filter: drop-shadow(0 6px 20px rgba(0, 0, 0, 0.2));
        }

        .login-header h3 {
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #212529;
            font-size: 1.75rem;
            background: linear-gradient(135deg, var(--primary-color), #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .login-header p {
            font-size: 0.95rem;
            color: #6c757d;
            font-weight: 400;
        }

        .form-floating {
            position: relative;
            margin-bottom: 1.75rem;
        }

        .form-floating > .form-control {
            height: calc(3.5rem + 2px);
            padding: 1rem 1rem 1rem 3.5rem;
            border: 2px solid rgba(0, 0, 0, 0.1);
            border-radius: 14px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 1rem;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
        }

        .password-field > .form-control {
            padding-right: 3.5rem;
        }

        .form-floating > .form-control:focus {
            border-color: var(--primary-color);
            box-shadow:
                0 0 0 4px rgba(13, 110, 253, 0.1),
                0 4px 12px rgba(13, 110, 253, 0.15);
            background: rgba(255, 255, 255, 0.95);
            transform: translateY(-2px);
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            padding: 0.5rem;
            z-index: 10;
            transition: all 0.3s ease;
            border-radius: 8px;
        }

        .password-toggle:hover {
            color: var(--primary-color);
            background: rgba(13, 110, 253, 0.1);
        }

        .password-toggle:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.2);
        }

        .form-floating > .form-control.is-invalid {
            border-color: var(--error-color);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 3.6 .4.4.4-.4m0 4.8-.4-.4-.4.4'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
            padding-right: calc(1.5em + 0.75rem);
        }

        .form-floating > .form-control.is-invalid:focus {
            border-color: var(--error-color);
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.15);
        }

        .input-icon-wrapper {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 1.25rem;
            z-index: 5;
            pointer-events: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .form-floating > .form-control:focus ~ .input-icon-wrapper {
            color: var(--primary-color);
            transform: translateY(-50%) scale(1.1);
        }

        .form-floating > label {
            color: #6c757d;
            font-weight: 500;
            padding: 1rem 1rem 1rem 3.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .form-floating > label i {
            color: #6c757d;
            transition: var(--transition);
        }

        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            color: var(--primary-color);
            transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
        }

        .form-floating > .form-control:focus ~ label i,
        .form-floating > .form-control:not(:placeholder-shown) ~ label i {
            color: var(--primary-color);
        }

        .error {
            display: block;
            color: var(--error-color);
            font-size: 0.875rem;
            margin-top: 0.5rem;
            padding-left: 0.5rem;
            animation: shake 0.3s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .form-check-input {
            width: 1.25rem;
            height: 1.25rem;
            border-radius: 8px;
            border: 2px solid #dee2e6;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background-color: white;
        }

        .form-check-input:hover {
            border-color: var(--primary-color);
            transform: scale(1.1);
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e");
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
        }

        .form-check-label {
            cursor: pointer;
            user-select: none;
            color: #495057;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .form-check:hover .form-check-label {
            color: var(--primary-color);
        }

        .forgot-password-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 0.9rem;
            position: relative;
        }

        .forgot-password-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary-color);
            transition: width 0.3s ease;
        }

        .forgot-password-link:hover {
            color: var(--primary-hover);
            transform: translateX(3px);
        }

        .forgot-password-link:hover::after {
            width: 100%;
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary-color) 0%, #5a67d8 50%, var(--primary-hover) 100%);
            background-size: 200% 200%;
            border: none;
            border-radius: 14px;
            padding: 1rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(13, 110, 253, 0.4);
            background-position: right center;
        }

        .btn-login:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-login:active {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(13, 110, 253, 0.3);
        }

        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
            background-position: left center;
        }

        .btn-login .spinner-border {
            width: 1.2rem;
            height: 1.2rem;
            border-width: 2px;
        }

        .btn-login span {
            position: relative;
            z-index: 1;
        }

        footer {
            margin-top: auto;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 10;
        }

        footer a {
            transition: all 0.3s ease;
        }

        footer a:hover {
            color: var(--primary-color) !important;
            transform: translateX(3px);
        }

        footer i {
            transition: transform 0.3s ease;
        }

        footer a:hover i {
            transform: scale(1.2);
        }

        @media (max-width: 768px) {
            body {
                background-size: 300% 300%;
            }

            .login-card {
                padding: 2rem 1.5rem;
                margin: 1rem;
                border-radius: 20px;
            }

            .login-logo {
                max-height: 60px;
            }

            .login-header h3 {
                font-size: 1.5rem;
            }

            .form-floating > .form-control {
                height: calc(3rem + 2px);
                padding: 0.875rem 0.875rem 0.875rem 3rem;
                font-size: 0.95rem;
            }

            .form-floating > label {
                padding: 0.875rem 0.875rem 0.875rem 3rem;
                font-size: 0.9rem;
            }

            .input-icon-wrapper {
                left: 0.875rem;
                font-size: 1.1rem;
            }

            .password-toggle {
                right: 0.75rem;
                padding: 0.375rem;
            }

            .btn-login {
                padding: 0.875rem 1.5rem;
                font-size: 1rem;
            }

            .wrapper {
                padding: 2rem 0;
            }
        }

        @media (max-width: 576px) {
            .login-card {
                padding: 1.5rem 1.25rem;
                margin: 0.5rem;
            }

            .login-header {
                margin-bottom: 2rem;
            }

            .form-floating {
                margin-bottom: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="login-header-section">
        <div class="container-fluid">
            <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-around py-3">
                <a href="/" class="d-flex align-items-center justify-content-sm-around col-md-3 mb-2 mb-md-0">
                    <img src="{{ asset('loja/assets/img/logo.svg') }}" alt="Labloci Logo">
                </a>
            </header>
        </div>
    </div>

    <div class="wrapper">
        <main class="flex-fill">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-sm-10 col-md-8 col-lg-5 col-xl-4">
                        <div class="login-card">
                            <div class="login-header text-center">
                                <img src="{{ asset('loja/assets/img/logo.svg') }}" alt="Logo" class="login-logo">
                                <h3 class="text-primary">Bem-vindo de volta!</h3>
                                <p class="text-muted">Por favor, faça login para continuar</p>
                            </div>

                            <form id="loginForm">
                                @csrf
                                <div class="form-floating mb-3">
                                    <i class="bi bi-envelope input-icon-wrapper"></i>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="E-mail" autofocus>
                                    <label for="email">E-mail</label>
                                    <span id="emailError" class="error"></span>
                                </div>

                                <div class="form-floating mb-3 password-field">
                                    <i class="bi bi-lock input-icon-wrapper"></i>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Senha">
                                    <button type="button" class="password-toggle" id="togglePassword" aria-label="Mostrar senha">
                                        <i class="bi bi-eye" id="eyeIcon"></i>
                                    </button>
                                    <label for="password">Senha</label>
                                    <span id="passwordError" class="error"></span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" value="" id="chkLembrar">
                                        <label for="chkLembrar" class="form-check-label">Lembrar de mim</label>
                                    </div>
                                    <a href="/recuperarsenha.html" class="forgot-password-link">Esqueceu a senha?</a>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 btn-lg btn-login" id="btnLogin">
                                    <span class="btn-text">Entrar</span>
                                    <span class="btn-loading d-none">
                                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                        Entrando...
                                    </span>
                                </button>
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

        // função para mostrar/ocultar loading no botão
        function setLoadingButton(loading) {
            const btn = $('#btnLogin');
            const btnText = btn.find('.btn-text');
            const btnLoading = btn.find('.btn-loading');

            if (loading) {
                btn.prop('disabled', true);
                btnText.addClass('d-none');
                btnLoading.removeClass('d-none');
            } else {
                btn.prop('disabled', false);
                btnText.removeClass('d-none');
                btnLoading.addClass('d-none');
            }
        }

        // função para limpar erros
        function clearErrors() {
            $('#emailError').text('').parent().find('.form-control').removeClass('is-invalid');
            $('#passwordError').text('').parent().find('.form-control').removeClass('is-invalid');
        }

        // função para mostrar erro no campo
        function showFieldError(fieldId, message) {
            const errorSpan = $('#' + fieldId + 'Error');
            const input = $('#' + fieldId);
            errorSpan.text(message);
            input.addClass('is-invalid');
        }

        // função de callback para processar o login
        function handleLogin(response) {
            setLoadingButton(false);

            // verifica se o login foi bem-sucedido
            if (response.success) {
                // exibe mensagem de sucesso
                Swal.fire({
                    title: 'Sucesso!',
                    text: 'Login realizado com sucesso!',
                    icon: 'success',
                    confirmButtonText: 'Ok',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    // redireciona para a página inicial
                    window.location.href = "{{ route('user.dashboard') }}";
                });
            } else {
                // exibe uma mensagem de erro
                Swal.fire({
                    title: 'Erro!',
                    text: response.message || 'Falha ao realizar login. Verifique suas credenciais.',
                    icon: 'error',
                    confirmButtonText: 'Ok',
                    confirmButtonColor: '#dc3545'
                });

                // adiciona classe de erro nos campos
                $('#email').addClass('is-invalid');
                $('#password').addClass('is-invalid');
            }
        }

        // quando o formulário for enviado
        $('#loginForm').submit(function(event) {
            event.preventDefault();

            // limpa erros anteriores
            clearErrors();

            let isValid = true;
            const email = $('#email').val().trim();
            const password = $('#password').val();

            // validação do e-mail
            if (!email) {
                showFieldError('email', 'Por favor, informe seu e-mail.');
                isValid = false;
            } else if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
                showFieldError('email', 'Por favor, informe um endereço de e-mail válido.');
                isValid = false;
            }

            // validação da senha
            if (!password) {
                showFieldError('password', 'Por favor, informe sua senha.');
                isValid = false;
            } else if (password.length < 6) {
                showFieldError('password', 'A senha deve conter pelo menos 6 caracteres.');
                isValid = false;
            }

            // se não for válido, não envia
            if (!isValid) {
                return false;
            }

            // ativa loading no botão
            setLoadingButton(true);

            // pega os dados do formulário
            const data = $(this).serialize();

            // envia os dados do formulário para a rota de login
            $.ajax({
                url: loginRoute,
                method: 'POST',
                data: data,
                success: handleLogin,
                error: function(xhr) {
                    setLoadingButton(false);
                    let errorMessage = 'Ocorreu um erro ao processar sua solicitação.';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.status === 422) {
                        errorMessage = 'Dados inválidos. Verifique as informações fornecidas.';
                    } else if (xhr.status === 500) {
                        errorMessage = 'Erro interno do servidor. Tente novamente mais tarde.';
                    }

                    Swal.fire({
                        title: 'Erro!',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'Ok',
                        confirmButtonColor: '#dc3545'
                    });
                }
            });
        });

        // remove classe de erro quando o usuário começar a digitar
        $('#email, #password').on('input', function() {
            $(this).removeClass('is-invalid');
            $('#' + $(this).attr('id') + 'Error').text('');
        });

        // Toggle para mostrar/ocultar senha
        $('#togglePassword').on('click', function() {
            const passwordInput = $('#password');
            const eyeIcon = $('#eyeIcon');
            const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';

            passwordInput.attr('type', type);

            if (type === 'text') {
                eyeIcon.removeClass('bi-eye').addClass('bi-eye-slash');
                $(this).attr('aria-label', 'Ocultar senha');
            } else {
                eyeIcon.removeClass('bi-eye-slash').addClass('bi-eye');
                $(this).attr('aria-label', 'Mostrar senha');
            }
        });

        // Adiciona efeito de foco suave nos campos
        $('.form-control').on('focus', function() {
            $(this).closest('.form-floating').addClass('focused');
        }).on('blur', function() {
            if (!$(this).val()) {
                $(this).closest('.form-floating').removeClass('focused');
            }
        });

        // Animação de entrada para os campos
        $('.form-floating').each(function(index) {
            $(this).css({
                'animation': 'fadeInUp 0.6s ease ' + (index * 0.1) + 's both'
            });
        });
    </script>
</body>

</html>
