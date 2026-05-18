@extends('layouts.admin')

@section('content')
    @php
        $filters = $filters ?? [];
        $hasActiveFilters = collect($filters)->filter(function ($v, $key) {
            if ($key === 'sem_codlab') {
                return ! empty($v);
            }
            return $v !== null && $v !== '';
        })->isNotEmpty();
    @endphp

    <div class="ordem-servico-container animais-list-container">
        <div class="page-header">
            <div>
                <h3>Animais</h3>
                <p class="text-muted mb-0 animais-subtitle">
                    {{ $animais->total() }} {{ $animais->total() === 1 ? 'registro encontrado' : 'registros encontrados' }}
                </p>
            </div>
            <a href="{{ route('animais.create') }}" class="btn btn-alt-loci text-white">
                <i class="fa-solid fa-plus me-1"></i> Novo animal
            </a>
        </div>

        <form method="GET" action="{{ route('animais') }}" id="filtros-animais" class="search-filters">
            <div class="search-row">
                <div class="search-group">
                    <label for="nome">Nome do animal</label>
                    <div class="input-group">
                        <input type="search" name="nome" id="nome" class="form-control"
                            placeholder="Buscar por nome..." value="{{ $filters['nome'] ?? '' }}">
                        <span class="input-icon"><i class="fa-solid fa-horse"></i></span>
                    </div>
                </div>
                <div class="search-group">
                    <label for="codlab">Codlab</label>
                    <div class="input-group">
                        <input type="text" name="codlab" id="codlab" class="form-control"
                            placeholder="Ex.: EQU300001" value="{{ $filters['codlab'] ?? '' }}">
                        <span class="input-icon"><i class="fa-solid fa-barcode"></i></span>
                    </div>
                </div>
                <div class="search-group">
                    <label for="registro">Registro / marca</label>
                    <div class="input-group">
                        <input type="text" name="registro" id="registro" class="form-control"
                            placeholder="Número de registro" value="{{ $filters['registro'] ?? '' }}">
                        <span class="input-icon"><i class="fa-solid fa-hashtag"></i></span>
                    </div>
                </div>
            </div>

            <div class="search-row">
                <div class="search-group">
                    <label for="especies">Espécie</label>
                    <select name="especies" id="especies" class="form-select filter-select">
                        <option value="">Todas</option>
                        @foreach ($especiesList as $especie)
                            <option value="{{ $especie }}" @selected(($filters['especies'] ?? '') === $especie)>{{ $especie }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="search-group">
                    <label for="breed">Raça</label>
                    <select name="breed" id="breed" class="form-select filter-select">
                        <option value="">Todas</option>
                        @foreach ($breedsList as $raca)
                            <option value="{{ $raca }}" @selected(($filters['breed'] ?? '') === $raca)>{{ $raca }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="search-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-select filter-select">
                        <option value="">Todos</option>
                        @foreach ($statusOptions as $value => $meta)
                            <option value="{{ $value }}" @selected((string) ($filters['status'] ?? '') === (string) $value)>
                                {{ $meta['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="search-group">
                    <label for="sex">Sexo</label>
                    <select name="sex" id="sex" class="form-select filter-select">
                        <option value="">Todos</option>
                        <option value="M" @selected(($filters['sex'] ?? '') === 'M')>Macho</option>
                        <option value="F" @selected(($filters['sex'] ?? '') === 'F')>Fêmea</option>
                    </select>
                </div>
            </div>

            <div class="search-row align-items-end">
                <div class="search-group">
                    <label for="identificador">Identificador</label>
                    <div class="input-group">
                        <input type="text" name="identificador" id="identificador" class="form-control"
                            placeholder="Código do exame / identificador" value="{{ $filters['identificador'] ?? '' }}">
                        <span class="input-icon"><i class="fa-solid fa-tag"></i></span>
                    </div>
                </div>
                <div class="search-group">
                    <label class="d-block">&nbsp;</label>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="sem_codlab" value="1" id="sem_codlab"
                            @checked(!empty($filters['sem_codlab']))>
                        <label class="form-check-label" for="sem_codlab">Somente sem codlab</label>
                    </div>
                </div>
                <div class="search-group filter-actions">
                    <label class="d-block">&nbsp;</label>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-search">
                            <i class="fa-solid fa-filter"></i> Filtrar
                        </button>
                        @if ($hasActiveFilters)
                            <a href="{{ route('animais') }}" class="btn btn-outline-secondary btn-clear-filters">
                                <i class="fa-solid fa-rotate-left"></i> Limpar
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            @if ($hasActiveFilters)
                <div class="active-filters">
                    <span class="active-filters-label">Filtros ativos:</span>
                    @foreach ($filters as $key => $value)
                        @if ($key === 'sem_codlab' && $value)
                            <span class="filter-chip">Sem codlab</span>
                        @elseif ($value !== null && $value !== '')
                            <span class="filter-chip">{{ ucfirst(str_replace('_', ' ', $key)) }}: {{ $value }}</span>
                        @endif
                    @endforeach
                </div>
            @endif
        </form>

        <div class="table-container">
            <div class="table-responsive">
                <table class="table animais-table">
                    <thead>
                        <tr>
                            <th>Animal</th>
                            <th>Raça</th>
                            <th>Espécie</th>
                            <th>Codlab</th>
                            <th>Registro</th>
                            <th>Status</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="animais-tbody">
                        @include('admin.animais.includes.table-rows', ['animais' => $animais, 'statusOptions' => $statusOptions])
                    </tbody>
                </table>
            </div>
            <div class="pagin mt-3" id="animais-pagination">
                {{ $animais->links() }}
            </div>
        </div>
    </div>

    <style>
        .animais-list-container .animais-subtitle { font-size: 0.9rem; }
        .animais-list-container .filter-select {
            border-radius: 8px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            height: 42px;
            font-size: 0.95rem;
        }
        .animais-list-container .filter-actions { min-width: 200px; }
        .animais-list-container .btn-clear-filters {
            height: 42px;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            border-radius: 8px;
        }
        .animais-list-container .active-filters {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(0, 0, 0, 0.06);
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            align-items: center;
        }
        .animais-list-container .active-filters-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #6c757d;
        }
        .animais-list-container .filter-chip {
            background: rgba(78, 115, 223, 0.1);
            color: #4e73df;
            padding: 0.25rem 0.65rem;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .animais-list-container .codlab-badge {
            font-family: ui-monospace, monospace;
            font-weight: 600;
            font-size: 0.85rem;
            background: #f8f9fc;
            border: 1px solid #e3e6f0;
            padding: 0.2rem 0.5rem;
            border-radius: 6px;
            color: #2e384d;
        }
        .animais-list-container .status-badge { font-weight: 500; font-size: 0.75rem; }
        .animais-list-container .animal-name-cell strong { color: #2e384d; }
    </style>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            var searchTimer;

            function applyAjaxFilters() {
                $.ajax({
                    url: "{{ route('animais') }}",
                    method: "GET",
                    data: $('#filtros-animais').serialize(),
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    success: function(response) {
                        if (response.viewRender) {
                            $('#animais-tbody').html(response.viewRender);
                            $('#animais-pagination').html(response.pagination || '');
                            $('.animais-subtitle').text(
                                (response.total || 0) + ' ' + ((response.total || 0) === 1 ? 'registro encontrado' : 'registros encontrados')
                            );
                        }
                    }
                });
            }

            $('#nome, #codlab, #registro, #identificador').on('keyup', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(applyAjaxFilters, 450);
            });

            $('#especies, #breed, #status, #sex, #sem_codlab').on('change', applyAjaxFilters);

            $(document).on('click', '.excluir-animal', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Você tem certeza?',
                    text: 'Esse processo pode ser irreversível!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sim, deletar!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/animal-delete/',
                            type: 'POST',
                            data: { id: id },
                            success: function() {
                                Swal.fire('Deletado!', 'Animal deletado com sucesso.', 'success');
                                applyAjaxFilters();
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.transferir-animal', function() {
                window.location.href = '/animal-transfer/' + $(this).data('id');
            });
        });
    </script>
@endsection
