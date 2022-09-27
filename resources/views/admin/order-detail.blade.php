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
                        <h5 class="mb-0">CRIADOR: {{ $order->data_g['data_g']['criador'][1] }} -
                            {{ $order->data_g['data_g']['criador'][0] }}</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        @foreach ($order->data_g['data_table'] as $item)
                            @php
                                $animal = App\Models\Animal::where('register_number_brand', $item['id'])->first();
                                $status = 'Aguardando cadastro';
                            @endphp
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
                                <li class="list-group-item text-uppercase"><span>STATUS: {{ $status }}</span></li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-4">
                                            <input type="text" class="form-control" placeholder="Numero do chip">
                                        </div>
                                        <div class="col-4">
                                            <span>Insira o numero do chip do animal</span>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @php
        $status = '';
        if ($order->status == 0) {
            $status = 'Aguardando';
        }
        if ($order->status == 1) {
            $status = 'Aguardando amostra';
        }
        if ($order->status == 2) {
            $status = 'Em produção';
        }
        if ($order->status == 3) {
            $status = 'Finalizado';
        }
        if ($order->status == 4) {
            $status = 'Cancelado';
        }
    @endphp
    <div class="container" style="margin-top: 20px;">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">RESPONSAVEL TÉCNICO: {{ $order->technical_manager ?? 'Não encontrado' }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <ul class="list-group">
                            <li class="list-group-item"><span>Data: {{ $order->collection_date }}</span></li>
                            <li class="list-group-item"><span>Origem do pedido: {{ $order->origin }}</span></li>
                            <li class="list-group-item"><span>Status do pedido: {{ $status }}</span></li>
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
                    <div class="col text-center align-self-center"><button class="btn fw-bold link-light" type="button"
                            style="background: var(--bs-info);">PDF</button><button class="btn fw-bold link-light"
                            type="button" style="background: var(--bs-green);margin: 15px;">EXCEL</button></div>
                    <div class="col"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    {{-- <div class="modal fade" id="add-criador" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cadastrar Criador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
