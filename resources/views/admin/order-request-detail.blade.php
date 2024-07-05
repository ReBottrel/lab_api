@extends('layouts.admin')
@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">Detalhe do Pedido #{{ $order->id }}</h2>
            </div>
        </div>
    </div>
    <section></section>
    <div class="container my-4">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">CLIENTE: {{ $order->owner->name }}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <ul class="list-group">
                            <li class="list-group-item text-uppercase"><span>ORIGEM DO PEDIDO: {{ $order->origin }}</span>
                            </li>
                            <li class="list-group-item"><span>TOTAL: {{ 'R$ ' . number_format($order->total, 2, ',', '.') }}
                                </span></li>
                            <li class="list-group-item"><span>E-MAIL: {{ $order->owner->email }}</span>
                            </li>
                            <li class="list-group-item"><span>TELEFONE: {{ $order->owner->cell }}</span>
                            </li>

                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">PRODUTOS DO PEDIDO</h4>
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
                                    $days = '10 Dias';
                                @endphp
                            @endif

                            <ul class="list-group my-4">
                                <li class="list-group-item"><span>PRODUTO: {{ $item->animal }}</span></li>
                                <li class="list-group-item text-uppercase"><span>EXAME: {{ $item->title }} /
                                        {{ $item->category }}</span></li>
                                <li class="list-group-item"><span>VALOR:
                                        {{ 'R$ ' . number_format($item->value, 2, ',', '.') }}</span></li>
                                <li
                                    class="list-group-item text-white text-uppercase @if ($status == 'Pagamento aprovado') bg-success @elseif($status == 'Pagamento cancelado' && $status == 'Pagamento recusado') bg-danger @else bg-primary @endif">
                                    <span>STATUS DE PAGAMENTO:
                                        {{ $status }}</span>
                                </li>
                                <li class="list-group-item"><span>TEMPO: {{ $days }}
                                    </span></li>
                                <li class="list-group-item"><span>ID DE PAGAMENTO: {{ $item->payment_id }}
                                    </span></li>
                                <li class="list-group-item"><span>DATA DE PAGAMENTO: @if ($item->payment_status == 1)
                                            {{ date('d/m/Y H:i:s', strtotime($item->updated_at)) }}
                                        @else
                                            -
                                        @endif
                                    </span></li>
                                <li>
                                    <button data-id="{{ $item->id }}" class="btn btn-danger delete">EXCLUIR</button>
                                </li>
                            </ul>
                        @endforeach

                    </div>

                </div>
            </div>
            <div class="my-3 text-center">
                <form action="{{ route('export') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $order->id }}">
                    <button type="submit" class="btn btn-success"><i class="fas fa-file-excel"></i> EXPORTAR EXCEL</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).on('click', '.delete', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Você tem certeza?',
                text: "Você não poderá reverter isso!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, deletar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('order.payment.delete') }}",
                        type: "POST",
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            // console.log(data)

                            Swal.fire({
                                icon: 'success',
                                title: 'Sucesso',
                                text: 'deletado com sucesso',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.reload();
                                }
                            });


                        },
                        error: function(data) {
                            console.log(data)
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Algo deu errado!',
                            });
                        }
                    });
                }
            })
        });
    </script>
@endsection
