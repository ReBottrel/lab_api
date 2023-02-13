<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LOCILAB - VETERINARIO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/0ab2bcde1c.js" crossorigin="anonymous"></script>
    <link href="{{ asset('vet/css/main.min.css') }}" rel="stylesheet">

</head>

<body>
    <div class="top">
        <div class="row align-items-center">
            <div class="col-4">
                <div class="menu">
                    <button class="btn-menu btn-menu-close"><i class="fas fa-bars"></i></button>
                </div>
            </div>
            <div class="col-4">
                <div class="logo">
                    <img src="{{ asset('vet/img/logo.svg') }}" alt="">
                </div>
            </div>
            <div class="col-4 d-flex justify-content-end">
                <div class="user">
                    <div class="user-icon">
                        <i class="fa-solid fa-user"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        @yield('content')
    </div>
    <div class="menu-mobile">
        <div class="menu-mobile-content">
            <div class="menu-mobile-header">
                <div class="menu-mobile-header-logo">
                    <img src="{{ asset('vet/img/logo.svg') }}" alt="">
                </div>
                <div class="menu-mobile-header-close">
                    <button class="btn-menu btn-menu-close"><i class="fas fa-times"></i></button>
                </div>
            </div>
            <div class="menu-mobile-body">
                <div class="menu-mobile-body-item home">

                    <div class="menu-mobile-body-item-icon">
                        <i class="fa-solid fa-home"></i>
                    </div>
                    <div class="menu-mobile-body-item-text">
                        <p>Home</p>
                    </div>

                </div>
                <div class="menu-mobile-body-item">
                    <div class="menu-mobile-body-item-icon">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="menu-mobile-body-item-text">
                        <p>Perfil</p>
                    </div>
                </div>
                <div class="menu-mobile-body-item">
                    <div class="menu-mobile-body-item-icon">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="menu-mobile-body-item-text">
                        <p>Perfil</p>
                    </div>
                </div>
                <div class="menu-mobile-body-item">
                    <div class="menu-mobile-body-item-icon">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="menu-mobile-body-item-text">
                        <p>Perfil</p>
                    </div>
                </div>
                <div class="menu-mobile-body-item">
                    <div class="menu-mobile-body-item-icon">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="menu-mobile-body-item-text">
                        <p>Perfil</p>
                    </div>
                </div>
                <div class="menu-mobile-body-item">
                    <div class="menu-mobile-body-item-icon">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="menu-mobile-body-item-text">
                        <p>Perfil</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.6.2/modernizr.min.js"></script>
    <script src="{{ asset('adm/assets/js/fabric.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.btn-menu').click(function() {
                $('.menu-mobile').toggleClass('menu-mobile-active');

            });
            $('.home').click(function() {
                window.location.href = "{{ route('vet.index') }}";
            });
        });
    </script>
    @yield('js')
</body>

</html>
