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
                        <form><input class="form-control search" type="search" placeholder="Buscar pelo nome..."></form>
                    </div>
                    <div class="col">
                        <form>
                            <select class="form-select status-filter">
                                <optgroup label="Status">
                                    <option value="0"> Todos</option>
                                    <option value="1"> Aguardando amostra</option>
                                    <option value="2"> Amostra recebida</option>
                                    <option value="7"> Amostra aprovada</option>
                                    <option value="6"> Amostra reprovada</option>
                                    <option value="7"> Aguardando pagamento</option>
                                    <option value="9"> Pagamento confirmado</option>
                                </optgroup>
                            </select>
                        </form>
                    </div>
                </div>
                <div class="filter">
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
                                            <div class="col">
                                                <p>Status</p>
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
                                                    href="{{ route('order.detail', $order->id) }}">Ver</a>
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
                                            </ul>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                  
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('.status-filter').change(function() {
                var status = $(this).val();
                $.ajax({
                    url: "{{ route('filter.status') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        status: status
                    },
                    success: function(data) {
                        console.log(data);
                        $('.filter').html(data[0].viewRender);
                    }
                });
            });
            $('.search').keyup(function() {
                var search = $(this).val();
                $.ajax({
                    url: "{{ route('filter.search') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        search: search
                    },
                    success: function(data) {
                        console.log(data);
                        $('.filter').html(data[0].viewRender);
                    }
                });
            });
        });
    </script>
@endsection
