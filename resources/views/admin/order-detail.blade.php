@extends('layouts.admin')

@section('content')
    <div class="container" style="margin-bottom: 25px;">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Detalhe do pedido #{{ $order->id }}</h4>
            </div>
        </div>
        @if ($lote)
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h4 class="card-title">Este pedido já possuí uma ordem de serviço</h4>
                </div>
            </div>
        @endif
    </div>
    <section></section>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-0">CRIADOR: {{ $order->data_g['data_g']['criador'][1] }} -
                            {{ $order->data_g['data_g']['criador'][0] }}</h5>
                    </div>
                    <div class="col-md-6">
                        <h5 class="mb-0">Parceiro: {{ $order->parceiro ?? 'Sem parceiro' }}</h5>
                        <form action="{{ route('order.parceiro.update') }}" method="post">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            <div class="row">
                                <div class="col-8">
                                    <select class="form-select" name="parceiro" aria-label="Default select example">
                                        <option selected>Selecione o parceiro</option>
                                        @foreach ($parceiros as $parceiro)
                                            <option value="{{ $parceiro->nome }}"
                                                @if ($order->parceiro == $parceiro->nome) selected @endif>{{ $parceiro->nome }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>
                                <div class="col-4">
                                    <button type="submit" class="btn btn-primary">SALVAR</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        @foreach ($order->data_g['data_table'] as $item)
                            @php

                                $animal = App\Models\Animal::where('id', $item['id'])
                                    ->orWhere('register_number_brand', $item['id'])
                                    ->first();
                                if ($animal) {
                                    $get = App\Models\PedidoAnimal::where('id_animal', $animal->id)->first();
                                    $datas = App\Models\DataColeta::where('id_animal', $animal->id)->first();
                                }

                                $status = 'Aguardando amostra';
                            @endphp
                            @if ($animal)
                                @if ($animal->status)
                                    @if ($status == 1)
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
                                    @elseif($animal->status == 10)
                                        @php
                                            $status = 'Pedido Concluído';
                                        @endphp
                                    @elseif($animal->status == 11)
                                        @php
                                            $status = 'Aguardando Pagamento';
                                        @endphp
                                    @elseif($animal->status == 12)
                                        @php
                                            $status = 'Morto';
                                        @endphp
                                    @endif
                                @endif
                            @endif
                            <ul class="list-group m-3">
                                <li class="list-group-item"><span>ID: {{ $item['id'] }}</span></li>
                                <li class="list-group-item"><span>PRODUTO: {{ $item['produto'] }}</span></li>
                                <li class="list-group-item"><span>SEXO: {{ $item['sexo'] }}</span></li>
                                <li class="list-group-item"><span>NASCIMENTO: {{ $item['nascimento'] }}</span></li>
                                <li class="list-group-item"><span>PAI: {{ $item['pai'] }}</span></li>
                                <li class="list-group-item"><span>REGISTRO DO PAI: {{ $item['registro_pai'] }}</span></li>
                                <li class="list-group-item"><span>MÃE: {{ $item['mae'] }}</span></li>
                                <li class="list-group-item"><span>REGISTRO DA MÃE: {{ $item['registro_mae'] }}</span></li>
                                {{-- <li class="list-group-item">{{ $animal->id ?? ''}}</li> --}}
                                <li
                                    class="list-group-item text-uppercase @if ($status == 'Análise Aprovada') bg-success @elseif($status == 'Morto') bg-warning @elseif($status == 'Amostra paga') bg-success @elseif($status == 'Análise reprovada') bg-danger @elseif($status == 'Recoleta solicitada') bg-warning @else bg-primary @endif  text-white">
                                    <span>STATUS:
                                        {{ $status }}</span>
                                </li>

                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-4">
                                            <label for="exampleFormControlInput1" class="form-label">Data de
                                                recebimento</label>
                                            <input type="text" class="form-control datas data-1"
                                                data-id="{{ $animal->id }}" data-type="data_recebimento"
                                                id="data-rece-{{ $animal->id }}"
                                                value="{{ $datas->data_recebimento ?? '' }}" placeholder="">
                                        </div>
                                        <div class="col-4">
                                            <label for="exampleFormControlInput1" class="form-label">Data de
                                                coleta</label>
                                            <input type="text" class="form-control datas data-2"
                                                id="data-coleta-{{ $animal->id }}" data-type="data_coleta"
                                                data-id="{{ $animal->id }}" value="{{ $datas->data_coleta ?? '' }}"
                                                placeholder="">
                                        </div>
                                        <div class="col-4">
                                            <label for="exampleFormControlInput1" class="form-label">Data de
                                                chamado</label>
                                            <input type="text" class="form-control datas data-3"
                                                data-id="{{ $animal->id }}" data-type="data_laboratorio"
                                                id="data-chamado-{{ $animal->id }}"
                                                value="{{ $datas->data_laboratorio ?? '' }}" placeholder="">
                                        </div>

                                    </div>
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
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-4">
                                        <input type="text" name="cpf_technical"
                                            value="{{ $order->cpf_technical ?? '' }}" data-id="{{ $order->id }}"
                                            class="form-control cpf-tech">
                                    </div>
                                    <div class="col-4">
                                        <p>CPF do RESPONSAVEL TÉCNICO</p>
                                    </div>
                                </div>

                            </li>
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
                                    <div class="text-center my-4" data-order="{{ $order->id }}" id="criar-ordem">
                                        <button class="btn btn-alt-2">GERAR ORDEM DE SERVIÇO</button>
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
        $('.datas').mask('00/00/0000');
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
        $(document).on('blur', '.data-1', function() {
            var id = $(this).data('id');
            var data1 = $(`#data-rece-${id}`).val();
            $.ajax({
                url: `/data-store-resultado`,
                type: 'POST',

                data: {
                    id_animal: id,
                    data_recebimento: data1
                },
                success: function(data) {
                    console.log(data);
                }
            });

        });
        $(document).on('blur', '.data-2', function() {
            var id = $(this).data('id');
            var data2 = $(`#data-coleta-${id}`).val();
            $.ajax({
                url: `/data-store-resultado`,
                type: 'POST',

                data: {
                    id_animal: id,
                    data_coleta: data2
                },
                success: function(data) {
                    console.log(data);
                }
            });

        });
        $(document).on('blur', '.data-3', function() {
            var id = $(this).data('id');
            var data3 = $(`#data-chamado-${id}`).val();
            $.ajax({
                url: `/data-store-resultado`,
                type: 'POST',

                data: {
                    id_animal: id,
                    data_laboratorio: data3
                },
                success: function(data) {
                    console.log(data);
                }
            });
        });
        $(document).on('click', '#criar-ordem', function() {
            var order = $(this).data('order');
            $.ajax({
                url: `{{ route('ordem.servico.store') }}`,
                type: 'POST',
                data: {

                    order: order
                },
                beforeSend: function() {
                    Swal.fire({
                        title: 'Aguarde!',
                        text: 'Estamos gerando a ordem de serviço.',
                        icon: 'info',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false,
                        onOpen: () => {
                            Swal.showLoading()
                        }
                    })
                },

                success: function(data) {
                    Swal.fire(
                        'Sucesso!',
                        'Ordem de serviço gerada com sucesso.',
                        'success'
                    )
                    location.reload();

                },
                error: function(er) {
                    error = er.responseJSON.error;
                    console.log(er);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: error,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    });
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
