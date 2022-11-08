<div class="col-3">
    <div class="list-group">
        <a href="{{ route('user.dados') }}" class="list-group-item list-group-item-action @if(request()->routeIs('user.dados*')) active @else  @endif">
            <i class="bi-person fs-6"></i> Dados Pessoais
        </a>

        <a href="{{ route('user.address') }}" class="list-group-item @if(request()->routeIs('user.address*')) active @else  @endif list-group-item-action">
            <i class="bi-house-door fs-6"></i> Endereço
        </a>
        <div class="dropdown">
            <a href="#"
                class="list-group-item list-group-item-action @if(request()->routeIs('user.dashboard*')) active @else  @endif  dropdown-toggle" role="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                <i class="bi-truck fs-6"></i> Pedidos
            </a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('user.dashboard') }}">Efetuados</a></li>
                <li><a class="dropdown-item" href="#">Concluídos</a></li>
                <li><a class="dropdown-item" href="#">Cancelados</a></li>
            </ul>
        </div>
        <a href="#" class="list-group-item list-group-item-action">
            <i class="bi-lock fs-6"></i> Alterar Senha
        </a>
        <a href="{{ route('logout') }}"
            onclick="event.preventDefault();
        document.getElementById('logout-form').submit();"
            class="list-group-item list-group-item-action">
            <i class="bi-door-open fs-6"></i> Sair
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</div>
