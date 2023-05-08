<div class="p-4">
    @forelse ($pedidos as $pedido)
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
                $status = 'Pagamento confirmado';
            @endphp
        @elseif($pedido->status == 10)
            @php
                $status = 'Pedido concluído';
            @endphp
        @elseif($pedido->status == 11)
            @php
                $status = 'Aguardando pagamento';
            @endphp
        @endif
        <div class="filter-changed">
            <div class="ajust">
                <div class="row">
                    <div class="col-3">
                        <p>Produto</p>
                    </div>
                    <div class="col-4">
                        <p>Criador</p>
                    </div>
                    <div class="col-2">
                        <p>Status</p>
                    </div>
                    <div class="col-3">
                        <p>Ações</p>
                    </div>

                </div>
                <div class="row">
                    <div class="col-3">
                        <p>{{ $pedido->animal->animal_name }}</p>
                    </div>
                    <div class="col-4">
                        <p>{{ $pedido->order->creator ?? 'Sem criador' }}</p>
                    </div>
                    <div class="col-2">
                        <p>{{ $status }}</p>
                    </div>

                    <div class="col-3">
                        @if ($pedido->order)
                            <div>
                                <a
                                    href="@if ($pedido->order->origin == 'app') {{ route('orders.vet.detail', $pedido->id_pedido) }} @endif"><button
                                        class="btn btn-success">Ver Produto</button></a>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    @empty
        <h4>Sem itens</h4>
    @endforelse
</div>
