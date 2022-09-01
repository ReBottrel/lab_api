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
</head>

<body class="bg-gradient-primary" style="background: var(--bs-green);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-12 col-xl-10">
                <div class="card shadow-lg o-hidden border-0 my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 text-center d-none d-lg-flex align-self-center">
                                <div class="flex-grow-1 bg-login-image" style="width: 475px;"><img
                                        class="img-fluid shadow-sm" src="{{ asset('adm/assets/img/logo.svg') }}" loading="lazy"
                                        width="40%"></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h4 class="text-dark mb-4">Bem Vindo</h4>
                                    </div>
                                    <form class="user" action="{{ route('admin.entrar') }}" id="form-login">
                                        @csrf
                                        <div class="mb-3"><input class="form-control form-control-user" type="email"
                                                id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Email"
                                                name="email"></div>
                                        <div class="mb-3"><input class="form-control form-control-user"
                                                type="password" id="exampleInputPassword" placeholder="Senha"
                                                name="password"></div>
                                        <div class="mb-3">
                                            <div class="custom-control custom-checkbox small"></div>
                                        </div><button class="btn fw-bold link-light d-block btn-user w-100"
                                            type="button" id="btn-login" style="background: #684286;">Entrar</button>
                                        <hr>
                                        <hr>
                                    </form>
                                    <div class="text-center"><a class="small" href="forgot-password.html">Esqueceu a
                                            Senha?</a></div>
                                    <div class="text-center"></div>
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
    <script src="{{ asset('adm/assets/js/main.js') }}"></script>
</body>

</html>
