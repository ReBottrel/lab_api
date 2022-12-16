@extends('layouts.loja')
@section('content')
    <div class="container">
        <h1 class="text-primary">Pedidos concluídos</h1>
        <div class="row gx-3">
            @component('layouts.partials.user-menu')
            @endcomponent
            <div class="col-md-8 col-12 my-3">
                <div class="container">
                    <div class="row">
                        @foreach ($orders as $item)
                            <div class="order">
                                <a href="{{ route('orders.done.detail', $item->id) }}">
                                    <div class="order-flex">
                                        <div>
                                            <p>Numero do pedido: {{ $item->id }}</p>
                                        </div>
                                        <div>
                                            <p>Data: {{ date('d/m/Y', strtotime($item->created_at)); }}</p>
                                        </div>
                                        <div>
                                            <p>Clique para ver mais informações</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('scripts')
    @endsection
