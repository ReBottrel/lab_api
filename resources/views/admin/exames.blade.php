@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h4>Exames</h4>
                    </div>
                    <div class="col text-end align-self-end me-auto"><button class="btn btn-dark" type="button"
                            data-bs-target="#modal-1" data-bs-toggle="modal">Add Exame</button></div>
                </div>
                @foreach ($exames as $exame)
                    <div class="text-secondary border rounded shadow orders"
                        style="background: var(--bs-gray-300);margin-top: 15px;margin-bottom: 15px;" title="teste">
                        <div class="row justify-content-center align-items-center" style="height: auto;padding: 5px 5px;">
                            <div class="col-xl-10 col-xxl-9 offset-xxl-0">
                                <div class="row" style="height: 25px;">
                                    <div class="col" style="height: 20px;">
                                        <p>Exame:</p>
                                    </div>
                                    <div class="col">
                                        <p>Categoria:</p>
                                    </div>
                                    <div class="col">
                                        <p>Animal:</p>
                                    </div>
                                    <div class="col">
                                        <p>Preço:</p>
                                    </div>
                                </div>
                                <div class="row fw-bold text-dark" style="">
                                    <div class="col" style="height: auto;">
                                        <p class="text-capitalize">{{ $exame->title }}</p>
                                    </div>
                                    <div class="col" style="height: 20px;">
                                        <p class="text-capitalize">{{ $exame->category }}</p>
                                    </div>
                                    <div class="col" style="height: 20px;">
                                        <p class="text-capitalize">
                                            @if ($exame->animal == 'asininos_muares')
                                                Asininos e Muares
                                            @else
                                                {{ $exame->animal }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col" style="height: 20px;">
                                        <p>{{ 'R$ ' . number_format($exame->value, 2, ',', '.') }} </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-1 col-xxl-3">
                                <div class="btn-group border rounded" style="background: var(--bs-success);"><button
                                        class="btn link-light" type="button">Ações</button><button
                                        class="btn btn-sm dropdown-toggle dropdown-toggle-split link-light"
                                        data-bs-toggle="dropdown" aria-expanded="false" type="button"></button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item  edit" data-id="{{ $exame->id }}"
                                            data-bs-target="#edit-exame" data-bs-toggle="modal">Editar</a>
                                        {{-- <a class="dropdown-item" href="#">Ver</a> --}}
                                        <a class="dropdown-item btn-delete"
                                            href="{{ route('exame.delete', $exame->id) }}">Excluir</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div>
                    {{ $exames->links() }}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" role="dialog" tabindex="-1" id="modal-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Adicionar Exame</h4><button type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('exame.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div>
                            <div style="margin: 0;margin-top: 10px;margin-bottom: 10px;"><label class="form-label">Categoria
                                    do Exame</label><select class="form-select" id="category-create" name="category">
                                    <option value="dna">DNA</option>
                                    <option value="sorologia">SOROLOGIA</option>
                                </select>
                            </div>
                    
                            <div style="margin: 0;margin-top: 10px;margin-bottom: 10px;">
                                <label class="form-label">Espécie</label>
                                <select class="form-select" name="animal">
                                    <option value="equinos">EQUINOS</option>
                                    <option value="bovinos">BOVINOS</option>
                                    <option value="asininos_muares">ASININOS E MUARES</option>
                                </select>
                            </div>
                            <div style="margin: 0;margin-top: 10px;margin-bottom: 10px;"><label class="form-label">Titulo do
                                    exame</label><input class="form-control" type="text" name="title"></div>
                            <div style="margin: 0;margin-top: 10px;margin-bottom: 10px;">
                                <label class="form-label">Preço do
                                    exame</label>
                                <input class="form-control" type="text" name="value" id="preco">
                            </div>
                            <div style="margin: 0;margin-top: 10px;margin-bottom: 10px;">
                                <label class="form-label">Verificação Extra</label>
                                <input class="form-control" type="text" name="extra_value" id="extrapreco">
                            </div>
                            <div style="margin: 0;margin-top: 10px;margin-bottom: 10px;"><label
                                    class="form-label">Descrição</label>
                                <textarea class="form-control" name="short_description"></textarea>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-light" type="button" data-bs-dismiss="modal">Fechar</button>
                        <button class="btn btn-success" type="submit">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" role="dialog" tabindex="-1" id="edit-exame">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Editar Exame</h4><button type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('exame.update') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id">
                        <div>
                            <div style="margin: 0;margin-top: 10px;margin-bottom: 10px;"><label
                                    class="form-label">Categoria
                                    do Exame</label><select class="form-select" id="category" name="category">
                                    <option value="dna">DNA</option>
                                    <option value="sorologia" id="sorologia">SOROLOGIA</option>
                                </select>
                            </div>
                 
                            <div style="margin: 0;margin-top: 10px;margin-bottom: 10px;">
                                <label class="form-label">Espécie</label>
                                <select class="form-select" name="animal">
                                    <option value="equinos">EQUINOS</option>
                                    <option value="bovinos">BOVINOS</option>
                                    <option value="asininos_muares">ASININOS E MUARES</option>
                                </select>
                            </div>
                            <div style="margin: 0;margin-top: 10px;margin-bottom: 10px;"><label class="form-label">Titulo
                                    do
                                    exame</label><input class="form-control" type="text" name="title"></div>
                            <div style="margin: 0;margin-top: 10px;margin-bottom: 10px;">
                                <label class="form-label">Preço do
                                    exame</label>
                                <input class="form-control" type="text" name="value" id="preco">
                            </div>
                            <div style="margin: 0;margin-top: 10px;margin-bottom: 10px;">
                                <label class="form-label">Verificação Extra</label>
                                <input class="form-control" type="text" name="extra_value" id="extrapreco">
                            </div>
                            <div style="margin: 0;margin-top: 10px;margin-bottom: 10px;"><label
                                    class="form-label">Descrição</label>
                                <textarea class="form-control" name="short_description"></textarea>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-light" type="button" data-bs-dismiss="modal">Fechar</button>
                        <button class="btn btn-success" type="submit">Salvar</button>
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
                $('#tipo-create').removeClass('d-none')
            } else {
                $('#tipo-create').addClass('d-none')
            }
        })
    </script>
@endsection
