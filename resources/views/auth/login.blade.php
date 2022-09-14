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
                    <form class="col-sm-10 col-md-8 col-lg-6" action="{{ route('login') }}" method="post">
                        @csrf
                        <h3 class="text-primary">Identifique-se, por favor</h3>

                        <div class="form-floating mb-3">
                            <input type="email" name="email" id="txtEmail" class="form-control" placeholder=" "
                                autofocus>
                            <label for="txtEmail">E-mail</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" name="password" id="txtSenha" class="form-control" placeholder=" ">
                            <label for="txtSenha">Senha</label>
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
    <script src="{{ asset('loja/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
