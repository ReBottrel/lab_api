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
                        @foreach ($order->orderRequestPayment as $item)
                            @if ($item->payment_status == 1)
                                @if ($item->payment_status == 1)
                                    @php
                                        
                                        $status = 'Pagamento confirmado';
                                        
                                    @endphp
                                @endif
                                <div class="row order-itens">
                                    <div class="col-md-6">
                                        <p>Produto: <span>{{ $item->animal }}</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p>Tipo de Exame: <span>{{ $item->category }}</span></p>
                                    </div>
                                    <div class="col-md-6 payment-ok">
                                        <p>Status de Pagamento: <span>{{ $status }}</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p>ID de Pagamento: <span>{{ $item->payment_id ?? 'Pago fora do sistema' }}</span>
                                        </p>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('scripts')
    @endsection
