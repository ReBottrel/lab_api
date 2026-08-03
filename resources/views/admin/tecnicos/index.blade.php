@extends('layouts.admin')

@section('content')
    <style>
        .tecnicos-page {
            max-width: 100%;
        }

        .tecnicos-page .tecnicos-card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(106, 68, 134, 0.08);
            overflow: hidden;
            max-width: 100%;
        }

        .tecnicos-page .tecnicos-header {
            background: linear-gradient(135deg, #6A4486 0%, #8256a3 100%);
            color: #fff;
            padding: 1.25rem 1.5rem;
            border-bottom: none;
        }

        .tecnicos-page .tecnicos-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: 0.01em;
        }

        .tecnicos-page .tecnicos-header .subtitle {
            margin: 4px 0 0;
            font-size: 0.85rem;
            opacity: 0.85;
        }

        .tecnicos-page .btn-criar {
            background: #8CC540;
            border-color: #8CC540;
            color: #fff;
            font-weight: 600;
            border-radius: 8px;
            padding: 0.55rem 1rem;
            white-space: nowrap;
        }

        .tecnicos-page .btn-criar:hover {
            background: #7ab336;
            border-color: #7ab336;
            color: #fff;
        }

        .tecnicos-page .toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
            justify-content: space-between;
            margin-top: 1rem;
        }

        .tecnicos-page .search-wrap {
            position: relative;
            flex: 1;
            min-width: 240px;
            max-width: 520px;
        }

        .tecnicos-page .search-wrap .form-control {
            border: none;
            border-radius: 10px;
            padding: 0.7rem 2.75rem 0.7rem 1rem;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .tecnicos-page .search-wrap .form-control:focus {
            box-shadow: 0 0 0 3px rgba(140, 197, 64, 0.35);
        }

        .tecnicos-page .search-wrap .search-icon {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #6A4486;
            opacity: 0.55;
            pointer-events: none;
        }

        .tecnicos-page .card-body {
            padding: 0;
        }

        .tecnicos-page .table {
            margin-bottom: 0;
        }

        .tecnicos-page .table thead th {
            background: #f4f0f7;
            color: #4a3560;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            font-weight: 700;
            border-top: none;
            border-bottom: 1px solid #e8dff0;
            padding: 0.9rem 1.25rem;
            white-space: nowrap;
        }

        .tecnicos-page .table tbody td {
            padding: 0.95rem 1.25rem;
            vertical-align: middle;
            border-color: #f0ebf5;
            color: #334155;
        }

        .tecnicos-page .table tbody tr {
            transition: background-color 0.15s ease;
        }

        .tecnicos-page .table tbody tr:hover {
            background: #faf7fc;
        }

        .tecnicos-page .tec-name {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .tecnicos-page .tec-avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #6A4486, #8CC540);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .tecnicos-page .tec-name-text {
            font-weight: 600;
            color: #2d1f3d;
            line-height: 1.3;
            word-break: break-word;
        }

        .tecnicos-page .tec-meta {
            color: #64748b;
            font-size: 0.9rem;
        }

        .tecnicos-page .btn-acoes {
            background: #6A4486;
            border-color: #6A4486;
            color: #fff;
            border-radius: 8px;
            font-size: 0.85rem;
            padding: 0.4rem 0.85rem;
        }

        .tecnicos-page .btn-acoes:hover,
        .tecnicos-page .btn-acoes:focus {
            background: #5a3872;
            border-color: #5a3872;
            color: #fff;
        }

        .tecnicos-page .dropdown-menu {
            border: none;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(45, 31, 61, 0.12);
            padding: 0.4rem;
        }

        .tecnicos-page .dropdown-item {
            border-radius: 6px;
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
        }

        .tecnicos-page .dropdown-item:hover {
            background: #f4f0f7;
            color: #6A4486;
        }

        .tecnicos-page .dropdown-item.delete:hover {
            background: #fef2f2;
            color: #dc2626;
        }

        .tecnicos-page .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #64748b;
        }

        .tecnicos-page .empty-state i {
            font-size: 2rem;
            color: #c4b5d4;
            margin-bottom: 0.75rem;
        }

        .tecnicos-page .pagination-wrap {
            padding: 1rem 1.25rem;
            border-top: 1px solid #f0ebf5;
            background: #fcfbfd;
        }
    </style>

    <div class="container-fluid tecnicos-page px-3">
        <div class="card tecnicos-card">
            <div class="tecnicos-header">
                <div class="d-flex flex-wrap justify-content-between align-items-start">
                    <div>
                        <h1>Técnicos</h1>
                        <p class="subtitle">Gerencie os responsáveis técnicos cadastrados</p>
                    </div>
                </div>
                <div class="toolbar">
                    <a href="{{ route('techinical.create') }}" class="btn btn-criar">
                        <i class="fas fa-plus mr-1"></i> Criar Técnico
                    </a>
                    <div class="search-wrap">
                        <input type="search" name="filter" id="buscar-tecnico"
                            placeholder="Buscar por nome, e-mail, telefone ou documento..."
                            class="form-control buscar-tecnico" autocomplete="off">
                        <i class="fas fa-search search-icon"></i>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Telefone</th>
                                <th style="width: 120px;">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="filter">
                            @forelse ($tecnicos as $tecnico)
                                @php
                                    $nome = trim((string) $tecnico->professional_name);
                                    $partes = preg_split('/\s+/', $nome) ?: [];
                                    $iniciais = '';
                                    if (!empty($partes[0])) {
                                        $iniciais .= mb_substr($partes[0], 0, 1);
                                    }
                                    if (count($partes) > 1) {
                                        $iniciais .= mb_substr($partes[count($partes) - 1], 0, 1);
                                    }
                                    $iniciais = mb_strtoupper($iniciais ?: '?');
                                @endphp
                                <tr>
                                    <td>
                                        <div class="tec-name">
                                            <span class="tec-avatar">{{ $iniciais }}</span>
                                            <span class="tec-name-text">{{ $tecnico->professional_name }}</span>
                                        </div>
                                    </td>
                                    <td class="tec-meta">{{ $tecnico->email ?: '—' }}</td>
                                    <td class="tec-meta">{{ $tecnico->cell ?: '—' }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-acoes dropdown-toggle" href="#" role="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                Ações
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <a href="{{ route('techinical.edit', $tecnico->id) }}"
                                                    class="dropdown-item">
                                                    <i class="fas fa-pen fa-fw mr-1"></i> Editar
                                                </a>
                                                <a data-id="{{ $tecnico->id }}" class="dropdown-item delete">
                                                    <i class="fas fa-trash fa-fw mr-1"></i> Excluir
                                                </a>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-state">
                                            <div><i class="fas fa-user-md"></i></div>
                                            <div>Nenhum técnico encontrado.</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="pagination-wrap">
                    {{ $tecnicos->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            var searchTimer = null;
            var lastSearch = '';

            $(document).on('click', '.delete', function() {
                var id = $(this).data('id');
                var url = "{{ route('techinical.delete', ':id') }}";
                url = url.replace(':id', id);
                Swal.fire({
                    title: 'Você tem certeza?',
                    text: "A remoção do técnico pode ser irreversivel!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#6A4486',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sim, delete isso!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function() {
                                Swal.fire(
                                    'Deletado!',
                                    'Técnico deletado com sucesso!.',
                                    'success'
                                ).then(() => location.reload());
                            }
                        });
                    }
                });
            });

            function buscarTecnicos(search) {
                if (search === lastSearch) {
                    return;
                }
                lastSearch = search;

                if (search === '') {
                    window.location.reload();
                    return;
                }

                $.ajax({
                    url: "{{ route('techinical.search') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        search: search
                    },
                    success: function(data) {
                        if (data && data[0] && data[0].viewRender !== undefined) {
                            $('.filter').html(data[0].viewRender ||
                                '<tr><td colspan="4"><div class="empty-state"><div><i class="fas fa-user-md"></i></div><div>Nenhum técnico encontrado.</div></div></td></tr>'
                            );
                        }
                    }
                });
            }

            $('#buscar-tecnico').on('input', function() {
                var search = $.trim($(this).val());
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function() {
                    buscarTecnicos(search);
                }, 300);
            });
        });
    </script>
@endsection
