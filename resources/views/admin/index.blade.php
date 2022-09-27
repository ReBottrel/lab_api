@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex justify-content-between align-items-center mb-4">
            <h3 class="text-dark mb-0">PAINEL</h3>
        </div>
        <div class="row">
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card shadow border-start-primary py-2">
                    <div class="card-body">
                        <div class="row align-items-center no-gutters">
                            <div class="col me-2">
                                <div class="text-uppercase text-primary fw-bold text-xs mb-1">
                                    <span>FATURAMENTO MENSAL</span>
                                </div>
                                <div class="text-dark fw-bold h5 mb-0"><span>R$ 0,00</span></div>
                            </div>
                            <div class="col-auto"><i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card shadow border-start-success py-2">
                    <div class="card-body">
                        <div class="row align-items-center no-gutters">
                            <div class="col me-2">
                                <div class="text-uppercase text-success fw-bold text-xs mb-1">
                                    <span>FATURAMENTO ANUAL</span>
                                </div>
                                <div class="text-dark fw-bold h5 mb-0"><span>R$ 0,00</span></div>
                            </div>
                            <div class="col-auto"><i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card shadow border-start-info py-2">
                    <div class="card-body">
                        <div class="row align-items-center no-gutters">
                            <div class="col me-2">
                                <div class="text-uppercase text-info fw-bold text-xs mb-1">
                                    <span>PEDIDOS</span>
                                </div>
                                <div class="row g-0 align-items-center">
                                    <div class="col-auto">
                                        <div class="text-dark fw-bold h5 mb-0 me-3"><span>{{ $orders->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto"><i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card shadow border-start-warning py-2">
                    <div class="card-body">
                        <div class="row align-items-center no-gutters">
                            <div class="col me-2">
                                <div class="text-uppercase text-warning fw-bold text-xs mb-1"><span>TOTAL
                                        DE CLIENTES</span></div>
                                <div class="text-dark fw-bold h5 mb-0"><span>0</span></div>
                            </div>
                            <div class="col-auto"><i class="fas fa-user-friends fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="container">
            <div class="row">
                <div class="col">
                    <h5>PEDIDOS</h5>
                </div>
            </div>
        </div>
    </div>
    <section style="height: 70px;">
        @foreach ($orders as $order)
            <div class="container">
                <div class="text-secondary border rounded shadow orders"
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
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" data-id="{{ $order->id }}" id="btn-aceitar">Aceitar</a>
                              
                                    <a class="dropdown-item" id="show-btn"
                                        href="{{ route('order.detail', $order->id) }}">Ver</a>
                                    <a class="dropdown-item" href="#">Excluir</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

    </section>
@endsection
