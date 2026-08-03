@foreach ($orders as $order)
    <div class="text-secondary border rounded shadow orders order-item" data-id="{{ $order->id }}"
        style="background: var(--bs-gray-300); margin-top: 12px; margin-bottom: 12px; width: 100%;">
        <div class="row align-items-center p-2 m-0">
            <div class="col-lg-10">
                <div class="row">
                    <div class="col">
                        <p class="mb-1">Numero do pedido:</p>
                    </div>
                    <div class="col">
                        <p class="mb-1">Cliente:</p>
                    </div>
                    <div class="col">
                        <p class="mb-1">Origem:</p>
                    </div>
                    <div class="col">
                        <p class="mb-1">Data:</p>
                    </div>
                    <div class="col">
                        <p class="mb-1">Status</p>
                    </div>
                </div>
                <div class="row fw-bold text-dark">
                    <div class="col">
                        <p class="mb-1">{{ $order->id }}</p>
                    </div>
                    <div class="col">
                        <p class="mb-1">{{ $order->creator }}</p>
                    </div>
                    <div class="col">
                        <p class="mb-1">{{ $order->origin }}</p>
                    </div>
                    <div class="col">
                        <p class="mb-1">{{ date('d/m/Y', strtotime($order->created_at)) }}</p>
                    </div>
                    <div class="col">
                        <p class="mb-1">
                            <span @if ($order->id_tecnico) class="text-success" @else class="text-danger" @endif>T</span>
                            /
                            <span @if ($order->owner_id) class="text-success" @else class="text-danger" @endif>C</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 text-lg-end">
                <div class="dropdown">
                    <a class="btn btn-alt-loci text-white dropdown-toggle" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Ações
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item"
                            href="@if ($order->origin == 'email') {{ route('order.detail', $order->id) }} @else {{ route('order.sistema.detail', $order->id) }} @endif">Ver</a>
                        <a class="dropdown-item" href="{{ route('orders.owner', $order->id) }}">Proprietario</a>
                        <a class="dropdown-item" href="{{ route('technical', $order->id) }}">Técnico responsável</a>
                        @if ($order->status == 2)
                            <a class="dropdown-item"
                                href="{{ route('order.request.detail', $order->id) }}">Detalhes do pedido</a>
                        @endif
                        <a class="dropdown-item" href="{{ route('orders.delete', $order->id) }}">Excluir</a>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endforeach
