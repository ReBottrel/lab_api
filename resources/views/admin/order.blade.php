@extends('layouts.admin')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Pedidos</h4>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('export.pay') }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-alt-loci text-white float-end export-pay">Exportar
                                Relatório de Pedidos Pagos</button>
                        </form>
                    </div>
                </div>
                <div class="row my-4">
                    <div class="col-6">
                        <form>
                            <label for="exampleFormControlInput1" class="form-label">Buscar proprietário</label>
                            <input class="form-control search" type="search" placeholder="Buscar pelo nome...">
                        </form>
                    </div>
                    <div class="col-6">
                        <form>
                            <label for="exampleFormControlInput1" class="form-label">Filtrar Amostra</label>
                            <select class="form-select status-filter">
                                <optgroup label="Status">
                                    <option value="0"> Todos</option>
                                    <option value="1"> Aguardando amostra</option>
                                    <option value="2"> Amostra recebida</option>
                                    <option value="7"> Amostra aprovada</option>
                                    <option value="6"> Amostra reprovada</option>
                                    <option value="10"> Pedido concluído</option>
                                    <option value="11"> Aguardando pagamento</option>
                                    <option value="9"> Pagamento confirmado</option>
                                </optgroup>
                            </select>
                        </form>
                    </div>

                </div>
                <div class="row my-3">
                    <div class="col-6">
                        <form>
                            <label for="exampleFormControlInput1" class="form-label">Buscar numero do pedido</label>
                            <input class="form-control number-search" type="search" placeholder="">
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div>
                        <p>Filtro por data de pagamento</p>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Inicio</label>
                            <input type="date" class="form-control" id="inicio">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Fim</label>
                            <input type="date" class="form-control" id="fim">
                        </div>
                    </div>
                    <div class="col-md-2 d-flex btn-buscar">
                        <div class="">
                            <button type="button" class="btn btn-primary buscar">BUSCAR</button>
                        </div>
                    </div>
                    <div class="col-md-2 d-none d-flex btn-buscar export">
                        <div class="">
                            <form action="{{ route('export.filter') }}" method="post">
                                @csrf
                                <input type="hidden" name="from" id="from">
                                <input type="hidden" name="to" id="to">
                                <button type="submit" class="btn btn-success export-filter"><i
                                        class="fa-solid fa-file-excel"></i>
                                    EXPORTAR</button>
                            </form>

                        </div>
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
                        </div>
                    @endforeach
                    {{ $orders->links() }}
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


            $('.status-payment').change(function() {
                var status = $(this).val();
                $.ajax({
                    url: "{{ route('filter.payment') }}",
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

            $('.number-search').on('input', function() {
                var search = $(this).val();
                $.ajax({
                    url: "{{ route('filter.search.number') }}",
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

            $(document).on('click', '.buscar', function() {
                var inicio = $('#inicio').val();
                var fim = $('#fim').val();
                $.ajax({
                    url: "{{ route('filter.date') }}",
                    type: "GET",
                    data: {
                        _token: "{{ csrf_token() }}",
                        from: inicio,
                        to: fim
                    },
                    beforeSend: function() {
                        $('.buscar').html(`<div class="spinner-border text-primary" role="status">
        <span class="sr-only">Loading...</span>
        </div>`);
                    },
                    success: function(data) {
                        $('.buscar').html(`BUSCAR`);

                        $('.filter').html(data[0].viewRender);
                        $('.export').removeClass('d-none');
                        $('#from').val(inicio);
                        $('#to').val(fim);
                    },
                    error: function(er) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Encontramos um erro!',
                        })
                    }
                });
            });

        });
    </script>
@endsection
