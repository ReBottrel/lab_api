@extends('layouts.admin')

@section('content')
    <div class="container my-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row justify-content-between align-items-center">
                    <div class="col-md-6">
                        <h4 class="card-title text-primary">Detalhe do Pedido #{{ $order->id }}</h4>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="{{ route('admin.order-create-animal', $order->id) }}" class="btn btn-secondary">
                            Adicionar Produto
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @if ($lote)
            <div class="alert alert-success mt-3" role="alert">
                Este pedido já possui uma ordem de serviço.
            </div>
        @endif
    </div>

    <div class="container">
        <div class="card shadow">
            <div class="card-header bg-light">
                <h5 class="mb-0 text-primary">Informações do Pedido</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Criador:</strong> {{ $order->creator }} - {{ $order->creator_number }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Técnico:</strong> {{ $order->technical_manager ?? 'Não encontrado' }}</p>
                    </div>
                    <div class="col-md-6 mt-3">
                        <p><strong>Usuário:</strong> {{ $user->name ?? 'Não encontrado' }}
                            <br><strong>Email:</strong> {{ $user->email ?? 'Não encontrado' }}
                        </p>
                    </div>
                    <div class="col-md-6 mt-3">
                        <p><strong>Parceiro:</strong> {{ $order->parceiro ?? 'Sem parceiro' }}</p>
                        <form action="{{ route('order.parceiro.update') }}" method="post">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            <div class="input-group">
                                <select class="form-select" name="parceiro">
                                    <option selected>Selecione o parceiro</option>
                                    @foreach ($parceiros as $parceiro)
                                        <option value="{{ $parceiro->nome }}"
                                            @if ($order->parceiro == $parceiro->nome) selected @endif>
                                            {{ $parceiro->nome }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary">Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Animais -->
        <div class="mt-4">
            <h5 class="text-primary">Animais do Pedido</h5>
            @foreach ($animals as $animal)
                @php
                    $datas = App\Models\DataColeta::where('id_animal', $animal->id)->first();
                    $dataColeta = isset($datas->data_coleta) ? parseDate($datas->data_coleta) : '';
                    $dataLaboratorio = isset($datas->data_laboratorio) ? parseDate($datas->data_laboratorio) : '';
                    $dataRecebimento = isset($datas->data_recebimento) ? parseDate($datas->data_recebimento) : '';
                    $horaRecebimento = isset($datas->hora_coleta) ? $datas->hora_coleta : '';
                    $status = 'Status desconhecido';
                    if ($animal) {
                        switch ($animal->status) {
                            case 1:
                                $status = 'Aguardando amostra';
                                break;
                            case 2:
                                $status = 'Amostra recebida';
                                break;
                            case 3:
                                $status = 'Amostra em análise/execução';
                                break;
                            case 4:
                                $status = 'Análise concluída';
                                break;
                            case 5:
                                $status = 'Resultado disponível';
                                break;
                            case 6:
                                $status = 'Análise reprovada';
                                break;
                            case 7:
                                $status = 'Análise Aprovada';
                                break;
                            case 8:
                                $status = 'Recoleta solicitada';
                                break;
                            case 9:
                                $status = 'Amostra paga';
                                break;
                            case 10:
                                $status = 'Pedido Concluído';
                                break;
                            case 11:
                                $status = 'Aguardando Pagamento';
                                break;
                            case 12:
                                $status = 'Morto';
                                break;
                        }
                    }
                @endphp

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="text-secondary">Animal ID: {{ $animal->id }}</h6>
                        <ul class="list-group mb-3">
                            <li class="list-group-item"><strong>Nome do Animal:</strong>
                                {{ $animal->animal_name ?? 'Não informado' }}</li>
                            <li class="list-group-item"><strong>CODLAB:</strong>
                                {{ $animal->codlab ?? 'Não informado' }}</li>
                            <li class="list-group-item"><strong>Espécie:</strong>
                                {{ $animal->especies ?? 'Não informado' }}</li>
                            <li class="list-group-item"><strong>Raça:</strong> {{ $animal->breed ?? 'Não informado' }}</li>
                            <li class="list-group-item"><strong>Registro do Pai:</strong>
                                {{ $animal->registro_pai ?? 'Não informado' }}</li>
                            <li class="list-group-item"><strong>Nome do Pai:</strong> {{ $animal->pai ?? 'Não informado' }}
                            </li>
                            <li class="list-group-item"><strong>Registro da Mãe:</strong>
                                {{ $animal->registro_mae ?? 'Não informado' }}</li>
                            <li class="list-group-item"><strong>Nome da Mãe:</strong> {{ $animal->mae ?? 'Não informado' }}
                            </li>
                            <li class="list-group-item"><strong>Data de Nascimento:</strong>
                                {{ $animal->birth_date ? date('d/m/Y', strtotime($animal->birth_date)) : 'Não informado' }}
                            </li>
                            <li class="list-group-item"><strong>Obs:</strong>
                                {{ $animal->description ?? 'Não informado' }}</li>
                            </li>
                            <li class="list-group-item">
                                <strong>Status:</strong>
                                <span
                                    class="badge 
                                    @if ($status == 'Análise Aprovada' || $status == 'Pedido Concluído' || $status == 'Amostra paga') bg-success 
                                    @elseif($status == 'Morto' || $status == 'Recoleta solicitada') bg-warning 
                                    @elseif($status == 'Análise reprovada') bg-danger 
                                    @else bg-primary @endif">
                                    {{ $status }}
                                </span>
                            </li>
                        </ul>

                        <!-- Inputs para as Datas -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="data-recebimento-{{ $animal->id }}" class="form-label">Data de
                                    Recebimento</label>
                                <input type="date" class="form-control data-1" id="data-rece-{{ $animal->id }}"
                                    value="{{ $dataRecebimento }}" data-id="{{ $animal->id }}"
                                    data-type="data_recebimento">

                            </div>
                            <div class="col-md-4">
                                <label for="data-coleta-{{ $animal->id }}" class="form-label">Data de Coleta</label>
                                <input type="date" class="form-control data-2" id="data-coleta-{{ $animal->id }}"
                                    value="{{ $dataColeta }}" data-id="{{ $animal->id }}" data-type="data_coleta">
                            </div>
                            <div class="col-md-4">
                                <label for="data-chamado-{{ $animal->id }}" class="form-label">Hora do Recebimento</label>
                                <input type="time" class="form-control data-3" id="data-chamado-{{ $animal->id }}"
                                    value="{{ $horaRecebimento }}" data-id="{{ $animal->id }}"
                                    data-type="hora_coleta">
                            </div>
                        </div>

                        <!-- Select para alterar o Status -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="status-select-{{ $animal->id }}" class="form-label">Alterar Status</label>
                                <select class="form-select status-select" id="status-select-{{ $animal->id }}"
                                    data-id="{{ $animal->id }}" data-order="{{ $order->id }}"
                                    data-od="{{ $order->id }}">
                                    @foreach ($stats as $key => $stat)
                                        <option value="{{ $key }}"
                                            @if ($animal->status == $key) selected @endif>
                                            {{ $stat }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Ações -->
                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn btn-danger w-100 excluir-animal" data-order="{{ $order->id }}"
                                    data-id="{{ $animal->id }}">
                                    Remover Animal do Pedido
                                </button>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('animais.show', $animal->id) }}" class="btn btn-primary w-100">Editar
                                    Animal</a>
                            </div>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    </div>
    <div class="container my-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row text-center">
                    <!-- Botão: Gerar Pagamento -->
                    @if (isset($animal) && $order->status == 4)
                        <div class="col-md-12 mb-3">
                            <button class="btn btn-info fw-bold gerar" type="button" data-order="{{ $order->id }}">
                                GERAR PAGAMENTO
                            </button>
                        </div>
                    @endif

                    <!-- Botões: Pagamento Gerado e Ver Relatório -->
                    @if (isset($animal) && $order->status == 2)
                        <div class="col-md-6 mb-3">
                            <button class="btn btn-secondary fw-bold w-100" type="button" disabled>
                                PAGAMENTO GERADO
                            </button>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('order.request.detail', $order->id) }}"
                                class="btn btn-success fw-bold w-100">
                                VER RELATÓRIO DE PEDIDO
                            </a>
                        </div>
                    @endif

                    <!-- Botão: Gerar Ordem de Serviço -->
                    @if (isset($animal) && $order->status == 2)
                        <div class="col-md-12 text-center">
                            <button class="btn btn-primary fw-bold gerar-ordem" id="criar-ordem"
                                data-order="{{ $order->id }}">
                                GERAR ORDEM DE SERVIÇO
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalOrdem" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Gerar Ordem</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <textarea class="form-control" rows="3" id="observacao" placeholder="Insira uma observação"></textarea>
                    <input type="hidden" id="order-id" value="{{ $order->id }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" id="criar-ordem" class="btn btn-primary">Salvar e Gerar</button>
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

            // $(document).on('click', '#modalAnimal', function() {
            //     var id = $(this).data('order');
            //     $.ajax({
            //         url: `{{ route('get.dados.animal') }}`,
            //         type: 'GET',
            //         data: {
            //             id: id
            //         },
            //         success: function(data) {
            //             console.log(data);
            //             $('#order').val(data.id);
            //         }
            //     })
            // });

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

        });
        $(document).on('click', '.excluir-animal', function() {
            var id = $(this).data('id');
            var order = $(this).data('order');
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
                        url: `/animal-remove-order/`,
                        type: 'POST',
                        data: {
                            id: id,
                            order: order
                        },
                        success: function(data) {
                            console.log(data);
                            Swal.fire(
                                'Deletado!',
                                'Animal removido com sucesso.',
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
        $(document).on('change', '.status-select', function() {
            var id = $(this).data('id');
            var od = $(this).data('od');
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
                    url: `/amostra/${id}`,
                    type: 'POST',
                    data: {
                        value: $(this).val(),
                        order: order,
                        od: od,
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
