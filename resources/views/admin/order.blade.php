@extends('layouts.admin')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h4>Pedidos</h4>
                    </div>
                    <div class="col">
                        <form><input class="form-control" type="search" placeholder="Buscar pelo nome..."></form>
                    </div>
                    <div class="col">
                        <form><select class="form-select">
                                <optgroup label="Status">
                                    <option value="1" selected="">Concluido</option>
                                    <option value="2">Pendente</option>
                                    <option value="3">Cancelado</option>
                                    <option value="4">Produção</option>
                                </optgroup>
                            </select></form>
                    </div>
                </div>
                @foreach ($orders as $order)
                    <div class="container">
                        <div class="text-secondary border rounded shadow orders" data-id="{{ $order->id }}"
                            style="background: var(--bs-gray-300);margin-top: 15px;margin-bottom: 15px;">
                            <div class="row justify-content-center align-items-center" style="height: auto;padding: 5px ;">
                                <div class="col-xl-10 col-xxl-9 offset-xxl-0">
                                    <div class="row" style="height: auto;">
                                        <div class="col">
                                            <p>Origem do pedido:</p>
                                        </div>
                                        <div class="col">
                                            <p>Cliente:</p>
                                        </div>
                                        <div class="col">
                                            <p>Atendimento:</p>
                                        </div>
                                        <div class="col">
                                            <p>Data:</p>
                                        </div>
                                    </div>
                                    <div class="row fw-bold text-dark" style="height: auto;">
                                        <div class="col">
                                            <p>{{ $order->origin }}</p>
                                        </div>
                                        <div class="col">
                                            <p>{{ $order->creator }}</p>
                                        </div>
                                        <div class="col">
                                            <p>{{ $order->collection_number }}</p>
                                        </div>
                                        <div class="col">
                                            <p>{{ $order->collection_date }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-1 col-xxl-3">
                                    <div class="btn-group border rounded" style="background: var(--bs-success);">
                                        <button class="btn link-light" type="button">Ações</button><button
                                            class="btn btn-sm dropdown-toggle dropdown-toggle-split link-light"
                                            data-bs-toggle="dropdown" aria-expanded="false" type="button"></button>
                                        <div class="dropdown-menu"><a class="dropdown-item" id="show-btn"
                                                href="{{ route('order.detail', $order->id) }}">Ver</a><a
                                                class="dropdown-item" href="#">Excluir</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
@endsection
