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
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-0">CRIADOR: {{ $order->creator }} -
                            {{ $order->creator_number }}</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        @foreach ($animals as $animal)
                            @if ($animal)
                                @if ($animal->status == 1)
                                    @php
                                        $status = 'Aguardando amostra';
                                    @endphp
                                @elseif($animal->status == 2)
                                    @php
                                        $status = 'Amostra recebida';
                                    @endphp
                                @elseif($animal->status == 3)
                                    @php
                                        $status = 'Em análise';
                                    @endphp
                                @elseif($animal->status == 4)
                                    @php
                                        $status = 'Análise concluída';
                                    @endphp
                                @elseif($animal->status == 5)
                                    @php
                                        $status = 'Resultado disponível';
                                    @endphp
                                @elseif($animal->status == 6)
                                    @php
                                        $status = 'Análise reprovada';
                                    @endphp
                                @elseif($animal->status == 7)
                                    @php
                                        $status = 'Análise Aprovada';
                                    @endphp
                                @elseif($animal->status == 8)
                                    @php
                                        $status = 'Recoleta solicitada';
                                    @endphp
                                @elseif($animal->status == 9)
                                    @php
                                        $status = 'Amostra paga';
                                    @endphp
                                @elseif($animal->status == 11)
                                    @php
                                        $status = 'Aguardando Pagamento';
                                    @endphp
                                @endif
                            @endif
                            <ul class="list-group m-3">
                                <li class="list-group-item"><span>ID: {{ $animal->register_number_brand }}</span></li>
                                <li class="list-group-item"><span>PRODUTO: {{ $animal->animal_name }}</span></li>
                                <li class="list-group-item"><span>SEXO: {{ $animal->sex }}</span></li>
                                <li class="list-group-item"><span>NASCIMENTO: {{ $animal->birth_date }}</span></li>
                                <li class="list-group-item"><span>PAI: {{ $animal->pai }}</span></li>
                                <li class="list-group-item"><span>REGISTRO DO PAI: {{ $animal->registro_pai }}</span></li>
                                <li class="list-group-item"><span>MÃE: {{ $animal->mae }}</span></li>
                                <li class="list-group-item"><span>REGISTRO DA MÃE: {{ $animal->registro_mae }}</span></li>
                                <li
                                    class="list-group-item text-uppercase @if ($status == 'Análise Aprovada') bg-success @elseif($status == 'Amostra paga') bg-success @elseif($status == 'Análise reprovada') bg-danger @elseif($status == 'Recoleta solicitada') bg-warning @else bg-primary @endif  text-white">
                                    <span>STATUS:
                                        {{ $status }}</span>
                                </li>
                                <li class="list-group-item">
                                    <label for="exampleFormControlInput1" class="form-label">Status do pedido</label>
                                    <select class="form-select status-select" data-order="{{ $order->id }}"
                                        data-id="{{ $animal->id ?? '' }}" aria-label="Default select example">
                                        @if ($animal)
                                            @foreach ($stats as $key => $stat)
                                                <option value="{{ $key }}"
                                                    @if ($animal->status == $key) selected @endif>{{ $stat }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </li>
  
                                @if ($status != 'Aguardando amostra' && $status != 'Aguardando cadastro')
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-4">
                                                <input type="text" class="form-control chip"
                                                    value="{{ $animal->chip_number ?? '' }}"
                                                    data-id="{{ $animal->id ?? '' }}" placeholder="Numero do chip">
                                            </div>
                                            <div class="col-4">
                                                <span>Insira o numero do chip do animal</span>
                                            </div>
                                        </div>
                                    </li>
                                @endif

                            </ul>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container" style="margin-top: 20px;">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">RESPONSAVEL TÉCNICO: {{ $order->technical_manager ?? 'Não encontrado' }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <ul class="list-group">
                            <li class="list-group-item"><span>Data:
                                    {{ date('d/m/Y', strtotime($order->created_at)) }}</span></li>
                            <li class="list-group-item"><span>Origem do pedido: {{ $order->origin }}</span></li>

                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="container" style="margin-top: 20px;">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-baseline">
                    <div class="col align-self-center me-auto"></div>
                    <div class="col text-center align-self-center">
                        @if (isset($animal))
                            @if ($order->status == 4)
                                <button class="btn fw-bold link-light gerar" type="button"
                                    data-order="{{ $order->id }}" style="background: var(--bs-info);">GERAR
                                    PAGAMENTO</button>
                            @elseif($order->status == 2)
                                <div class="row">
                                    <div class="col-6">
                                        <button class="btn fw-bold link-light" type="button" disabled
                                            style="background: var(--bs-info);">PAGAMENTO GERADO</button>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{ route('order.request.detail', $order->id) }}"> <button
                                                class="btn fw-bold link-light" type="button"
                                                style="background: var(--bs-success);">VER RELATÓRIO DE PEDIDO</button></a>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                    <div class="col"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).on('blur', '.cpf-tech', function() {
            var id = $(this).data('id');
            $.ajax({
                url: `/cpf-technical/${id}`,
                type: 'POST',

                data: {
                    cpf: $(this).val()
                },
                success: function(data) {
                    console.log(data);
                }
            });
        });
        $(document).on('blur', '.chip', function() {
            var id = $(this).data('id');
            $.ajax({
                url: `/chip/${id}`,
                type: 'POST',

                data: {
                    chip: $(this).val()
                },
                success: function(data) {
                    console.log(data);
                }
            });
        });
        $(document).on('change', '.status-select', function() {
            var id = $(this).data('id');
            if ($(this).val() == 6 | $(this).val() == 7) {
                var order = $(this).data('order');
            }
            $.ajax({
                url: `/amostra/${id}`,
                type: 'POST',
                data: {
                    value: $(this).val(),
                    order: order
                },
                success: function(data) {
                    console.log(data);
                    window.location.reload();
                },
                error: function(er) {
                    console.log('erro');
                }
            });
        });

        $(document).on('click', '.gerar', function() {
            var id = $(this).data('id');
            var order = $(this).data('order');
            $(this).attr("disabled", true);
            Swal.fire({
                title: 'Confirmar envio de pagamento?',
                text: "Essa ação não poderá ser desfeita!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, continuar'
            }).then((result) => {
                Swal.fire(
                    $.ajax({
                        url: `/order-generate`,
                        type: 'POST',
                        data: {
                            order: order
                        },
                        success: function(data) {
                            console.log(data);
                            window.location.reload();
                        }
                    }));
                if (result.isConfirmed) {
                    Swal.fire(
                        'Confirmado!',
                        'O pagamento foi gerado com sucesso.',
                        'success'
                    )

                }
            });
        });
    </script>
@endsection
