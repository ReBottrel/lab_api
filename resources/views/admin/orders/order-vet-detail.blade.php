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
                        @foreach ($pedidos as $pedido)
                            @php
                                $animal = App\Models\Animal::where('id', $pedido->id_animal)->first();
                                $datas = App\Models\SorologiaDate::where('animal_id', $animal->id)->first();
                                $exames = App\Models\ExamToAnimal::where('animal_id', $animal->id)->get();
                            @endphp
                            @if ($pedido)
                                @if ($pedido->status == 1)
                                    @php
                                        $status = 'Aguardando amostra';
                                    @endphp
                                @elseif($pedido->status == 2)
                                    @php
                                        $status = 'Amostra recebida';
                                    @endphp
                                @elseif($pedido->status == 3)
                                    @php
                                        $status = 'Em análise';
                                    @endphp
                                @elseif($pedido->status == 4)
                                    @php
                                        $status = 'Análise concluída';
                                    @endphp
                                @elseif($pedido->status == 5)
                                    @php
                                        $status = 'Resultado disponível';
                                    @endphp
                                @elseif($pedido->status == 6)
                                    @php
                                        $status = 'Análise reprovada';
                                    @endphp
                                @elseif($pedido->status == 7)
                                    @php
                                        $status = 'Análise Aprovada';
                                    @endphp
                                @elseif($pedido->status == 8)
                                    @php
                                        $status = 'Recoleta solicitada';
                                    @endphp
                                @elseif($pedido->status == 9)
                                    @php
                                        $status = 'Amostra paga';
                                    @endphp
                                @elseif($pedido->status == 11)
                                    @php
                                        $status = 'Aguardando Pagamento';
                                    @endphp
                                @endif
                            @endif
                            <ul class="list-group m-3">
                                <li class="list-group-item"><span>ID: {{ $animal->register_number_brand }}</span></li>
                                <li class="list-group-item"><span>PRODUTO: {{ $animal->animal_name }}</span></li>
                                @foreach ($exames as $exam)
                                    @php
                                        $exame = App\Models\Exam::where('id', $exam->exam_id)->first();
                                    @endphp
                                    <li class="list-group-item"><span>EXAME: {{ $exame->title }}</span></li>
                                @endforeach


                                {{-- <li class="list-group-item"><span>PAI: {{ $animal->pai }}</span></li>
                            <li class="list-group-item"><span>REGISTRO DO PAI: {{ $animal->registro_pai }}</span>
                            </li>
                            <li class="list-group-item"><span>MÃE: {{ $animal->mae }}</span></li>
                            <li class="list-group-item"><span>REGISTRO DA MÃE: {{ $animal->registro_mae }}</span>
                            </li> --}}
                                <li class="list-group-item"><span>Obs: {{ $animal->description ?? '' }}</span></li>

                                <li
                                    class="list-group-item text-uppercase @if ($status == 'Análise Aprovada') bg-success @elseif($status == 'Amostra paga') bg-success @elseif($status == 'Análise reprovada') bg-danger @elseif($status == 'Recoleta solicitada') bg-warning @else bg-primary @endif  text-white">
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
                                                id="data-rece-{{ $animal->id }}" data-pedido="{{ $pedido->id }}"
                                                value="{{ $datas->data_recebimento ?? '' }}" placeholder="">
                                        </div>
                                        <div class="col-4">
                                            <label for="exampleFormControlInput1" class="form-label">Data do
                                                resultado</label>
                                            <input type="text" class="form-control datas data-2"
                                                id="data-resultado-{{ $animal->id }}" data-pedido="{{ $pedido->id }}" data-type="data_resultado"
                                                data-id="{{ $animal->id }}" value="{{ $datas->data_resultado ?? '' }}"
                                                placeholder="">
                                        </div>
                                        <div class="col-4">
                                            <label for="exampleFormControlInput1" class="form-label">Numero do lacre
                                                AIE</label>
                                            <input type="text" class="form-control  data-3"
                                                data-id="{{ $animal->id }}" data-pedido="{{ $pedido->id }}" data-type="numero_lacre_aie"
                                                id="numero-aie-{{ $animal->id }}"
                                                value="{{ $datas->numero_aie ?? '' }}" placeholder="">
                                        </div>
                                        <div class="col-4">
                                            <label for="exampleFormControlInput1" class="form-label">Numero do lacre
                                                MORMO</label>
                                            <input type="text" class="form-control  data-4"
                                                data-id="{{ $animal->id }}" data-pedido="{{ $pedido->id }}" data-type="numero_lacre_mormo"
                                                id="numero-mormo-{{ $animal->id }}"
                                                value="{{ $datas->numero_mormo ?? '' }}" placeholder="">
                                        </div>

                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        {{-- <div class="col-md-4">
                                        <label for="exampleFormControlInput1" class="form-label">Tipo de
                                            coleta</label>
                                        <select class="form-select sample-select" data-order="{{ $order->id }}"
                                            data-id="{{ $animal->id ?? '' }}" aria-label="Default select example">
                                            @if ($animal)
                                                @foreach ($samples as $sample)
                                                    <option value="{{ $sample->id }}"
                                                        @if ($datas) @if ($datas->tipo == $sample->id) selected @endif
                                                        @endif>
                                                        {{ $sample->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div> --}}
                                        <div class="col-md-4 mt-4">
                                            <button class="btn btn-danger excluir-animal"
                                                data-id="{{ $animal->id }}">EXCLUIR
                                                ANIMAL</button>
                                        </div>
                                        <div class="col-md-4 mt-4">
                                            <a href="{{ route('animais.show', $animal->id) }}"> <button
                                                    class="btn btn-primary">EDITAR ANIMAL</button></a>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <label for="exampleFormControlInput1" class="form-label">Status do pedido</label>
                                    <select class="form-select status-select" data-order="{{ $order->id }}"
                                        data-id="{{ $pedido->id ?? '' }}" aria-label="Default select example">
                                        @if ($pedido)
                                            @foreach ($stats as $key => $stat)
                                                <option value="{{ $key }}"
                                                    @if ($pedido->status == $key) selected @endif>
                                                    {{ $stat }}
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
                <h5 class="mb-0">VETERINÁRIO: {{ $order->technical_manager ?? 'Não encontrado' }}</h5>
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
        $(document).ready(function() {
            $('.datas').mask('00/00/0000');
            $('.cpf-tech').mask('000.000.000-00', {
                reverse: true
            });

        });
        $(document).on('click', '.excluir-animal', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Você tem certeza?',
                text: "Esse processo pode ser irreversível!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, deletar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/animal-delete/`,
                        type: 'POST',
                        data: {
                            id: id
                        },
                        success: function(data) {
                            console.log(data);
                            Swal.fire(
                                'Deletado!',
                                'Animal deletado com sucesso.',
                                'success'
                            )
                            location.reload();
                        }
                    });

                }
            })

        });
        $(document).on('change', '.sample-select', function() {
            var id = $(this).data('id');
            var order = $(this).data('order');
            $.ajax({
                url: `/sample-update`,
                type: 'POST',
                data: {
                    tipo: $(this).val(),
                    id_animal: id,
                    order: order
                },
                success: function(data) {
                    console.log(data);
                }
            });
        });
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

        $(document).on('blur', '.data-1', function() {
            var id = $(this).data('id');
            var pedido = $(this).data('pedido');
            var data1 = $(`#data-rece-${id}`).val();
            $.ajax({
                url: `/data-store-sorologia`,
                type: 'POST',

                data: {
                    pedidos: pedido,
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
            var pedido = $(this).data('pedido');
            var data2 = $(`#data-resultado-${id}`).val();
            $.ajax({
                url: `/data-store-sorologia`,
                type: 'POST',

                data: {
                    pedidos: pedido,
                    id_animal: id,
                    data_resultado: data2
                },
                success: function(data) {
                    console.log(data);
                }
            });

        });
        $(document).on('blur', '.data-3', function() {
            var id = $(this).data('id');
            var pedido = $(this).data('pedido');
            var data3 = $(`#numero-aie-${id}`).val();
            $.ajax({
                url: `/data-store-sorologia`,
                type: 'POST',

                data: {
                    pedidos: pedido,
                    id_animal: id,
                    numero_aie: data3
                },
                success: function(data) {
                    console.log(data);
                }
            });
        });
        $(document).on('blur', '.data-4', function() {
            var id = $(this).data('id');
            var pedido = $(this).data('pedido');
            var data4 = $(`#numero-mormo-${id}`).val();
            $.ajax({
                url: `/data-store-sorologia`,
                type: 'POST',

                data: {
                    pedidos: pedido,
                    id_animal: id,
                    numero_mormo: data4
                },
                success: function(data) {
                    console.log(data);
                }
            });
        });
        $(document).on('change', '.status-select', function() {
            var id = $(this).data('id');
            var data1 = $(`#data-rece-${id}`).val();
            var data2 = $(`#data-coleta-${id}`).val();
            var data3 = $(`#data-chamado-${id}`).val();
            console.log(data1);
            var isValid = true;

            var order;
            if ($(this).val() == 6 || $(this).val() == 7) {
                order = $(this).data('order');
            }
            if (isValid == true) {
                $.ajax({
                    url: `/app-order-status/${id}`,
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

                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Verifique se o criador ou técnico está associado ou telefona está correto!',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        });
                    }
                });
            }
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
