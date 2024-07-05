<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>ADMIN - LOCI</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('adm/assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="{{ asset('adm/assets/fonts/fontawesome-all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adm/assets/fonts/font-awesome.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('adm/assets/fonts/fontawesome5-overrides.min.css') }}">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.11/themes/default/style.min.css" />
    <script src="https://kit.fontawesome.com/0ab2bcde1c.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('adm/assets/css/style.min.css') }}">
</head>

<body id="page-top">
    <div id="wrapper">
        <nav class="navbar navbar-light shadow-lg align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0"
            style="background: var(--bs-light);color: var(--bs-dark);">
            <div class="container-fluid d-flex flex-column p-0"><img class="img-fluid"
                    src="{{ asset('adm/assets/img/logo.svg') }}" width="100%" loading="lazy"
                    style="width: 150px;margin: 10px;padding: 10px;">
                <hr class="sidebar-divider my-0">
                <ul class="navbar-nav text-light m-side" id="accordionSidebar">
                    @if (auth()->user()->permission == 10)
                        <li class="nav-item"><a class="nav-link active" href="{{ route('admin') }}"><i
                                    class="fas fa-tachometer-alt"
                                    style="color: var(--bs-dark);"></i><span>Home</span></a>
                        </li>
                    @endif
                    <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#"
                            style="color: var(--bs-dark);"><i class="fas fa-table"
                                style="color: var(--bs-dark);"></i><span>Pedidos</span></a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            @if (auth()->user()->permission == 10)
                                <li><a class="dropdown-item" href="{{ route('orders.all') }}">Todos os pedidos</a></li>
                                <li><a class="dropdown-item" href="{{ route('orders.vet') }}">Pedidos do veterinario</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('orders.sistema') }}">Pedidos do
                                        sistema</a>
                                </li>
                                <li><a class="dropdown-item" href="#">Concluídos</a></li>
                            @endif
                            @if (auth()->user()->permission == 10 || auth()->user()->association_id == 2)
                                <li><a class="dropdown-item" href="{{ route('order.create.painel') }}">Criar Pedido</a>
                                </li>
                            @endif
                            @if (auth()->user()->association_id == 2)
                                <li><a class="dropdown-item" href="{{ route('orders.pega') }}">Ver
                                        Pedidos</a>
                                </li>
                            @endif
                            @if (auth()->user()->permission == 8 || auth()->user()->association_id != 2)
                                <li><a class="dropdown-item" href="{{ route('buscar.pedido.parceiro') }}">Buscar
                                        Pedido</a>
                                </li>
                            @endif
                        </ul>
                    </li>

                    @if (auth()->user()->permission == 10 || auth()->user()->association_id == 2)
                        <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#"
                                style="color: var(--bs-dark);"><i class="fa-solid fa-horse"
                                    style="color: var(--bs-dark);"></i><span>Animais</span></a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">

                                <li><a class="dropdown-item" href="{{ route('animais') }}">Todos os animais</a>
                                </li>

                                <li><a class="dropdown-item" href="{{ route('species') }}">Espécie</a></li>
                                <li><a class="dropdown-item" href="{{ route('breeds') }}">Raça</a></li>
                                <li><a class="dropdown-item" href="{{ route('fur') }}">Pelagem</a></li>
                                <li><a class="dropdown-item" href="{{ route('marks') }}">Marcas da Resenha</a></li>

                            </ul>
                        </li>
                    @endif
                    @if (auth()->user()->permission == 10)
                        <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#"
                                style="color: var(--bs-dark);"><i class="fas fa-table"
                                    style="color: var(--bs-dark);"></i><span>Alelos</span></a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                @if (auth()->user()->permission == 10)
                                    <li><a class="dropdown-item" href="{{ route('alelos.get.api') }}">Importar API</a>
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('alelos.create') }}">Criar/Editar</a>
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('import.txt.view') }}">Importar TXT</a>
                                    </li>
                                @endif

                            </ul>
                        </li>
                    @endif
                    @if (auth()->user()->permission == 10)
                        <li class="nav-item">
                            <a class="nav-link " href="{{ route('ordem.servico.all') }}"><i class="fas fa-user"
                                    style="color: var(--bs-dark);"></i><span>Ordem de Serviços</span></a>
                        </li>
                    @endif
                    @if (auth()->user()->permission == 10)
                        <li class="nav-item">
                            <a class="nav-link " href="{{ route('laudos') }}"><i class="fas fa-user"
                                    style="color: var(--bs-dark);"></i><span>Laudos</span></a>
                        </li>
                    @endif
                    @if (auth()->user()->permission == 10)
                        <li class="nav-item">
                            <a class="nav-link " href="{{ route('users') }}"><i class="fas fa-user"
                                    style="color: var(--bs-dark);"></i><span>Usuários</span></a>
                        </li>
                    @endif
                    @if (auth()->user()->permission == 10)
                        <li class="nav-item"><a class="nav-link" href="{{ route('exames') }}"
                                style="color: var(--bs-dark);"><i class="far fa-list-alt"
                                    style="color: var(--bs-dark);"></i><span>Exames</span></a>
                        </li>
                    @endif
                    @if (auth()->user()->permission == 10 || auth()->user()->association_id == 2)
                        <li class="nav-item"><a class="nav-link" href="{{ route('owners') }}"><i
                                    class="fas fa-users"
                                    style="color: var(--bs-dark);"></i><span>Proprietarios</span></a>
                        </li>
                    @endif
                    @if (auth()->user()->permission == 10)
                        <li class="nav-item"><a class="nav-link" href="{{ route('cupons') }}"
                                style="color: var(--bs-dark);"><i class="fa-solid fa-percent"
                                    style="color: var(--bs-dark);"></i><span>Cupons</span></a>
                        </li>
                    @endif
                    @if (auth()->user()->permission == 10 || auth()->user()->association_id == 2)
                        <li class="nav-item"><a class="nav-link" href="{{ route('techinicals') }}"
                                style="color: var(--bs-dark);"><i class="fas fa-user-nurse"
                                    style="color: var(--bs-dark);"></i><span>Técnicos</span></a>
                        </li>
                    @endif
                    @if (auth()->user()->permission == 10)
                        <li class="nav-item"><a class="nav-link" href="{{ route('parceiros') }}"
                                style="color: var(--bs-dark);"><i class="fas fa-user-nurse"
                                    style="color: var(--bs-dark);"></i><span>Parceiros</span></a>
                        </li>
                    @endif
                    @if (auth()->user()->permission == 10)
                        <li class="nav-item"><a class="nav-link" href="{{ route('relatorios') }}"
                                style="color: var(--bs-dark);"><i class="fas fa-user-nurse"
                                    style="color: var(--bs-dark);"></i><span>Relatórios</span></a>
                        </li>
                    @endif
                    @if (auth()->user()->permission == 10)
                        <li class="nav-item"><a class="nav-link" href="{{ route('configs') }}"><i
                                    class="fa fa-gear"
                                    style="color: var(--bs-dark);"></i><span>Configurações</span></a>
                        </li>
                    @endif

                </ul>
                <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0"
                        id="sidebarToggle" type="button"></button></div>
            </div>
        </nav>

        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content" style="margin-top: 0;">
                <nav class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top">
                    <div class="container-fluid"><button class="btn btn-link d-md-none rounded-circle me-3"
                            id="sidebarToggleTop-1" type="button"><i class="fas fa-bars"></i></button>
                        <form class="d-none d-sm-inline-block me-auto ms-md-3 my-2 my-md-0 mw-100 navbar-search">
                            <div class="input-group"></div>
                        </form>
                        <ul class="navbar-nav flex-nowrap ms-auto">
                            <li class="nav-item dropdown d-sm-none no-arrow"><a class="dropdown-toggle nav-link"
                                    aria-expanded="false" data-bs-toggle="dropdown" href="#"><i
                                        class="fas fa-search"></i></a>
                                <div class="dropdown-menu dropdown-menu-end p-3 animated--grow-in"
                                    aria-labelledby="searchDropdown">
                                    <form class="me-auto navbar-search w-100">
                                        <div class="input-group"><input class="bg-light form-control border-0 small"
                                                type="text" placeholder="Search for ...">
                                            <div class="input-group-append"><button class="btn btn-primary py-0"
                                                    type="button"><i class="fas fa-search"></i></button></div>
                                        </div>
                                    </form>
                                </div>
                            </li>
                            <li class="nav-item dropdown no-arrow mx-1">
                                <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link"
                                        aria-expanded="false" data-bs-toggle="dropdown" href="#"><span
                                            class="badge bg-danger badge-counter">0+</span><i
                                            class="fas fa-bell fa-fw"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end dropdown-list animated--grow-in">
                                        <h6 class="dropdown-header">Notificaões</h6>
                                        <a class="dropdown-item text-center small text-gray-500" href="#">Todas
                                            a notificaões</a>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item dropdown no-arrow mx-1">
                                <div class="shadow dropdown-list dropdown-menu dropdown-menu-end"
                                    aria-labelledby="alertsDropdown"></div>
                            </li>
                            <div class="d-none d-sm-block topbar-divider"></div>
                            <li class="nav-item dropdown no-arrow">
                                <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link"
                                        aria-expanded="false" data-bs-toggle="dropdown" href="#"><span
                                            class="d-none d-lg-inline me-2 text-gray-600 small">{{ auth()->user()->name }}</span><img
                                            class="border rounded-circle img-profile"
                                            src="{{ asset('adm/assets/img/avatars/avatar5.jpeg') }}"></a>
                                    <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in"><a
                                            class="dropdown-item" href="#"><i
                                                class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Perfil</a><a
                                            class="dropdown-item" href="{{ route('configs') }}"><i
                                                class="fas fa-cogs fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Configurações</a>
                                        <div class="dropdown-divider"></div><a class="dropdown-item"
                                            href="{{ route('admin.logout') }}"><i
                                                class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Sair</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
                <div class="content">
                    @yield('content')
                </div>
            </div>

        </div><a class="border rounded d-inline scroll-to-top" href="#page-top"
            style="background: var(--bs-teal);"><i class="fas fa-angle-up"></i></a>
    </div>

    <script src="{{ asset('adm/assets/js/jquery.min.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="{{ asset('adm/assets/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.11/jstree.min.js"></script>
    <script src="{{ asset('adm/assets/js/fabric.min.js') }}"></script>
    <script src="{{ asset('adm/assets/js/script.min.js') }}"></script>
    <script src="{{ asset('adm/assets/js/main.js') }}"></script>
    @yield('js')
    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: '{{ $errors->first() }}'
            });
        </script>
    @endif
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
    @if (Session::has('error'))
        <script type="text/javascript">
            Swal.fire({
                title: 'Oops!',
                icon: 'error',
                text: "{{ Session::get('error') }}",
                timer: 5000,
                type: 'error'
            }).then((result) => {
                // Reload the Page
                location.reload();
            });
        </script>
    @endif

    @component('layouts.partials.javascript')
    @endcomponent
</body>

</html>
