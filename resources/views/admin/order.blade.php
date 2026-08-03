@extends('layouts.admin')
@section('content')
    <style>
        .orders-page {
            max-width: 100%;
            overflow-x: hidden;
        }

        .orders-page .orders-card {
            max-width: 100%;
        }

        .orders-page .filter-panel {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 16px;
        }

        .orders-page .filter-panel .form-label {
            font-size: 0.8rem;
            margin-bottom: 4px;
        }

        .orders-page .filter-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            gap: 8px;
        }

        .orders-page .payment-section {
            border-top: 1px solid #e2e8f0;
            margin-top: 12px;
            padding-top: 12px;
        }

        .orders-page .payment-section .section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: #64748b;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .orders-page .order-item {
            background: var(--bs-gray-300);
            margin-top: 12px;
            margin-bottom: 12px;
            width: 100%;
        }

        .orders-page .order-item p {
            margin-bottom: 0.25rem;
        }

        .orders-page .suggest-wrap {
            position: relative;
        }

        .orders-page .suggest-list {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 30;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.12);
            max-height: 240px;
            overflow-y: auto;
            display: none;
            margin-top: 4px;
        }

        .orders-page .suggest-list.show {
            display: block;
        }

        .orders-page .suggest-item {
            display: block;
            width: 100%;
            text-align: left;
            border: 0;
            background: #fff;
            padding: 0.55rem 0.75rem;
            font-size: 0.88rem;
            color: #334155;
            cursor: pointer;
        }

        .orders-page .suggest-item:hover,
        .orders-page .suggest-item.active {
            background: #f4f0f7;
            color: #6A4486;
        }

        .orders-page .suggest-empty {
            padding: 0.65rem 0.75rem;
            font-size: 0.85rem;
            color: #94a3b8;
        }
    </style>

    <div class="container-fluid orders-page px-3">
        <div class="card orders-card">
            <div class="card-body">
                <div class="row align-items-center mb-3">
                    <div class="col-md-6">
                        <h4 class="mb-0">Pedidos</h4>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <form action="{{ route('export.pay') }}" method="post" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-alt-loci text-white export-pay">
                                Exportar Relatório de Pedidos Pagos
                            </button>
                        </form>
                    </div>
                </div>

                <div class="filter-panel">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label class="form-label" for="search-owner">Proprietário</label>
                            <div class="suggest-wrap">
                                <input class="form-control search" id="search-owner" type="search"
                                    placeholder="Nome do proprietário" autocomplete="off">
                                <div class="suggest-list" id="suggest-owner"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label" for="search-number">Nº do pedido</label>
                            <input class="form-control number-search" id="search-number" type="search"
                                placeholder="Ex: 13702">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="search-animal">Animal</label>
                            <div class="suggest-wrap">
                                <input class="form-control animal-search" id="search-animal" type="search"
                                    placeholder="Nome do animal" autocomplete="off">
                                <div class="suggest-list" id="suggest-animal"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="status-filter">Status da amostra</label>
                            <select class="form-select status-filter" id="status-filter">
                                <option value="0">Todos</option>
                                <option value="1">Aguardando amostra</option>
                                <option value="2">Amostra recebida</option>
                                <option value="7">Amostra aprovada</option>
                                <option value="6">Amostra reprovada</option>
                                <option value="10">Pedido concluído</option>
                                <option value="11">Aguardando pagamento</option>
                                <option value="9">Pagamento confirmado</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-2 mt-1 align-items-end">
                        <div class="col-md-2">
                            <label class="form-label" for="inicio-status">Data início</label>
                            <input type="date" class="form-control" id="inicio-status">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label" for="fim-status">Data fim</label>
                            <input type="date" class="form-control" id="fim-status">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="search-codlab">Codlab</label>
                            <input class="form-control codlab-search" id="search-codlab" type="search"
                                placeholder="Ex: EQU19853">
                        </div>
                        <div class="col-md-5">
                            <div class="filter-actions">
                                <button type="button" class="btn btn-primary" id="btn-buscar">Buscar</button>
                            </div>
                        </div>
                    </div>

                    <div class="payment-section">
                        <div class="section-title">Filtro por data de pagamento</div>
                        <div class="row g-2 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label" for="inicio">Início</label>
                                <input type="date" class="form-control" id="inicio">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="fim">Fim</label>
                                <input type="date" class="form-control" id="fim">
                            </div>
                            <div class="col-md-6">
                                <div class="filter-actions">
                                    <button type="button" class="btn btn-primary buscar" id="btn-pagamento">Buscar
                                        pagamento</button>
                                    <form action="{{ route('export.filter') }}" method="post"
                                        class="d-none export" id="export-pagamento">
                                        @csrf
                                        <input type="hidden" name="from" id="from">
                                        <input type="hidden" name="to" id="to">
                                        <button type="submit" class="btn btn-success export-filter">
                                            <i class="fa-solid fa-file-excel"></i> Exportar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="filter">
                    @foreach ($orders as $order)
                        <div class="text-secondary border rounded shadow orders order-item"
                            data-id="{{ $order->id }}">
                            <div class="row align-items-center p-2 m-0">
                                <div class="col-lg-10">
                                    <div class="row">
                                        <div class="col">
                                            <p>Numero do pedido:</p>
                                        </div>
                                        <div class="col">
                                            <p>Cliente:</p>
                                        </div>
                                        <div class="col">
                                            <p>Origem:</p>
                                        </div>
                                        <div class="col">
                                            <p>Data:</p>
                                        </div>
                                        <div class="col">
                                            <p>Status</p>
                                        </div>
                                    </div>
                                    <div class="row fw-bold text-dark">
                                        <div class="col">
                                            <p>{{ $order->id }}</p>
                                        </div>
                                        <div class="col">
                                            <p>{{ $order->creator }}</p>
                                        </div>
                                        <div class="col">
                                            <p>{{ $order->origin }}</p>
                                        </div>
                                        <div class="col">
                                            <p>{{ date('d/m/Y', strtotime($order->created_at)) }}</p>
                                        </div>
                                        <div class="col">
                                            <p>
                                                <span
                                                    @if ($order->id_tecnico) class="text-success" @else class="text-danger" @endif>T</span>
                                                /
                                                <span
                                                    @if ($order->user_id) class="text-success" @else class="text-danger" @endif>C</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 text-lg-end">
                                    <div class="dropdown">
                                        <a class="btn btn-alt-loci text-white dropdown-toggle" href="#"
                                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Ações
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item"
                                                href="@if ($order->origin == 'email') {{ route('order.detail', $order->id) }} @else {{ route('order.sistema.detail', $order->id) }} @endif">Ver</a>
                                            <a class="dropdown-item"
                                                href="{{ route('orders.owner', $order->id) }}">Proprietario</a>
                                            <a class="dropdown-item"
                                                href="{{ route('technical', $order->id) }}">Técnico responsável</a>
                                            <a class="dropdown-item"
                                                href="{{ route('order.edit', $order->id) }}">Editar pedido</a>
                                            <a class="dropdown-item"
                                                href="{{ route('orders.delete', $order->id) }}">Excluir</a>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            function setLoading($btn, loading, label) {
                if (loading) {
                    $btn.data('label', $btn.html());
                    $btn.prop('disabled', true).html(label || 'Carregando...');
                } else {
                    $btn.prop('disabled', false).html($btn.data('label') || label || 'Buscar');
                }
            }

            function renderFilter(data) {
                if (data && data[0] && data[0].viewRender) {
                    $('.filter').html(data[0].viewRender);
                }
            }

            function buscarPedidos() {
                var number = $.trim($('.number-search').val());
                var codlab = $.trim($('.codlab-search').val());
                var animal = $.trim($('.animal-search').val());
                var owner = $.trim($('.search').val());
                var status = $('.status-filter').val();
                var start = $('#inicio-status').val();
                var end = $('#fim-status').val();
                var $btn = $('#btn-buscar');

                if (number) {
                    setLoading($btn, true);
                    $.ajax({
                        url: "{{ route('filter.search.number') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            search: number
                        },
                        success: function(data) {
                            renderFilter(data);
                        },
                        complete: function() {
                            setLoading($btn, false, 'Buscar');
                        }
                    });
                    return;
                }

                if (codlab) {
                    setLoading($btn, true);
                    $.ajax({
                        url: "{{ route('filter.search.codlab') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            codlab: codlab
                        },
                        success: function(data) {
                            renderFilter(data);
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Animal não encontrado!',
                            });
                        },
                        complete: function() {
                            setLoading($btn, false, 'Buscar');
                        }
                    });
                    return;
                }

                if (animal) {
                    setLoading($btn, true);
                    $.ajax({
                        url: "{{ route('filter.search.animal') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            animal: animal
                        },
                        success: function(data) {
                            renderFilter(data);
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Animal não encontrado!',
                            });
                        },
                        complete: function() {
                            setLoading($btn, false, 'Buscar');
                        }
                    });
                    return;
                }

                if (owner) {
                    setLoading($btn, true);
                    $.ajax({
                        url: "{{ route('filter.search') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            search: owner
                        },
                        success: function(data) {
                            renderFilter(data);
                        },
                        complete: function() {
                            setLoading($btn, false, 'Buscar');
                        }
                    });
                    return;
                }

                setLoading($btn, true);
                $('.filter').empty();
                $.ajax({
                    url: "{{ route('filter.status') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        status: status,
                        start: start,
                        end: end
                    },
                    success: function(data) {
                        renderFilter(data);
                    },
                    complete: function() {
                        setLoading($btn, false, 'Buscar');
                    }
                });
            }

            $('#btn-buscar').on('click', buscarPedidos);

            function hideSuggest($list) {
                $list.removeClass('show').empty();
            }

            function renderSuggest($list, items) {
                if (!items.length) {
                    $list.html('<div class="suggest-empty">Nenhuma sugestão</div>').addClass('show');
                    return;
                }

                var html = items.map(function(item) {
                    return '<button type="button" class="suggest-item" data-value="' +
                        $('<div>').text(item.value).html() + '">' +
                        $('<div>').text(item.label).html() +
                        '</button>';
                }).join('');

                $list.html(html).addClass('show');
            }

            function setupSuggest(inputSelector, listSelector, url, onSelect) {
                var $input = $(inputSelector);
                var $list = $(listSelector);
                var timer = null;
                var lastQ = '';

                $input.on('input', function() {
                    var q = $.trim($input.val());
                    clearTimeout(timer);

                    if (q.length < 2) {
                        hideSuggest($list);
                        return;
                    }

                    timer = setTimeout(function() {
                        if (q === lastQ) {
                            return;
                        }
                        lastQ = q;

                        $.ajax({
                            url: url,
                            type: 'GET',
                            data: {
                                q: q
                            },
                            success: function(data) {
                                if ($.trim($input.val()) !== q) {
                                    return;
                                }
                                renderSuggest($list, data.suggestions || []);
                            }
                        });
                    }, 250);
                });

                $list.on('mousedown', '.suggest-item', function(e) {
                    e.preventDefault();
                    var value = $(this).data('value');
                    $input.val(value);
                    hideSuggest($list);
                    lastQ = value;
                    if (typeof onSelect === 'function') {
                        onSelect(value);
                    }
                });

                $input.on('keydown', function(e) {
                    if (e.which === 27) {
                        hideSuggest($list);
                    }
                });

                $input.on('blur', function() {
                    setTimeout(function() {
                        hideSuggest($list);
                    }, 150);
                });
            }

            setupSuggest('#search-owner', '#suggest-owner', "{{ route('filter.suggest.owner') }}", function() {
                clearTimeout(liveOwnerTimer);
                clearTimeout(liveAnimalTimer);
                $('.number-search, .codlab-search, .animal-search').val('');
                buscarPedidos();
            });

            setupSuggest('#search-animal', '#suggest-animal', "{{ route('filter.suggest.animal') }}", function() {
                clearTimeout(liveOwnerTimer);
                clearTimeout(liveAnimalTimer);
                $('.number-search, .codlab-search, .search').val('');
                buscarPedidos();
            });

            // Digitar e parar já busca, sem clicar
            var liveOwnerTimer = null;
            var liveAnimalTimer = null;

            $('#search-owner').on('input', function() {
                clearTimeout(liveOwnerTimer);
                var q = $.trim($(this).val());
                if (q.length < 3) {
                    return;
                }
                liveOwnerTimer = setTimeout(function() {
                    $('.number-search, .codlab-search, .animal-search').val('');
                    buscarPedidos();
                }, 600);
            });

            $('#search-animal').on('input', function() {
                clearTimeout(liveAnimalTimer);
                var q = $.trim($(this).val());
                if (q.length < 3) {
                    return;
                }
                liveAnimalTimer = setTimeout(function() {
                    $('.number-search, .codlab-search, .search').val('');
                    buscarPedidos();
                }, 600);
            });

            $('#search-owner, #search-animal').on('keyup', function(e) {
                if (e.which !== 32) {
                    return;
                }
                var q = $.trim($(this).val());
                if (q.length < 2) {
                    return;
                }
                clearTimeout(liveOwnerTimer);
                clearTimeout(liveAnimalTimer);
                hideSuggest($('#suggest-owner'));
                hideSuggest($('#suggest-animal'));
                if ($(this).is('#search-owner')) {
                    $('.number-search, .codlab-search, .animal-search').val('');
                } else {
                    $('.number-search, .codlab-search, .search').val('');
                }
                buscarPedidos();
            });

            $('.filter-panel').on('keypress', 'input', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    hideSuggest($('#suggest-owner'));
                    hideSuggest($('#suggest-animal'));
                    if ($(this).is('#inicio, #fim')) {
                        $('#btn-pagamento').click();
                    } else {
                        buscarPedidos();
                    }
                }
            });

            $('#btn-pagamento').on('click', function() {
                var inicio = $('#inicio').val();
                var fim = $('#fim').val();
                var $btn = $(this);
                setLoading($btn, true,
                    '<div class="spinner-border spinner-border-sm text-light" role="status"></div>');
                $.ajax({
                    url: "{{ route('filter.date') }}",
                    type: "GET",
                    data: {
                        _token: "{{ csrf_token() }}",
                        from: inicio,
                        to: fim
                    },
                    success: function(data) {
                        renderFilter(data);
                        $('#export-pagamento').removeClass('d-none');
                        $('#from').val(inicio);
                        $('#to').val(fim);
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Encontramos um erro!',
                        });
                    },
                    complete: function() {
                        setLoading($btn, false, 'Buscar pagamento');
                    }
                });
            });
        });
    </script>
@endsection
