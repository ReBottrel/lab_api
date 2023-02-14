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
<div class="user-configs">
    <div class="row">
        <div class="col-12">
            <div class="user-configs-item">
                <a href="{{ route('vet.logout') }}">
                    <i class="fa-solid fa-sign-out"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </div>
</div>
