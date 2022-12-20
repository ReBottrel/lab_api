@extends('layouts.admin')
@section('content')
    <div class="container">
        <div class="card my-4">
            <div class="card-header">
                <h4>Criar pedido add produto</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Criador: {{ $order->creator }}</h4>
                    </div>
                    <div class="col-md-6">
                        <h4>Técnico: {{ $order->technical_manager }}</h4>
                    </div>
                </div>
                <div class="my-5">
                    <div class="my-3">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Adicionar
                            Produto</button>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Produto</th>
                                <th scope="col">Numero de registro</th>
                                <th scope="col">Sexo</th>
                                <th scope="col">Acões</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($animals as $item)
                                <tr>
                                    <th scope="row">{{ $item->animal_name }}</th>
                                    <td>{{ $item->register_number_brand }}</td>
                                    <td>{{ $item->sex }}</td>
                                    <td>
                                        <div>
                                            <a href="{{ route('admin.produto.delete', $item->id) }}"> <button
                                                    class="btn btn-danger">Apagar</button></a>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">Nenhum produto adicionado ao pedido</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($animals->count() > 0)
                    <div class="text-center">
                        <a href="{{ route('order.end.painel', $order->id) }}"> <button
                                class="btn btn-success text-white">Finalizar pedido</button></a>
                    </div>
                @endif

            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Adicionar produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.order-add-animal-post') }}" method="post">
                    @csrf
                    <input type="hidden" name="order" value="{{ $order->id }}">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Nome do animal</label>
                                    <input type="text" name="animal_name" id="animal_name" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">CHIP do animal</label>
                                    <input type="text" name="register_number_brand" id="register_number_brand"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Sexo</label>
                                    <select class="form-select" name="sex">
                                        <option value="F">F</option>
                                        <option value="M">M</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Espécie</label>
                                    <select class="form-select" name="especies">
                                        <option value="EQUINA">EQUINA</option>
                                        <option value="BOVINA">BOVINA</option>
                                        <option value="ASININO">ASININOS</option>
                                        <option value="MUARES">MUARES</option>
                                        <option value="EQUINO_PEGA">EQUINO PÊGA</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Raça</label>
                                    <select class="form-select" name="breed">
                                        <option value="MANGALARGA">MANGALARGA MARCHADOR</option>
                                        <option value="PÊGA">PÊGA</option>
                                        <option value="QUARTO DE MILHO">QUARTO DE MILHA</option>
                                        <option value="PAMPA">PAMPA</option>
                                        <option value="DESCONHECIDA">DESCONHECIDA</option>
                                        <option value="NELORE">NELORE</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Data de coleta</label>
                                    <input type="date" name="" id="birth_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Idade</label>
                                    <input type="text" name="age" id="age" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Data de nascimento</label>
                                    <input type="date" name="birth_date" id="birth_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Numero do chip</label>
                                    <input type="text" name="chip_number" id="chip_number" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Numero de registro do
                                        pai</label>
                                    <input type="text" name="registro_pai" id="registro_pai" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Nome do pai</label>
                                    <input type="text" name="pai" id="pai" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Numero de registro da
                                        mãe</label>
                                    <input type="text" name="registro_mae" id="registro_mae" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Nome da mãe</label>
                                    <input type="text" name="mae" id="mae" class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Salvar</button>
                            </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
@endsection
