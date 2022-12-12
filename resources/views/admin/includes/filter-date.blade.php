<div class="p-4">
    @forelse ($animals as $item)
        @if ($item->payment_status == 0)
            @php
                $status = 'Aguardando pagamento';
            @endphp
        @elseif($item->payment_status == 1)
            @php
                $status = 'Pagamento recebido';
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
                    <div class="col-1">
                        <p>Ações</p>
                    </div>
                

                </div>
                <div class="row">
                    <div class="col-3">
                        <p>{{ $item->animal }}</p>
                    </div>
                    <div class="col-4">
                        <p>{{ $item->orderRequest->creator }}</p>
                    </div>
                    <div class="col-2">
                        <p>{{ $status }}</p>
                    </div>
                    <div class="col-3 ">
                        <div>
                            <a href="#"><button class="btn btn-success">Ver Produto</button></a>
                        </div>
                    </div>
                    <div class="col-6">
                        <p>ID da transação: {{ $item->payment_id ?? 'Pago pelo sistema' }}</p>
                    </div>
                    <div class="col-6">
                        <p>ID do pedido: {{ $item->order_request_id  }}</p>
                    </div>

                </div>
            </div>
        </div>
    @empty
        <h4>Sem itens</h4>
    @endforelse
</div>
