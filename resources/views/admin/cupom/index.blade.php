@extends('layouts.admin')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Cupons</h1>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Criar Cupom
                </button>
            </div>
            <div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Porcentagem de desconto</th>
                            <th>Situação</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse ($cupons as $cupon)
                            <tr>
                                <td>{{ $cupon->code }}</td>
                                <td>{{ $cupon->value }}</td>
                                <td>Ativo</td>
                                <td>
                                   
                                    <a href="{{ route('cupom.delete', $cupon->id) }}" class="btn btn-sm btn-danger">Excluir</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td>Sem animal</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Cadastrar cupom</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('cupons.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Código do cupom</label>
                                <input type="text" class="form-control" name="code">
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Porcetagem de desconto</label>
                                <input type="text" class="form-control" name="value">
                            </div>
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
