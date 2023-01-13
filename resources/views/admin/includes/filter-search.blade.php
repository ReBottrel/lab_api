@foreach ($orders as $order)
    <div class="container">
        <div class="text-secondary border rounded shadow orders" data-id="{{ $order->id }}"
            style="background: var(--bs-gray-300);margin-top: 15px;margin-bottom: 15px;">
            <div class="row justify-content-center align-items-center" style="height: auto;padding: 5px ;">
                <div class="row justify-content-center align-items-center"
                style="height: auto;padding: 5px ;">
                <div class="col-xl-10 col-xxl-9 offset-xxl-0">
                    <div class="row" style="height: auto;">
                        <div class="col">
                            <p>Numero do pedido:</p>
                        </div>
                        <div class="col">
                            <p>Cliente:</p>
                        </div>
                        <div class="col">
                            <p>Origem:</p>
                        </div>
                        <div class="col">
                            <p>Data:</p>
                        </div>
                        <div class="col">
                            <p>Status</p>
                        </div>
                    </div>
                    <div class="row fw-bold text-dark" style="height: auto;">
                        <div class="col">
                            <p>{{ $order->id }}</p>
                        </div>
                        <div class="col">
                            <p>{{ $order->creator }}</p>
                        </div>
                        <div class="col">
                            <p>{{ $order->origin }}</p>
                        </div>
                        <div class="col">
                            <p>{{ date('d/m/Y', strtotime($order->created_at)) }}</p>
                        </div>
                        <div class="col">
                            <p><span
                                    @if ($order->id_tecnico) class="text-success"

                            @else
                                class="text-danger" @endif>T</span>
                                / <span
                                    @if ($order->owner_id) class="text-success"
                                @else
                                    class="text-danger" @endif>C</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-1 col-xxl-3">
                    <div class="dropdown">
                        <a class="btn btn-alt-loci text-white dropdown-toggle" href="#"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Ações
                        </a>

                        <ul class="dropdown-menu">
                            <a class="dropdown-item" id="show-btn"
                                href="@if ($order->origin == 'email') {{ route('order.detail', $order->id) }} @else {{ route('order.sistema.detail', $order->id) }} @endif">Ver</a>
                            <a class="dropdown-item" id="show-btn"
                                href="{{ route('orders.owner', $order->id) }}">Proprietario</a>
                            <a class="dropdown-item" id="show-btn"
                                href="{{ route('technical', $order->id) }}">Técnico
                                responsável</a>
                            @if ($order->status == 2)
                                <a class="dropdown-item"
                                    href="{{ route('order.request.detail', $order->id) }}">Detalhes do
                                    pedido</a>
                            @endif
                            <a class="dropdown-item" id="show-btn"
                                href="{{ route('orders.delete', $order->id) }}">Excluir</a>
                        </ul>
                    </div>

                </div>

      
        </div>
    </div>
@endforeach
