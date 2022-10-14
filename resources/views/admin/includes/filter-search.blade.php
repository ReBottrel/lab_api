@foreach ($orders as $order)
<div class="container">
    <div class="text-secondary border rounded shadow orders" data-id="{{ $order->id }}"
        style="background: var(--bs-gray-300);margin-top: 15px;margin-bottom: 15px;">
        <div class="row justify-content-center align-items-center"
            style="height: auto;padding: 5px ;">
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
                        <p>{{ date('d/m/Y', strtotime($order->created_at)) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-1 col-xxl-3">
                <div class="btn-group border rounded" style="background: var(--bs-success);">
                    <button class="btn link-light" type="button">Ações</button><button
                        class="btn btn-sm dropdown-toggle dropdown-toggle-split link-light"
                        data-bs-toggle="dropdown" aria-expanded="false" type="button"></button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" id="show-btn"
                            href="{{ route('order.detail', $order->id) }}">Ver</a>
                        <a class="dropdown-item" id="show-btn"
                            href="{{ route('orders.owner', $order->id) }}">Proprietario</a>
                        @if ($order->status == 2)
                            <a class="dropdown-item"
                                href="{{ route('order.request.detail', $order->id) }}">Detalhes do
                                pedido</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach