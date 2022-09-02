@extends('layouts.admin')

@section('content')
    <div class="container" style="margin-bottom: 25px;">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Detalhe do pedido #{{ $order->id }}</h4>
            </div>
        </div>
    </div>
    <section></section>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Nome do usuario do pedido</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <ul class="list-group">
                            <li class="list-group-item"><span></span></li>
                            <li class="list-group-item"><span>Item do pedido</span></li>
                            <li class="list-group-item"><span>Item do pedido</span></li>
                        </ul>
                    </div>
                    <div class="col">
                        <ul class="list-group">
                            <li class="list-group-item"><span>Nome do animal</span></li>
                            <li class="list-group-item"><span></span><span>Pai do animal</span></li>
                            <li class="list-group-item"><span>Mãe do animal</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container" style="margin-top: 20px;">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Numero do criador&nbsp;</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <ul class="list-group">
                            <li class="list-group-item"><span>Responsavel técnico</span></li>
                            <li class="list-group-item"><span>Origem do pedido</span></li>
                            <li class="list-group-item"><span>Status do pedido</span></li>
                        </ul>
                    </div>
                    <div class="col">
                        <ul class="list-group">
                            <li class="list-group-item"><span>Data do pedido</span></li>
                            <li class="list-group-item"><span></span><span>Informações&nbsp;</span></li>
                            <li class="list-group-item"><span>Informações</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container" style="margin-top: 20px;">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-baseline">
                    <div class="col align-self-center me-auto"></div>
                    <div class="col text-center align-self-center"><button class="btn fw-bold link-light" type="button"
                            style="background: var(--bs-info);">PDF</button><button class="btn fw-bold link-light"
                            type="button" style="background: var(--bs-green);margin: 15px;">EXCEL</button></div>
                    <div class="col"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
