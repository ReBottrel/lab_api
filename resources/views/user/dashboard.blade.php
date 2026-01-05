@extends('layouts.loja')

@section('content')
    <style>
        :root {
            --primary-color: #0d6efd;
            --primary-hover: #0b5ed7;
            --success-color: #198754;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 4px 20px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 40px rgba(0, 0, 0, 0.15);
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }

        .dashboard-wrapper {
            background: transparent;
            padding: 2rem 0;
        }

        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            background-size: 200% 200%;
            animation: gradientShift 8s ease infinite;
            border-radius: 20px;
            padding: 2.5rem;
            margin-bottom: 2rem;
            color: white;
            box-shadow: var(--shadow-lg);
            position: relative;
            overflow: hidden;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .dashboard-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .dashboard-header > * {
            position: relative;
            z-index: 1;
        }

        .dashboard-header h1 {
            color: white;
            font-weight: 700;
            margin: 0;
            font-size: 2.25rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .dashboard-header p {
            font-size: 1.05rem;
            opacity: 0.95;
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color), #764ba2);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .stat-card-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--primary-color), #764ba2);
            color: white;
            transition: var(--transition);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
        }

        .stat-card-value {
            font-size: 2rem;
            font-weight: 700;
            color: #212529;
            margin: 0.5rem 0;
        }

        .stat-card-label {
            color: #6c757d;
            font-size: 0.9rem;
            margin: 0;
        }

        .filters-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(0, 0, 0, 0.05);
            backdrop-filter: blur(10px);
        }

        .form-floating > .form-select {
            height: calc(3.5rem + 2px);
            border-radius: 12px;
            border: 2px solid #e9ecef;
            transition: var(--transition);
        }

        .form-floating > .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
        }

        .form-floating > label {
            color: #6c757d;
            font-weight: 500;
        }

        .order-card {
            background: white;
            border-radius: 20px;
            padding: 0;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: var(--transition);
            overflow: hidden;
            position: relative;
            animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            opacity: 0;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .order-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color), #764ba2);
            transform: scaleY(0);
            transform-origin: top;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .order-card::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.1) 0%, transparent 70%);
            transform: translate(50%, -50%);
            transition: transform 0.4s ease;
        }

        .order-card:hover {
            transform: translateY(-6px) scale(1.01);
            box-shadow: var(--shadow-lg);
        }

        .order-card:hover::before {
            transform: scaleY(1);
        }

        .order-card:hover::after {
            transform: translate(30%, -30%) scale(1.2);
        }

        .order-card a {
            text-decoration: none;
            color: inherit;
            display: block;
            padding: 2rem;
            position: relative;
            z-index: 1;
        }

        .order-flex {
            align-items: center;
        }

        .order-card .col-md-4 {
            padding: 0.5rem 1rem;
        }

        .order-card p {
            margin: 0;
            color: #495057;
            font-size: 0.95rem;
        }

        .order-card .order-number {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .order-card .order-number i {
            font-size: 1.5rem;
            background: linear-gradient(135deg, var(--primary-color), #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .order-card .order-date {
            color: #6c757d;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.95rem;
        }

        .order-card .order-date i {
            color: var(--primary-color);
            font-size: 1.1rem;
        }

        .order-card .order-action {
            color: var(--primary-color);
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            transition: var(--transition);
            padding: 0.5rem 1rem;
            border-radius: 10px;
            background: rgba(13, 110, 253, 0.05);
        }

        .order-card:hover .order-action {
            color: white;
            background: linear-gradient(135deg, var(--primary-color), #764ba2);
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
        }

        .order-card:hover .order-action i {
            transform: translateX(5px);
        }

        .order-card .order-action i {
            transition: var(--transition);
            font-size: 1.1rem;
        }

        .empty-state {
            text-align: center;
            padding: 5rem 2rem;
            background: white;
            border-radius: 20px;
            box-shadow: var(--shadow-md);
            position: relative;
            overflow: hidden;
        }

        .empty-state::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.05) 0%, transparent 70%);
            animation: rotate 15s linear infinite;
        }

        .empty-state > * {
            position: relative;
            z-index: 1;
        }

        .empty-state i {
            font-size: 5rem;
            background: linear-gradient(135deg, #dee2e6, #adb5bd);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1.5rem;
            display: inline-block;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .empty-state h3 {
            color: #495057;
            margin-bottom: 0.75rem;
            font-weight: 600;
        }

        .empty-state p {
            color: #6c757d;
            font-size: 1.05rem;
        }

        /* Menu lateral modernizado */
        .list-group {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .list-group-item {
            border: none;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1rem 1.25rem;
            transition: var(--transition);
            background: white;
        }

        .list-group-item:last-child {
            border-bottom: none;
        }

        .list-group-item:hover {
            background: linear-gradient(90deg, rgba(13, 110, 253, 0.05), transparent);
            transform: translateX(5px);
            padding-left: 1.5rem;
        }

        .list-group-item.active {
            background: linear-gradient(135deg, var(--primary-color), #764ba2);
            color: white;
            font-weight: 600;
        }

        .list-group-item.active i {
            color: white;
        }

        .list-group-item i {
            margin-right: 0.75rem;
            font-size: 1.1rem;
            color: var(--primary-color);
            transition: var(--transition);
        }

        .list-group-item:hover i {
            transform: scale(1.2);
        }

        .dropdown-menu {
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(0, 0, 0, 0.05);
            margin-top: 0.5rem;
        }

        .dropdown-item {
            padding: 0.75rem 1.25rem;
            transition: var(--transition);
        }

        .dropdown-item:hover {
            background: rgba(13, 110, 253, 0.1);
            transform: translateX(5px);
        }

        .orders-list {
            min-height: 200px;
        }

        .filters-card h5 {
            color: #212529;
            display: flex;
            align-items: center;
        }

        .filters-card h5 i {
            color: var(--primary-color);
        }

        .order-card .col-md-4:last-child {
            text-align: right;
        }

        @media (max-width: 991px) {
            .order-card .col-md-4:last-child {
                text-align: left;
                margin-top: 0.5rem;
            }

            .stats-cards {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 1rem;
            }
        }

        @media (max-width: 768px) {
            .dashboard-wrapper {
                padding: 1rem 0;
            }

            .dashboard-header {
                padding: 1.75rem;
                border-radius: 16px;
            }

            .dashboard-header h1 {
                font-size: 1.75rem;
            }

            .dashboard-header p {
                font-size: 0.95rem;
            }

            .filters-card {
                padding: 1.5rem;
                border-radius: 16px;
            }

            .filters-card h5 {
                font-size: 1.1rem;
            }

            .order-card {
                border-radius: 16px;
            }

            .order-card a {
                padding: 1.5rem;
            }

            .order-card .col-md-4 {
                padding: 0.5rem;
                margin-bottom: 0.75rem;
            }

            .order-card .order-number {
                font-size: 1.1rem;
            }

            .order-card .order-action {
                padding: 0.4rem 0.8rem;
                font-size: 0.9rem;
            }

            .stats-cards {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .stat-card {
                padding: 1.25rem;
            }

            .stat-card-value {
                font-size: 1.75rem;
            }

            .empty-state {
                padding: 3rem 1.5rem;
            }

            .empty-state i {
                font-size: 4rem;
            }
        }

        @media (max-width: 576px) {
            .dashboard-header {
                padding: 1.5rem;
            }

            .dashboard-header h1 {
                font-size: 1.5rem;
            }

            .order-card a {
                padding: 1.25rem;
            }

            .filters-card {
                padding: 1.25rem;
            }
        }
    </style>

    <div class="dashboard-wrapper">
        <div class="container">
            <div class="dashboard-header">
                <h1><i class="bi bi-person-circle me-2"></i>Minha Conta</h1>
                <p class="mb-0 mt-2">Gerencie seus pedidos e informações pessoais de forma simples e rápida</p>
            </div>

            <div class="row g-4">
                @component('layouts.partials.user-menu')
                @endcomponent

                <div class="col-md-8 col-12">
                    @if(count($orders) > 0)
                        <div class="stats-cards">
                            <div class="stat-card">
                                <div class="stat-card-icon">
                                    <i class="bi bi-receipt"></i>
                                </div>
                                <div class="stat-card-value">{{ count($orders) }}</div>
                                <p class="stat-card-label">Total de Pedidos</p>
                            </div>
                            <div class="stat-card">
                                <div class="stat-card-icon" style="background: linear-gradient(135deg, #198754, #20c997);">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div class="stat-card-value">{{ count($orders) }}</div>
                                <p class="stat-card-label">Pedidos Ativos</p>
                            </div>
                            <div class="stat-card">
                                <div class="stat-card-icon" style="background: linear-gradient(135deg, #ffc107, #fd7e14);">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                                <div class="stat-card-value">0</div>
                                <p class="stat-card-label">Em Processamento</p>
                            </div>
                        </div>
                    @endif

                    <div class="filters-card">
                        <h5 class="mb-4 fw-bold">
                            <i class="bi bi-funnel me-2"></i>Filtros e Ordenação
                        </h5>
                        <form class="row g-3">
                            <div class="col-12 col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="periodFilter">
                                        <option value="30">Últimos 30 dias</option>
                                        <option value="60">Últimos 60 dias</option>
                                        <option value="90">Últimos 90 dias</option>
                                        <option value="180">Últimos 180 dias</option>
                                        <option value="360" selected>Últimos 360 dias</option>
                                        <option value="9999">Todo o período</option>
                                    </select>
                                    <label><i class="bi bi-calendar3 me-2"></i>Período</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="sortFilter">
                                        <option value="1" selected>Mais novos primeiro</option>
                                        <option value="2">Mais antigos primeiro</option>
                                    </select>
                                    <label><i class="bi bi-sort-down me-2"></i>Ordenação</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    @if(count($orders) > 0)
                        <div class="orders-list">
                            <h5 class="mb-3 fw-bold text-muted">
                                <i class="bi bi-list-ul me-2"></i>Seus Pedidos
                            </h5>
                            @foreach ($orders as $index => $item)
                                <div class="order-card" style="animation-delay: {{ $index * 0.1 }}s">
                                    <a href="{{ route('user.orders', $item->id) }}">
                                        <div class="row order-flex">
                                            <div class="col-md-4">
                                                <p class="order-number">
                                                    <i class="bi bi-receipt"></i>
                                                    Pedido #{{ $item->id }}
                                                </p>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="order-date">
                                                    <i class="bi bi-calendar3"></i>
                                                    {{ date('d/m/Y', strtotime($item->created_at)) }}
                                                </p>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <span class="order-action">
                                                    Ver detalhes
                                                    <i class="bi bi-arrow-right"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <h3>Nenhum pedido encontrado</h3>
                            <p>Você ainda não possui pedidos no período selecionado.</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Animações de entrada para os cards
            $('.order-card').each(function(index) {
                $(this).css({
                    'animation-delay': (index * 0.1) + 's'
                });
            });

            // Animações de entrada para os stat cards
            $('.stat-card').each(function(index) {
                $(this).css({
                    'animation': 'fadeInUp 0.6s ease ' + (index * 0.1) + 's both',
                    'opacity': '0'
                });
            });

            // Efeito de hover suave nos cards
            $('.order-card').hover(
                function() {
                    $(this).find('.order-action i').css('transform', 'translateX(5px)');
                },
                function() {
                    $(this).find('.order-action i').css('transform', 'translateX(0)');
                }
            );

            // Efeito de hover nos stat cards
            $('.stat-card').hover(
                function() {
                    $(this).find('.stat-card-icon').css('transform', 'scale(1.1) rotate(5deg)');
                },
                function() {
                    $(this).find('.stat-card-icon').css('transform', 'scale(1) rotate(0deg)');
                }
            );

            // Funcionalidade de filtro por período (pode ser implementada com AJAX)
            $('#periodFilter, #sortFilter').on('change', function() {
                // Aqui você pode adicionar lógica para filtrar os pedidos
                // Por exemplo, fazer uma requisição AJAX ou recarregar a página com parâmetros
                console.log('Filtro alterado:', $(this).val());

                // Adiciona feedback visual
                const select = $(this);
                select.css('border-color', 'var(--primary-color)');
                setTimeout(() => {
                    select.css('border-color', '');
                }, 500);

                // Exemplo de implementação com recarregamento de página:
                // const period = $('#periodFilter').val();
                // const sort = $('#sortFilter').val();
                // window.location.href = `{{ route('user.dashboard') }}?period=${period}&sort=${sort}`;
            });

            // Adiciona efeito de loading ao clicar no card
            $('.order-card a').on('click', function(e) {
                const card = $(this).closest('.order-card');
                card.css({
                    'opacity': '0.8',
                    'transform': 'scale(0.98)'
                });
            });

            // Animação de entrada para o header
            $('.dashboard-header').css({
                'animation': 'fadeInUp 0.8s ease'
            });

            // Animação de entrada para o filters card
            $('.filters-card').css({
                'animation': 'fadeInUp 0.6s ease 0.2s both',
                'opacity': '0'
            });
        });

        // Código para funcionalidades antigas (mantido para compatibilidade)
        $(document).on('change', '.sel-price', function() {
            orderId = $(this).find(':selected').data('id');

            console.log(orderId);
            $(this).closest('.accordion').find('.prices').addClass('d-none');
            $(this).closest('.accordion').find(`.prices.prices-${$(this).val().split('-')[0]}`).removeClass(
                'd-none');
            var totalPrice = 0;
            $(this).closest(`#divPedidos`).find(`.preco`).each(function() {
                if ($(this).is('.d-none') == false && $(this).closest('.accordion').find('.paynow').is(
                        ':checked')) {
                    totalPrice += parseFloat($(this).data('price'));
                }
            });
            $(`.total-price-${orderId}`).text(totalPrice.toFixed(2).replace('.', ','));
            $(`.price-${orderId}`).val(totalPrice.toFixed(2).replace('.', ','));
        });

        $(document).on('click', '.paynow', function() {
            var totalPrice = 0;
            $(this).closest('#divPedidos').find(`.preco`).each(function() {
                if ($(this).is('.d-none') == false && $(this).closest('.accordion').find('.paynow').is(
                        ':checked')) {
                    totalPrice += parseFloat($(this).data('price'));
                }
            });
            $(`.total-price-${orderId}`).text(totalPrice.toFixed(2).replace('.', ','));
            $(`.price-${orderId}`).val(totalPrice.toFixed(2).replace('.', ','));
        });
    </script>
@endsection
