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
                    <iframe width="100%" height="650" src="https://lookerstudio.google.com/embed/reporting/a4cbcfc2-4782-4f40-b8c3-61341c8e7170/page/KNBeD" frameborder="0" style="border:0" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>


    <div class="ms-4">
        {{-- {{ $orders->links() }} --}}
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
