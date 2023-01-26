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
                @php
                    $rota = '';
                    switch ($order->tipo) {
                        case 1:
                            $rota = "/add-animal-create/$order->id";
                            break;
                        case 5:
                            $rota = "/add-parentesco-create/$order->id";
                            break;
                    
                        default:
                            $rota = '#';
                            break;
                    }
                @endphp
                <div class="my-5">
                    <div class="my-3">
                        <a href="{{ $rota }}"><button class="btn btn-primary">Criar
                                ou Adicionar Produto</button></a>
                    </div>

                    <div>

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
                                        <div class="d-flex">
                                            <div>
                                                <a href="{{ route('admin.produto.delete', $item->id) }}"> <button
                                                        class="btn btn-danger">Apagar</button></a>

                                            </div>
                                            <div class="mx-2">
                                                <button type="button" class="btn btn-primary edit" data-bs-toggle="modal"
                                                    data-bs-target="#exampleModal"
                                                    data-id="{{ $item->id }}">Editar</button>
                                            </div>
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
@endsection
@section('js')
@endsection
