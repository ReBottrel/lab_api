<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>ADMIN - LOCI</title>
    <link rel="stylesheet" href="{{ asset('adm/assets/bootstrap/css/bootstrap.min.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="{{ asset('adm/assets/fonts/fontawesome-all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adm/assets/fonts/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adm/assets/fonts/fontawesome5-overrides.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adm/assets/css/menu.min.css') }}">
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="login-card">
                    <div class="card shadow-lg o-hidden border-0 my-5">
                        <div class="card-body p-0">
                            <div class="login-header">
                                <img src="{{ asset('adm/assets/img/logo.svg') }}" alt="Logo">
                                <h4 class="text-dark">Área Administrativa</h4>
                                <p class="text-muted">Acesse o painel de controle</p>
                            </div>
                            
                            <div class="login-form-container">
                                <form class="user" action="{{ route('admin.entrar') }}" id="form-login">
                                    @csrf
                                    <div class="mb-4">
                                        <input class="form-control form-control-user" 
                                            type="email" 
                                            id="exampleInputEmail" 
                                            placeholder="Digite seu e-mail"
                                            name="email"
                                            autocomplete="email"
                                            required>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <input class="form-control form-control-user" 
                                            type="password" 
                                            id="exampleInputPassword" 
                                            placeholder="Digite sua senha"
                                            name="password"
                                            autocomplete="current-password"
                                            required>
                                    </div>
                                    
                                    <button class="btn fw-bold link-light d-block btn-user w-100" 
                                        type="button" 
                                        id="btn-login">
                                        <span>Entrar</span>
                                        <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </form>
                                
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="forgot-password.html">
                                        <i class="fas fa-lock"></i>
                                        <span>Esqueceu sua senha?</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('adm/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('adm/assets/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('adm/assets/js/script.min.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        $(document).ready(function() {
            const form = $('#form-login');
            const emailInput = $('#exampleInputEmail');
            const passwordInput = $('#exampleInputPassword');
            const loginButton = $('#btn-login');

            // Função para validar email
            function isValidEmail(email) {
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
            }

            // Função para mostrar erro
            function showError(message) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: message,
                    confirmButtonColor: '#6A4486',
                    timer: 3000,
                    timerProgressBar: true
                });
            }

            // Função para mostrar loading
            function toggleLoading(show) {
                if (show) {
                    loginButton.prop('disabled', true);
                    loginButton.html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Entrando...');
                } else {
                    loginButton.prop('disabled', false);
                    loginButton.html('<span>Entrar</span><i class="fas fa-arrow-right ms-2"></i>');
                }
            }

            // Função para validar formulário
            function validateForm() {
                const email = emailInput.val().trim();
                const password = passwordInput.val().trim();

                if (!email) {
                    showError('Por favor, digite seu e-mail');
                    emailInput.focus();
                    return false;
                }

                if (!isValidEmail(email)) {
                    showError('Por favor, digite um e-mail válido');
                    emailInput.focus();
                    return false;
                }

                if (!password) {
                    showError('Por favor, digite sua senha');
                    passwordInput.focus();
                    return false;
                }

                if (password.length < 6) {
                    showError('A senha deve ter pelo menos 6 caracteres');
                    passwordInput.focus();
                    return false;
                }

                return true;
            }

            // Função para realizar o login
            async function realizarLogin() {
                if (!validateForm()) return;

                toggleLoading(true);

                try {
                    const response = await $.ajax({
                        url: "{{ route('admin.entrar') }}",
                        type: 'POST',
                        data: form.serialize(),
                        dataType: 'json'
                    });

                    if (response === 'painel') {
                        // Mostrar mensagem de sucesso antes de redirecionar
                        await Swal.fire({
                            icon: 'success',
                            title: 'Sucesso!',
                            text: 'Login realizado com sucesso',
                            timer: 1500,
                            timerProgressBar: true,
                            showConfirmButton: false
                        });
                        
                        window.location.href = response;
                    } else {
                        throw new Error('Resposta inválida do servidor');
                    }

                } catch (error) {
                    console.error('Erro:', error);
                    
                    if (error.responseJSON?.invalid) {
                        showError(error.responseJSON.invalid);
                    } else {
                        showError('Ocorreu um erro ao tentar fazer login. Tente novamente.');
                    }
                } finally {
                    toggleLoading(false);
                }
            }

            // Event Listeners
            loginButton.click(realizarLogin);

            form.on('keypress', function(e) {
                if (e.which == 13) {
                    e.preventDefault();
                    realizarLogin();
                }
            });

            // Limpar campos quando houver erro
            emailInput.on('input', function() {
                $(this).removeClass('is-invalid');
            });

            passwordInput.on('input', function() {
                $(this).removeClass('is-invalid');
            });

            // Configurar AJAX headers
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>
</body>

</html>
