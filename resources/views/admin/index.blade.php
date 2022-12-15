@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex justify-content-between align-items-center mb-4">
            <h3 class="text-dark mb-0">PAINEL</h3>
        </div>
        @if (auth()->user()->permission == 10)
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
                                <p>{{ date('d/m/Y', strtotime($order->created_at)) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-1 col-xxl-3">
                        <div class="dropdown">
                            <a class="btn btn-alt-loci text-white dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Ações
                            </a>

                            <ul class="dropdown-menu">
                                <a class="dropdown-item" data-id="{{ $order->id }}" id="btn-aceitar">Aceitar</a>
                                <a class="dropdown-item" id="show-btn"
                                    href="{{ route('order.detail', $order->id) }}">Ver</a>
                                <a class="dropdown-item" data-id="{{ $order->id }}" id="btn-delete">Excluir</a>
                            </ul>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    @endforeach

    <div class="ms-4">
        {{ $orders->links() }}
    </div>
    </div>
@else
    <div class="">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h5>BEM VINDO, {{ auth()->user()->name }}</h5>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection
@section('js')
    <script>
        $(document).on('click', '#btn-delete', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Tem certeza?',
                text: "Você não poderá reverter isso!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, deletar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/order-delete/' + id,
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            Swal.fire(
                                'Deletado!',
                                'O pedido foi deletado.',
                                'success'
                            )
                            location.reload();
                        }
                    });
                }
            })
        });
    </script>
@endsection
