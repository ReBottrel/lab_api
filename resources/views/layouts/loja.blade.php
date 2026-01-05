<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('loja/node_modules/bootstrap-icons/font/bootstrap-icons.css') }}">
    <script src="https://kit.fontawesome.com/0ab2bcde1c.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('loja/css/style.min.css') }}">

    <style>
        :root {
            --primary-color: #6366f1;
            --primary-dark: #4f46e5;
            --secondary-color: #8b5cf6;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --bg-light: #f8fafc;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text-dark);
            background: var(--bg-light);
        }

        /* Top Bar */
        .top-bar {
            background: linear-gradient(135deg, var(--text-dark), #334155);
            color: #cbd5e1;
            padding: 0.625rem 0;
            font-size: 0.8125rem;
        }

        .top-bar-item {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-right: 1.5rem;
        }

        .top-bar-item i {
            color: var(--primary-color);
            font-size: 0.875rem;
        }

        .top-bar-link {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .top-bar-link:hover {
            color: white;
        }

        /* Header Styles */
        .main-header {
            background: #fff;
            box-shadow: 
                0 2px 8px rgba(0, 0, 0, 0.04),
                0 4px 16px rgba(0, 0, 0, 0.06);
            padding: 1.5rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .main-header.scrolled {
            padding: 1rem 0;
            box-shadow: 
                0 4px 12px rgba(0, 0, 0, 0.08),
                0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .logo-wrapper {
            transition: transform 0.3s ease;
            position: relative;
        }

        .logo-wrapper::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            transition: width 0.3s ease;
            border-radius: 2px;
        }

        .logo-wrapper:hover::after {
            width: 100%;
        }

        .logo-wrapper:hover {
            transform: scale(1.05);
        }

        .search-wrapper {
            max-width: 600px;
            margin: 0 auto;
        }

        .search-input-group {
            position: relative;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            border-radius: 14px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .search-input-group:focus-within {
            box-shadow: 0 4px 20px rgba(99, 102, 241, 0.15);
            transform: translateY(-2px);
        }

        .search-input-group input {
            border: 2px solid var(--border-color);
            border-right: none;
            padding: 1rem 1.25rem 1rem 3.25rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #fafafa;
        }

        .search-input-group input:focus {
            border-color: var(--primary-color);
            box-shadow: none;
            outline: none;
            background: white;
        }

        .search-input-group::before {
            content: '\F52A';
            font-family: 'bootstrap-icons';
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1.125rem;
            z-index: 10;
            transition: color 0.3s ease;
        }

        .search-input-group:focus-within::before {
            color: var(--primary-color);
        }

        .search-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            padding: 1rem 2rem;
            color: white;
            transition: all 0.3s ease;
            border-radius: 0 14px 14px 0;
            font-weight: 600;
            position: relative;
            overflow: hidden;
        }

        .search-btn::before {
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

        .search-btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .search-btn:hover {
            background: linear-gradient(135deg, var(--primary-dark), #7c3aed);
            transform: translateX(2px);
        }

        .header-actions {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            justify-content: flex-end;
        }

        .cart-icon {
            position: relative;
            background: #f1f5f9;
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid var(--border-color);
        }

        .cart-icon:hover {
            background: white;
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
        }

        .cart-icon i {
            font-size: 1.25rem;
            color: var(--text-dark);
        }

        .cart-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 700;
            border: 2px solid white;
        }

        .btn-header {
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.625rem;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .btn-header-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
        }

        .btn-header-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
            color: white;
        }

        .btn-header-secondary {
            background: #f1f5f9;
            color: var(--text-dark);
            border: 2px solid var(--border-color);
        }

        .btn-header-secondary:hover {
            background: white;
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        /* Footer Styles */
        .main-footer {
            background: linear-gradient(135deg, #1e293b, #334155);
            color: #e2e8f0;
            padding: 4rem 0 0;
            margin-top: 4rem;
        }

        .footer-section-title {
            color: white;
            font-weight: 700;
            font-size: 1.125rem;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-section-title i {
            color: var(--primary-color);
            font-size: 1.25rem;
        }

        .footer-description {
            color: #cbd5e1;
            line-height: 1.7;
            font-size: 0.95rem;
        }

        .footer-link {
            color: #cbd5e1;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            padding: 0.375rem 0;
            font-size: 0.95rem;
        }

        .footer-link:hover {
            color: white;
            transform: translateX(5px);
        }

        .footer-contact-item {
            display: flex;
            align-items: flex-start;
            gap: 0.875rem;
            margin-bottom: 1rem;
            color: #cbd5e1;
            font-size: 0.95rem;
        }

        .footer-contact-item i {
            color: var(--primary-color);
            font-size: 1.125rem;
            margin-top: 0.125rem;
        }

        .partner-logos {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .partner-logo {
            background: white;
            padding: 0.75rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            max-width: 120px;
        }

        .partner-logo:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .partner-logo img {
            width: 100%;
            height: auto;
        }

        .footer-copyright {
            background: rgba(0, 0, 0, 0.2);
            padding: 1.5rem 0;
            margin-top: 3rem;
            text-align: center;
            color: #cbd5e1;
            font-size: 0.9rem;
        }

        .footer-copyright a {
            color: white;
            font-weight: 600;
            text-decoration: none;
        }

        .footer-copyright a:hover {
            color: var(--primary-color);
        }

        /* Main Content */
        .main-content {
            min-height: calc(100vh - 400px);
            background: var(--bg-light);
        }

        @media (max-width: 991px) {
            .header-actions {
                justify-content: center;
                margin-top: 1rem;
            }

            .search-wrapper {
                margin-bottom: 1rem;
            }

            .btn-header {
                font-size: 0.85rem;
                padding: 0.5rem 1rem;
            }

            .partner-logos {
                justify-content: center;
            }

            .footer-contact-item {
                font-size: 0.875rem;
            }
        }
    </style>

    <title>LABLOCI - E-COMMERCE</title>
</head>

<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <span class="top-bar-item">
                        <i class="bi bi-telephone-fill"></i>
                        <a href="tel:3136814331" class="top-bar-link">(31) 3681-4331</a>
                    </span>
                    <span class="top-bar-item">
                        <i class="bi bi-whatsapp"></i>
                        <a href="https://wa.me/5531997370135" class="top-bar-link" target="_blank">(31) 99737-0135</a>
                    </span>
                </div>
                <div class="col-md-6 text-md-end">
                    <span class="top-bar-item">
                        <i class="bi bi-envelope-fill"></i>
                        <a href="mailto:atendimento@locilab.com.br" class="top-bar-link">atendimento@locilab.com.br</a>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header class="main-header">
        <div class="container">
            <div class="row align-items-center">
                <!-- Logo -->
                <div class="col-lg-3 col-md-12 text-center text-lg-start mb-3 mb-lg-0">
                    <a href="/" class="logo-wrapper d-inline-block">
                        <img src="{{ asset('loja/assets/img/logo.svg') }}" alt="LABLOCI" style="max-height: 50px;">
                    </a>
                </div>

                <!-- Search -->
                <div class="col-lg-6 col-md-12 mb-3 mb-lg-0">
                    <div class="search-wrapper">
                        <form role="search">
                            <div class="input-group search-input-group">
                                <input class="form-control" type="search" placeholder="Busque aqui seus produtos..." />
                                <button class="search-btn" type="submit">
                                    Buscar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Actions -->
                <div class="col-lg-3 col-md-12">
                    <div class="header-actions">
                        <!-- Cart Icon -->
                        <a href="#" class="cart-icon">
                            <i class="bi bi-cart3"></i>
                            <span class="cart-badge">0</span>
                        </a>

                        @if (Auth::check())
                            <a href="{{ route('user.dashboard') }}" class="btn-header btn-header-primary">
                                <i class="bi bi-person-circle"></i> 
                                <span class="d-none d-xl-inline">Minha Conta</span>
                            </a>
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="btn-header btn-header-secondary">
                                <i class="bi bi-box-arrow-right"></i>
                                <span class="d-none d-xl-inline">Sair</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn-header btn-header-primary">
                                <i class="bi bi-box-arrow-in-right"></i> Entrar
                            </a>
                            <a href="/cadastro.html" class="btn-header btn-header-secondary">
                                <i class="bi bi-person-plus"></i> 
                                <span class="d-none d-xl-inline">Cadastrar</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="row">
                <!-- About -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <h6 class="footer-section-title">
                        <i class="fas fa-dna"></i>
                        LABLOCI
                    </h6>
                    <p class="footer-description">
                        Somos uma equipe de cientistas fiéis aos princípios da ética, da competência técnica e da
                        segurança dos resultados produzidos. Excelência em análises laboratoriais.
                    </p>
                </div>

                <!-- Partners -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="footer-section-title">
                        <i class="fas fa-handshake"></i>
                        Parceiros
                    </h6>
                    <div class="partner-logos">
                        <div class="partner-logo">
                            <img src="{{ asset('loja/assets/img/isag.jpg') }}" alt="ISAG">
                        </div>
                        <div class="partner-logo">
                            <img src="{{ asset('loja/assets/img/mpa.jpg') }}" alt="MPA">
                        </div>
                        <div class="partner-logo">
                            <img src="{{ asset('loja/assets/img/inmetro.jpg') }}" alt="Inmetro">
                        </div>
                    </div>
                </div>

                <!-- Links -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h6 class="footer-section-title">
                        <i class="fas fa-link"></i>
                        Links Úteis
                    </h6>
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('privacy') }}" class="footer-link">
                            <i class="bi bi-chevron-right me-2"></i>Políticas de Privacidade
                        </a>
                        <a href="#!" class="footer-link">
                            <i class="bi bi-chevron-right me-2"></i>Termos e Condições
                        </a>
                        <a href="#!" class="footer-link">
                            <i class="bi bi-chevron-right me-2"></i>Meus Pedidos
                        </a>
                        <a href="#!" class="footer-link">
                            <i class="bi bi-chevron-right me-2"></i>Suporte
                        </a>
                    </div>
                </div>

                <!-- Contact -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h6 class="footer-section-title">
                        <i class="fas fa-envelope"></i>
                        Contato
                    </h6>
                    <div class="footer-contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Rua Coronel Durães, 170, slj 01<br>Bela Vista - Lagoa Santa / MG</span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>atendimento@locilab.com.br</span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="fas fa-phone"></i>
                        <span>(31) 3681-4331</span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="fa-brands fa-whatsapp"></i>
                        <span>(31) 99737-0135</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="footer-copyright">
            © 2022 Todos os Direitos Reservados - 
            <a href="https://locilab.com.br/">Labloci</a>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
    <script src="{{ asset('loja/js/script.js') }}"></script>
    
    <script>
        // Header scroll effect
        $(window).scroll(function() {
            if ($(this).scrollTop() > 50) {
                $('.main-header').addClass('scrolled');
            } else {
                $('.main-header').removeClass('scrolled');
            }
        });
    </script>
    
    @yield('scripts')
    
    @if (Session::has('success'))
        <script type="text/javascript">
            Swal.fire({
                title: 'Sucesso!',
                icon: 'success',
                text: "{{ Session::get('success') }}",
                timer: 5000,
                confirmButtonColor: '#6366f1'
            });
        </script>
    @endif
</body>

</html>
