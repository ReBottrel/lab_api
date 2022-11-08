@extends('layouts.loja')

@section('content')
    <div class="container">
        <h1 class="text-primary">Minha Conta</h1>
        <div class="row gx-3">
            @component('layouts.partials.user-menu')
            @endcomponent

            <div class="col-8">
                <form class="row mb-3">
                    <div class="col-12 col-md-6 mb-3">
                        <div class="form-floating">
                            <select class="form-select">
                                <option value="30">Últimos 30 dias</option>
                                <option value="60">Últimos 60 dias</option>
                                <option value="90">Últimos 90 dias</option>
                                <option value="180">Últimos 180 dias</option>
                                <option value="360" selected>Últimos 360 dias</option>
                                <option value="9999">Todo o período</option>
                            </select>
                            <label>Período</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating">
                            <select class="form-select">
                                <option value="1" selected>Mais novos primeiro</option>
                                <option value="2">Mais antigos primeiro</option>
                            </select>
                            <label>Ordenação</label>
                        </div>
                    </div>
                </form>
                @php
                    $total = 0;
                @endphp
                @foreach ($orders as $order)
                    <div class="accordion my-4" id="divPedidos">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#pedido000010">
                                    <b>Pedido {{ $order->id }}</b>
                                    <span class="mx-1">(realizado em {{ $order->created_at }})</span>
                                </button>
                            </h2>
                            <div id="pedido000010" class="accordion-collapse collapse" data-bs-parent="#divPedidos">
                                <div class="accordion-body">
                                    <div class="row px-2">
                                        <div class="col-md-4 px-2 font-weight-bold">
                                            <p class="font-weight-bold">Produto</p>
                                        </div>
                                        <div class="col-md-2">
                                            <p>R$ Unit. </p>
                                        </div>
                                        <div class="col-md-2">
                                            <p>Qtde</p>
                                        </div>
                                        <div class="col-md-2">
                                            <p>Subtotal</p>
                                        </div>
                                        <div class="col-md-2">
                                            <p>Status</p>
                                        </div>
                                    </div>
                                    @foreach ($order->orderRequestPayment as $item)
                                        <form action="{{ route('user.payment') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="orderId" value="{{ $order->id }}">
                                            <input type="hidden" name="itemId[]" value="{{ $item->id }}">
                                            <div class="accordion my-3" id="accordionExample-{{ $item->id }}">
                                                <div class="accordion-item">
                                                    <div class="accordion-header row px-2" id="headingOne"
                                                        class="accordion-button" type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseOne-{{ $item->id }}"
                                                        aria-expanded="true" aria-controls="collapseOne">
                                                        <div class="col-md-4">
                                                            <p>{{ $item->animal }}</p>
                                                        </div>
                                                        <div class="col-md-2">
                                                            @foreach ($prices as $key => $p)
                                                                <p class="prices preco @if ($key != 0) d-none @endif prices-{{ $key }}"
                                                                    data-price="{{ $p }}">

                                                                    {{ 'R$ ' . number_format($p, 2, ',', '.') }} </p>
                                                            @endforeach
                                                            {{-- <p>{{ 'R$ ' . number_format($item->value, 2, ',', '.') }} </p> --}}
                                                        </div>
                                                        <div class="col-md-2">
                                                            <p>1</p>
                                                        </div>
                                                        <div class="col-md-2">
                                                            @foreach ($prices as $key => $p)
                                                                <p
                                                                    class="prices @if ($key != 0) d-none @endif prices-{{ $key }}">
                                                                    {{ 'R$ ' . number_format($p, 2, ',', '.') }} </p>
                                                            @endforeach
                                                        </div>
                                                        <div class="col-md-2">
                                                            <p>
                                                                @if ($item->payment_status == 0)
                                                                    Aguardando
                                                                    pagamento
                                                                @elseif($item->payment_status == 1)
                                                                    Pagamento confirmado
                                                                @elseif($item->payment_status == 2)
                                                                    Pagamento recusado
                                                                @endif
                                                            </p>

                                                        </div>
                                                    </div>

                                                    <div id="collapseOne-{{ $item->id }}"
                                                        class="accordion-collapse collapse show"
                                                        aria-labelledby="headingOne"
                                                        data-bs-parent="#accordionExample-{{ $item->id }}">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <label for="exampleFormControlInput1"
                                                                            class="form-label">Tempo de
                                                                            entrega</label>
                                                                        <select class="form-select sel-price" name="days[]"
                                                                            @if ($item->payment_status == 1) disabled @endif
                                                                            aria-label="Default select example">
                                                                            <option value="0-{{ $item->id }}" selected>20 Dias (Padrão)
                                                                            <option value="1-{{ $item->id }}">24 Horas</option>
                                                                            <option value="2-{{ $item->id }}">2 Dias</option>
                                                                            <option value="3-{{ $item->id }}">5 Dias</option>
                                                                            <option value="4-{{ $item->id }}">10 Dias</option>
                                                                            </option>
                                                                        </select>
                                                                    </div>

                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input paynow"
                                                                            type="checkbox" value="{{ $item->id }}"
                                                                            name="paynow[]" id="flexCheckChecked"
                                                                            @if ($item->payment_status == 0) checked @else disabled="disabled" @endif>
                                                                        <label class="form-check-label"
                                                                            for="flexCheckChecked">
                                                                            Pagar Agora
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @php
                                                
                                                if ($item->payment_status == 0) {
                                                    $total += $item->value;
                                                } else {
                                                    $total += 0;
                                                }
                                                
                                            @endphp
                                    @endforeach
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="hidden" class="price" name="totalprice"
                                                value="{{ $order->orderRequestPayment->count() * $prices[0] }}">
                                            <h4>TOTAL: <span class="total-price">
                                                    {{ 'R$ ' . number_format($total, 2, ',', '.') }}</span>
                                            </h4>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <button type="submit" @if ($total == 0) disabled @else @endif
                                                class="btn btn-primary">Formas
                                                de Pagamento</button>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).on('change', '.sel-price', function() {
            $(this).closest('.accordion').find('.prices').addClass('d-none');
            $(this).closest('.accordion').find(`.prices.prices-${$(this).val().split('-')[0]}`).removeClass('d-none');
            var totalPrice = 0;
            $(this).closest('#divPedidos').find(`.preco`).each(function() {
                if ($(this).is('.d-none') == false && $(this).closest('.accordion').find('.paynow').is(
                        ':checked')) {
                    totalPrice += parseFloat($(this).data('price'));
                    // console.log($(this).closest('.accordion').find('.paynow'));
                }
            });
            $('.total-price').text(totalPrice.toFixed(2).replace('.', ','));
            $('.price').val(totalPrice.toFixed(2).replace('.', ','));

        });
        $(document).on('click', '.paynow', function() {
            var totalPrice = 0;
            $(this).closest('#divPedidos').find(`.preco`).each(function() {
                if ($(this).is('.d-none') == false && $(this).closest('.accordion').find('.paynow').is(
                        ':checked')) {
                    totalPrice += parseFloat($(this).data('price'));
                    // console.log($(this).closest('.accordion').find('.paynow'));
                }
            });
            $('.total-price').text(totalPrice.toFixed(2).replace('.', ','));
            $('.price').val(totalPrice.toFixed(2).replace('.', ','));
        });
    </script>
@endsection
