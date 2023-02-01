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

    <title>LABLOCI - E-COMMERCE</title>
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
                @if (Auth::check())
                    <a href="{{ route('user.dashboard') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-person"></i> Minha Conta
                    </a>
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                  document.getElementById('logout-form').submit();"
                        class="btn btn-primary btn-sm">
                        <i class="bi bi-door-open"></i> Sair
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                @else
                    <a href="{{ route('login') }}" type="button" class="btn btn-primary">Entrar</a>
                    <a href="/cadastro.html" type="button" class="btn btn-secondary">Cadastar-se</a>
                @endif

            </div>
        </header>
    </div>
    <div class="d-flex flex-column wrapper mb-5">
        <main class="flex-fill">
            @yield('content')
        </main>

    </div>
    <!-- Footer -->
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
                            <a href="{{ route('privacy') }}" class="text-reset">Politicas de privacidade</a>
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
    <!-- Footer -->
    {{-- <footer class="border-top border-primary text-muted text-bg-primary">
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
                    Rua Coronel Durães, 170, slj 01,
                    Bela Vista - Lagoa Santa l MG Labloci © 2022 Todos os Direitos Reservados
                </div>

            </div>
        </div>
    </footer> --}}
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
    <script src="{{ asset('loja/js/script.js') }}"></script>
    @yield('scripts')
    @if (Session::has('success'))
        <script type="text/javascript">
            Swal.fire({
                title: 'Sucesso!',
                icon: 'success',
                text: "{{ Session::get('success') }}",
                timer: 5000,
                type: 'success'
            }).then((result) => {
                // Reload the Page
                location.reload();
            });
        </script>
    @endif
</body>

</html>
