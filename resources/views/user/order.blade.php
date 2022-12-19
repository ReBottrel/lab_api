@extends('layouts.loja')

@section('content')
    <div class="container">
        <h1 class="text-primary">Minha Conta</h1>
        <div class="row gx-3">
            @component('layouts.partials.user-menu')
            @endcomponent

            <div class="col-md-8 col-12">
                @foreach ($order->orderRequestPayment as $item)
                    @php
                        $animal = App\Models\Animal::where('id', $item->animal_id)
                            ->orWhere('register_number_brand', $item->animal_id)
                            ->first();
                        if ($animal->especies == 'EQUINA') {
                            $exames = App\Models\Exam::where('category', 'dna')
                                ->where('requests', 1)
                                ->get();
                        } elseif ($animal->especies == 'ASININO') {
                            $exames = App\Models\Exam::where('category', 'dna')
                                ->where('requests', 2)
                                ->get();
                        } elseif ($animal->especies == 'MUARES') {
                            $exames = App\Models\Exam::where('category', 'dna')
                                ->where('requests', 2)
                                ->get();
                        } elseif ($animal->especies == 'MUAR') {
                            $exames = App\Models\Exam::where('category', 'dna')
                                ->where('requests', 2)
                                ->get();
                        } elseif ($animal->especies == 'EQUINO_PEGA') {
                            $exames = App\Models\Exam::where('category', 'dna')
                                ->where('requests', 2)
                                ->get();
                        } else {
                            $exames = App\Models\Exam::where('category', 'dna')
                                ->where('requests', 1)
                                ->get();
                        }
                    @endphp
                    @if ($item->payment_status != 2)
                        <form action="{{ route('user.payment') }}" method="post">
                            @csrf
                            <input type="hidden" name="orderId" id="orderId" value="{{ $order->id }}">
                            <input type="hidden" name="itemId[]" value="{{ $item->id }}">
                            <div class="row order-itens">
                                <div class="col-md-6">
                                    <p>Produto: <span>{{ $item->animal }}</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p>Tipo de Exame: <span>{{ $item->category }}</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p>Status de Pagamento: <span>Aguardando Pagamento</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p>Valor: <span
                                            class="valor-{{ $item->id }}">{{ 'R$ ' . number_format($item->value, 2, ',', '.') }}</span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Tempo de
                                            entrega</label>

                                        <select class="form-select sel-price" name="days[]"
                                            @if ($item->payment_status == 1) disabled @endif
                                            aria-label="Default select example">
                                            @foreach ($exames as $key => $exame)
                                                <option data-exame="{{ $exame->id }}"
                                                    value="{{ $key }}-{{ $item->id }}-{{ $exame->id }}"
                                                    data-value="{{ $exame->value }}" data-order="{{ $item->id }}"
                                                    data-id="{{ $order->id }}"
                                                    @if ($item->exam_id == $exame->id) selected @else @endif>
                                                    {{ $exame->title }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input paynow" type="checkbox" value="{{ $item->id }}"
                                            name="paynow[]" id="flexCheckChecked"
                                            @if ($item->payment_status == 0) checked @else disabled="disabled" @endif>
                                        <label class="form-check-label" for="flexCheckChecked">
                                            Pagar Agora
                                        </label>
                                    </div>
                                </div>
                            </div>
                    @endif
                @endforeach
                @php
                    
                    $total = $order->orderRequestPayment
                        ->where('payment_status', 0)
                        ->map(function ($query) {
                            return $query->value;
                        })
                        ->sum();
                    
                @endphp
                <div class="row">
                    <div class="col-md-6">
                        <h4>Total do seu pedido: <span
                                class="total-price">{{ 'R$ ' . number_format($total, 2, ',', '.') }}</span></h4>
                        <input type="hidden" class="price-total" name="totalprice" value="{{ $total }}">
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">Formas
                            de Pagamento</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).on('change', '.sel-price', function() {
            var orderId = $('#orderId').val();
            var totalPrice = 0;
            $('.sel-price').each(function() {
                if (!$(this).attr('disabled')) {
                    totalPrice += parseFloat($(this).find(':selected').data('value'));
                    console.log($(this));
                }

            });
            var valor = parseFloat($(this).find(':selected').data('value'));
            var order = $(this).find(':selected').data('order');
            var exame = $(this).find(':selected').data('exame');

            $(`.valor-${order}`).text(`R$ ${valor.toFixed(2).replace('.', ',')}`);


            $(`.total-price`).text(`R$ ${totalPrice.toFixed(2).replace('.', ',')}`);
            $(`.price-total`).val(`${totalPrice.toFixed(2).replace('.', ',')}`);
            $.ajax({
                type: 'post',
                url: `/value-update/${orderId}`,
                data: {
                    productValue: valor,
                    product: order,
                    value: totalPrice,
                    exame: exame,
                },
                success: function(data) {
                    console.log(data)
                }
            });
        });
    </script>
@endsection
