<div class="col-md-3 col-12 mb-4 mb-md-0">
    <style>
        .user-menu-wrapper {
            position: sticky;
            top: 2rem;
            z-index: 10;
        }

        .user-menu-card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 
                0 4px 6px -1px rgba(0, 0, 0, 0.02),
                0 10px 15px -3px rgba(0, 0, 0, 0.04),
                0 20px 25px -5px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.8);
            overflow: visible; /* Changed to visible for dropdowns if needed, or keeping interactions smooth */
            position: relative;
        }

        /* Glass / Mesh Header */
        .user-menu-header {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            padding: 2.5rem 1.5rem 2rem;
            color: white;
            text-align: center;
            border-radius: 24px 24px 0 0;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(99, 102, 241, 0.3);
        }

        .user-menu-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 50% 50%, rgba(255, 255, 255, 0.15) 0%, transparent 60%);
            animation: slowRotate 20s linear infinite;
            pointer-events: none;
        }

        @keyframes slowRotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .user-menu-icon {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            font-size: 2rem;
            border: 2px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .user-menu-card:hover .user-menu-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .user-menu-title {
            font-weight: 700;
            font-size: 1.15rem;
            margin: 0;
            letter-spacing: 0.5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* List Styling */
        .user-menu-list {
            list-style: none;
            padding: 1.25rem;
            margin: 0;
        }

        .user-menu-item {
            margin-bottom: 0.75rem;
        }

        .user-menu-item:last-child {
            margin-bottom: 0;
        }

        /* Links as floating pills */
        .user-menu-link {
            display: flex;
            align-items: center;
            padding: 1rem 1.25rem;
            color: #64748b;
            text-decoration: none;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 12px;
            font-weight: 500;
            font-size: 0.95rem;
            background: transparent;
            border: 1px solid transparent;
        }

        .user-menu-link i {
            font-size: 1.25rem;
            margin-right: 1rem;
            color: #94a3b8;
            transition: all 0.25s ease;
            width: 24px;
            text-align: center;
        }

        /* Hover State */
        .user-menu-link:hover {
            background: #f8fafc;
            color: #6366f1;
            transform: translateX(4px);
            border-color: rgba(99, 102, 241, 0.1);
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        }

        .user-menu-link:hover i {
            color: #6366f1;
            transform: scale(1.1);
        }

        /* Active State */
        .user-menu-link.active {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
            border: 1px solid transparent;
        }

        .user-menu-link.active i {
            color: white;
            opacity: 1;
        }

        /* Dropdown Styling */
        .user-menu-dropdown .dropdown-menu {
            border: none;
            border-radius: 16px;
            box-shadow: 
                0 10px 15px -3px rgba(0, 0, 0, 0.1), 
                0 4px 6px -2px rgba(0, 0, 0, 0.05);
            margin-top: 0.5rem;
            padding: 0.75rem;
            background: white;
            min-width: 100%;
        }

        .user-menu-dropdown .dropdown-item {
            padding: 0.75rem 1rem;
            border-radius: 10px;
            color: #64748b;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
        }

        .user-menu-dropdown .dropdown-item:hover {
            background: #f1f5f9;
            color: #6366f1;
            padding-left: 1.25rem;
        }
        
        .user-menu-dropdown .dropdown-item.active {
            background: #eff6ff;
            color: #6366f1;
            font-weight: 600;
        }

        .user-menu-item.user-menu-dropdown .user-menu-link .bi-chevron-down {
            font-size: 0.8rem;
            margin-right: 0;
            margin-left: auto;
            color: #cbd5e1;
            transition: transform 0.3s ease;
        }

        .user-menu-item.user-menu-dropdown .user-menu-link:hover .bi-chevron-down {
            color: #6366f1;
        }
        
        .user-menu-item.user-menu-dropdown .user-menu-link.active .bi-chevron-down {
            color: rgba(255, 255, 255, 0.8);
        }

        /* Logout specific */
        .user-menu-link.logout {
            color: #ef4444;
            margin-top: 1rem;
            border: 1px dashed rgba(239, 68, 68, 0.2);
        }

        .user-menu-link.logout i {
            color: #ef4444;
        }

        .user-menu-link.logout:hover {
            background: #fef2f2;
            border-color: #ef4444;
            color: #dc2626;
            transform: translateY(-2px);
        }
        
        .user-menu-link.logout:hover i {
            color: #dc2626;
        }

        @media (max-width: 991px) {
            .user-menu-wrapper {
                position: relative;
                top: 0;
                margin-bottom: 2rem;
                z-index: 1;
            }
        }
    </style>

    <div class="user-menu-wrapper">
        <div class="user-menu-card">
            <div class="user-menu-header">
                <div class="user-menu-icon">
                    <i class="bi bi-person-circle"></i>
                </div>
                <h5 class="user-menu-title">Menu do Usuário</h5>
            </div>
            
            <ul class="user-menu-list">
                <li class="user-menu-item">
                    <a href="{{ route('user.dados') }}"
                       class="user-menu-link @if(request()->routeIs('user.dados*')) active @endif">
                        <i class="bi bi-person-badge"></i>
                        <span>Dados Pessoais</span>
                    </a>
                </li>
                
                <li class="user-menu-item user-menu-dropdown">
                    <a href="#"
                       class="user-menu-link dropdown-toggle @if(request()->routeIs('user.dashboard*') || request()->routeIs('orders.done*')) active @endif"
                       role="button"
                       data-bs-toggle="dropdown"
                       aria-expanded="false">
                        <i class="bi bi-box-seam"></i>
                        <span>Meus Pedidos</span>
                        <i class="bi bi-chevron-down"></i>
                    </a>
                    <ul class="dropdown-menu animate__animated animate__fadeIn animate__faster">
                        <li>
                            <a class="dropdown-item @if(request()->routeIs('user.dashboard*')) active @endif"
                               href="{{ route('user.dashboard') }}">
                                <i class="bi bi-hourglass-split me-2"></i> Em Andamento
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item @if(request()->routeIs('orders.done*')) active @endif"
                               href="{{ route('orders.done') }}">
                                <i class="bi bi-check-circle me-2"></i> Concluídos
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="bi bi-x-circle me-2"></i> Cancelados
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li class="user-menu-item">
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       class="user-menu-link logout">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Sair da Conta</span>
                    </a>
                </li>
            </ul>
        </div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Smooth entrance
            $('.user-menu-card').css({
                'opacity': 0,
                'transform': 'translateY(20px)'
            }).animate({
                'opacity': 1,
                'transform': 'translateY(0)'
            }, {
                duration: 600,
                step: function(now, fx) {
                    if (fx.prop === 'transform') {
                        $(this).css('transform', 'translateY(' + (20 * (1 - fx.pos)) + 'px)');
                    }
                }
            });

            // Dropdown Chevron Rotation
            $('.user-menu-dropdown').on('show.bs.dropdown', function () {
                $(this).find('.bi-chevron-down').css('transform', 'rotate(180deg)');
            });

            $('.user-menu-dropdown').on('hide.bs.dropdown', function () {
                $(this).find('.bi-chevron-down').css('transform', 'rotate(0deg)');
            });
        });
    </script>
</div>
