@extends('layouts.admin')

@section('content')
    <style>
        .owners-page {
            max-width: 100%;
        }

        .owners-page .owners-card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(106, 68, 134, 0.08);
            overflow: hidden;
            max-width: 100%;
        }

        .owners-page .owners-header {
            background: linear-gradient(135deg, #6A4486 0%, #8256a3 100%);
            color: #fff;
            padding: 1.25rem 1.5rem;
            border-bottom: none;
        }

        .owners-page .owners-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: 0.01em;
        }

        .owners-page .owners-header .subtitle {
            margin: 4px 0 0;
            font-size: 0.85rem;
            opacity: 0.85;
        }

        .owners-page .btn-criar {
            background: #8CC540;
            border-color: #8CC540;
            color: #fff;
            font-weight: 600;
            border-radius: 8px;
            padding: 0.55rem 1rem;
            white-space: nowrap;
        }

        .owners-page .btn-criar:hover {
            background: #7ab336;
            border-color: #7ab336;
            color: #fff;
        }

        .owners-page .toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
            justify-content: space-between;
            margin-top: 1rem;
        }

        .owners-page .search-wrap {
            position: relative;
            flex: 1;
            min-width: 240px;
            max-width: 520px;
        }

        .owners-page .search-wrap .form-control {
            border: none;
            border-radius: 10px;
            padding: 0.7rem 2.75rem 0.7rem 1rem;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .owners-page .search-wrap .form-control:focus {
            box-shadow: 0 0 0 3px rgba(140, 197, 64, 0.35);
        }

        .owners-page .search-wrap .search-icon {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #6A4486;
            opacity: 0.55;
            pointer-events: none;
        }

        .owners-page .card-body {
            padding: 0;
        }

        .owners-page .table {
            margin-bottom: 0;
        }

        .owners-page .table thead th {
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

        .owners-page .table tbody td {
            padding: 0.95rem 1.25rem;
            vertical-align: middle;
            border-color: #f0ebf5;
            color: #334155;
        }

        .owners-page .table tbody tr:hover {
            background: #faf7fc;
        }

        .owners-page .owner-name {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .owners-page .owner-avatar {
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

        .owners-page .owner-name-text {
            font-weight: 600;
            color: #2d1f3d;
            line-height: 1.3;
            word-break: break-word;
        }

        .owners-page .owner-meta {
            color: #64748b;
            font-size: 0.9rem;
        }

        .owners-page .badge-access {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 0.35rem 0.75rem;
            font-size: 0.78rem;
            font-weight: 600;
        }

        .owners-page .badge-access.yes {
            background: #ecfdf3;
            color: #15803d;
        }

        .owners-page .badge-access.no {
            background: #fef2f2;
            color: #b91c1c;
        }

        .owners-page .btn-acoes {
            background: #6A4486;
            border-color: #6A4486;
            color: #fff;
            border-radius: 8px;
            font-size: 0.85rem;
            padding: 0.4rem 0.85rem;
        }

        .owners-page .btn-acoes:hover,
        .owners-page .btn-acoes:focus {
            background: #5a3872;
            border-color: #5a3872;
            color: #fff;
        }

        .owners-page .dropdown-menu {
            border: none;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(45, 31, 61, 0.12);
            padding: 0.4rem;
            min-width: 180px;
        }

        .owners-page .dropdown-item {
            border-radius: 6px;
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
            display: block;
            width: 100%;
            text-align: left;
            background: transparent;
            border: none;
        }

        .owners-page .dropdown-item:hover {
            background: #f4f0f7;
            color: #6A4486;
        }

        .owners-page .dropdown-item.delete:hover {
            background: #fef2f2;
            color: #dc2626;
        }

        .owners-page .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #64748b;
        }

        .owners-page .empty-state i {
            font-size: 2rem;
            color: #c4b5d4;
            margin-bottom: 0.75rem;
        }

        .owners-page .pagination-wrap {
            padding: 1rem 1.25rem;
            border-top: 1px solid #f0ebf5;
            background: #fcfbfd;
        }
    </style>

    <div class="container-fluid owners-page px-3">
        <div class="card owners-card">
            <div class="owners-header">
                <div>
                    <h1>Proprietários</h1>
                    <p class="subtitle">Gerencie os proprietários cadastrados</p>
                </div>
                <div class="toolbar">
                    <a href="{{ route('owner.create') }}" class="btn btn-criar">
                        <i class="fas fa-plus mr-1"></i> Criar Proprietário
                    </a>
                    <div class="search-wrap">
                        <input type="search" name="filter" id="buscar-owner"
                            placeholder="Buscar por nome, e-mail, documento ou telefone..."
                            class="form-control buscar-owner" autocomplete="off">
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
                                <th>Possui acesso?</th>
                                <th style="width: 120px;">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="filter">
                            @include('admin.owners.search', ['owners' => $owners])
                        </tbody>
                    </table>
                </div>
                <div class="pagination-wrap">
                    {{ $owners->links() }}
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

            $(document).on('click', '.create-access', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: "{{ route('owner.access') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso',
                            text: 'Acesso criado com sucesso',
                        }).then(() => window.location.reload());
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Algo deu errado!',
                        });
                    }
                });
            });

            function buscarOwners(search) {
                if (search === lastSearch) {
                    return;
                }
                lastSearch = search;

                if (search === '') {
                    window.location.reload();
                    return;
                }

                $.ajax({
                    url: "{{ route('owners.search') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        search: search
                    },
                    success: function(data) {
                        if (data && data[0] && data[0].viewRender !== undefined) {
                            $('.filter').html(data[0].viewRender ||
                                '<tr><td colspan="4"><div class="empty-state"><div><i class="fas fa-users"></i></div><div>Nenhum proprietário encontrado.</div></div></td></tr>'
                            );
                        }
                    }
                });
            }

            $('#buscar-owner').on('input', function() {
                var search = $.trim($(this).val());
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function() {
                    buscarOwners(search);
                }, 300);
            });

            $(document).on('click', '.delete', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Você tem certeza?',
                    text: "Você não poderá reverter isso!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#6A4486',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sim, deletar!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('owner.delete') }}",
                            type: "POST",
                            data: {
                                id: id,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function() {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sucesso',
                                    text: 'Proprietario deletado com sucesso',
                                }).then(() => window.location.reload());
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Algo deu errado!',
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
