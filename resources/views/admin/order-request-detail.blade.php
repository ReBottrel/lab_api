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
                <h5 class="mb-0">CLIENTE: {{ $order->user->name }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <ul class="list-group">
                            <li class="list-group-item text-uppercase"><span>ORIGEM DO PEDIDO: {{ $order->origin }}</span>
                            </li>
                            <li class="list-group-item"><span>TOTAL: {{ 'R$ ' . number_format($order->total, 2, ',', '.') }}
                                </span></li>
                            <li class="list-group-item"><span>E-MAIL: {{ $order->user->email }}</span>
                            </li>
                            <li class="list-group-item"><span>TELEFONE: {{ $userInfo->info->phone }}</span>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="container" style="margin-top: 20px;">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">PRODUTOS DO PEDIDO</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">

                        @foreach ($order->orderRequestPayment as $item)
                            {{-- Aqui é os status de pagamento --}}
                            @if ($item->payment_status == 0)
                                @php
                                    $status = 'Aguardando pagamento';
                                @endphp
                            @elseif($item->payment_status == 1)
                                @php
                                    $status = 'Pagamento aprovado';
                                @endphp
                            @elseif($item->payment_status == 2)
                                @php
                                    $status = 'Pagamento recusado';
                                @endphp
                            @elseif($item->payment_status == 3)
                                @php
                                    $status = 'Pagamento cancelado';
                                @endphp
                            @elseif($item->payment_status == 4)
                                @php
                                    $status = 'Pagamento em análise';
                                @endphp
                            @elseif($item->payment_status == 5)
                                @php
                                    $status = 'Pagamento estornado';
                                @endphp
                            @elseif($item->payment_status == 6)
                                @php
                                    $status = 'Pagamento em disputa';
                                @endphp
                            @endif

                            @if ($item->days == 0)
                                @php
                                    $days = '20 dias (Padrão)';
                                @endphp
                            @elseif($item->days == 1)
                                @php
                                    $days = '24 horas';
                                @endphp
                            @elseif($item->days == 2)
                                @php
                                    $days = '2 Dias';
                                @endphp
                            @elseif($item->days == 3)
                                @php
                                    $days = '5 Dias';
                                @endphp
                            @elseif($item->days == 4)
                                @php
                                    $days = '15 Dias';
                                @endphp
                            @endif

                            <ul class="list-group my-4">
                                <li class="list-group-item"><span>PRODUTO: {{ $item->animal }}</span></li>
                                <li class="list-group-item text-uppercase"><span>EXAME: {{ $item->title }} /
                                        {{ $item->category }}</span></li>
                                <li class="list-group-item"><span>VALOR:
                                        {{ 'R$ ' . number_format($item->value, 2, ',', '.') }}</span></li>
                                <li class="list-group-item text-white text-uppercase @if($status == 'Pagamento aprovado') bg-success @elseif($status == 'Pagamento cancelado' && $status == 'Pagamento recusado') bg-danger @else bg-primary @endif"><span>STATUS DE PAGAMENTO:
                                        {{ $status }}</span></li>
                                <li class="list-group-item"><span>TEMPO: {{ $days }}
                                    </span></li>
                                <li class="list-group-item"><span>ID DE PAGAMENTO: {{ $item->payment_id }}
                                    </span></li>
                            </ul>
                        @endforeach

                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- <div class="container" style="margin-top: 20px;">
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
    </div> --}}
@endsection
