@extends('layouts.admin')
@section('content')
    <div class="container mt-5">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row mb-4 align-items-center">
                    <div class="col">
                        <h4 class="fw-bold text-primary">Exames</h4>
                    </div>
                    <div class="col text-end">
                        <button class="btn btn-primary" type="button" data-bs-target="#modal-1" data-bs-toggle="modal">
                            <i class="bi bi-plus-circle"></i> Adicionar Exame
                        </button>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <form action="{{ route('admin.exames.especies') }}" method="GET">
                            <div class="input-group mb-3">
                                <select class="form-select" name="animal" id="filter-animal">
                                    <option value="">Todas as Espécies</option>
                                    <option value="equinos" {{ request('animal') == 'equinos' ? 'selected' : '' }}>Equinos
                                    </option>
                                    <option value="bovinos" {{ request('animal') == 'bovinos' ? 'selected' : '' }}>Bovinos
                                    </option>
                                    <option value="asininos_muares"
                                        {{ request('animal') == 'asininos_muares' ? 'selected' : '' }}>Asininos e Muares
                                    </option>
                                </select>
                                <select class="form-select" name="category" id="filter-category">
                                    <option value="">Todos os Tipos</option>
                                    <option value="dna" {{ request('category') == 'dna' ? 'selected' : '' }}>DNA</option>
                                    <option value="sorologia" {{ request('category') == 'sorologia' ? 'selected' : '' }}>
                                        Sorologia</option>
                                </select>
                                <button class="btn btn-outline-secondary" type="submit">Filtrar</button>
                            </div>
                        </form>
                    </div>
                </div>

                @foreach ($exames as $exame)
                    <div class="card mb-3 border-0 shadow-sm exam-card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <h5 class="card-title mb-0">{{ $exame->title }}</h5>
                                    <p class="text-muted">{{ ucfirst($exame->category) }}</p>
                                </div>
                                <div class="col-md-3">
                                    <p class="mb-0">
                                        {{ ucfirst($exame->animal == 'asininos_muares' ? 'Asininos e Muares' : $exame->animal) }}
                                    </p>
                                </div>
                                <div class="col-md-3">
                                    <p class="text-success mb-0">{{ 'R$ ' . number_format($exame->value, 2, ',', '.') }}</p>
                                </div>
                                <div class="col-md-3 text-end">
                                    <div class="btn-group">
                                        <button class="btn btn-outline-secondary">Ações</button>
                                        <button class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split"
                                            data-bs-toggle="dropdown" aria-expanded="false"></button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item edit" data-id="{{ $exame->id }}"
                                                    data-bs-target="#edit-exame" data-bs-toggle="modal">Editar</a></li>
                                            <li><a class="dropdown-item btn-delete"
                                                    href="{{ route('exame.delete', $exame->id) }}">Excluir</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="d-flex justify-content-center">
                    {{ $exames->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add Exame -->
    <div class="modal fade" id="modal-1" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Adicionar Exame</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('exame.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Categoria do Exame</label>
                            <select class="form-select" id="category-create" name="category">
                                <option value="dna">DNA</option>
                                <option value="sorologia">Sorologia</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Espécie</label>
                            <select class="form-select" name="animal">
                                <option value="equinos">Equinos</option>
                                <option value="bovinos">Bovinos</option>
                                <option value="asininos_muares">Asininos e Muares</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Título do Exame</label>
                            <input type="text" class="form-control" name="title">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Preço do Exame</label>
                            <input type="text" class="form-control" name="value" id="preco">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Dias</label>
                            <input type="text" class="form-control" name="days" id="days">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descrição</label>
                            <textarea class="form-control" name="short_description"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Exame -->
    <div class="modal fade" id="edit-exame" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Exame</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('exame.update') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id">
                        <div class="mb-3">
                            <label class="form-label">Categoria do Exame</label>
                            <select class="form-select" id="category" name="category">
                                <option value="dna">DNA</option>
                                <option value="sorologia">Sorologia</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Espécie</label>
                            <select class="form-select" name="animal">
                                <option value="equinos">Equinos</option>
                                <option value="bovinos">Bovinos</option>
                                <option value="asininos_muares">Asininos e Muares</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Título do Exame</label>
                            <input type="text" class="form-control" name="title">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Preço do Exame</label>
                            <input type="text" class="form-control" name="value" id="preco">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Dias</label>
                            <input type="number" class="form-control" name="days" id="days">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descrição</label>
                            <textarea class="form-control" name="short_description"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $('#category-create').change(function() {
            if ($(this).val() == 'sorologia') {
                $('#tipo-create').removeClass('d-none');
            } else {
                $('#tipo-create').addClass('d-none');
            }
        });
    </script>
@endsection
