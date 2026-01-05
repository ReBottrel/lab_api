@extends('layouts.loja')

@section('content')
    <style>
        .order-page-wrapper {
            background: #f8fafc;
            min-height: 100vh;
            padding: 2rem 0;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .order-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
            margin-bottom: 1.25rem;
            overflow: hidden;
        }

        .order-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .order-card-body {
            padding: 1.75rem;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.25rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .info-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .info-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin-bottom: 0.375rem;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .info-label i {
            color: #6366f1;
        }

        .info-value {
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
        }

        .product-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.5rem 1rem;
            border-radius: 50rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: #fff7ed;
            color: #c2410c;
            border: 1px solid #fed7aa;
        }

        .status-success {
            background: #f0fdf4;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        .status-error {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .price-display {
            font-family: 'Consolas', 'Monaco', monospace;
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
        }

        .custom-select {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            color: #475569;
            width: 100%;
            transition: all 0.2s;
        }

        .custom-select:hover {
            border-color: #cbd5e1;
        }

        .custom-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            outline: none;
        }

        .summary-card {
            background: #fff;
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 2rem;
        }

        .summary-title {
            font-size: 0.875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .total-amount {
            font-family: 'Consolas', 'Monaco', monospace;
            font-size: 2.25rem;
            font-weight: 800;
            color: #6366f1;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .checkout-btn {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border: none;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            font-size: 0.95rem;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.2s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .checkout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(99, 102, 241, 0.3);
        }

        .payment-btn {
            border-radius: 10px;
            padding: 0.625rem 1.25rem;
            font-weight: 600;
            font-size: 0.875rem;
        }

        @media (max-width: 991px) {
            .summary-card {
                position: relative;
                top: 0;
                margin-top: 2rem;
            }
        }
    </style>

    <div class="order-page-wrapper">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">Minha Conta</h1>
                <p class="text-muted">Gerencie seus pedidos e pagamentos</p>
            </div>

            <div class="row gx-4">
                @component('layouts.partials.user-menu')
                @endcomponent

                <div class="col-md-6 col-12">
                    <form action="" method="post" id="orderForm">
                        @csrf
                        <input type="hidden" name="orderId" id="orderId" value="{{ $order->id }}">

                        @php
                            $hasItems = false;
                        @endphp

                        @foreach ($order->orderRequestPayment as $item)
                            @php
                                $hasItems = true;
                                $animal = App\Models\Animal::where('id', $item->animal_id)
                                    ->orWhere('register_number_brand', $item->animal_id)
                                    ->first();
                                
                                $requests = 1;
                                if (in_array($animal->especies, ['ASININA', 'ASININO', 'MUARES', 'MUAR', 'EQUINO_PEGA', 'PEGA_EQUINO'])) {
                                    $requests = 2;
                                }
                                
                                $exames = App\Models\Exam::where('category', 'dna')
                                    ->where('requests', $requests)
                                    ->where('status', 1)
                                    ->get();

                                $statusClass = 'status-pending';
                                $statusText = 'Aguardando Pagamento';
                                $statusIcon = 'bi-clock-history';
                                
                                if ($item->payment_status == 1) {
                                    $statusClass = 'status-success';
                                    $statusText = 'Pagamento Confirmado';
                                    $statusIcon = 'bi-check-circle-fill';
                                } elseif ($item->payment_status == 2) {
                                    $statusClass = 'status-error';
                                    $statusText = 'Pagamento Recusado';
                                    $statusIcon = 'bi-x-circle-fill';
                                }
                            @endphp

                            @if ($item->payment_status != 2)
                                <input type="hidden" name="itemId[]" value="{{ $item->id }}">
                                
                                <div class="order-card order-itens">
                                    <div class="order-card-body">
                                        <!-- Product Info -->
                                        <div class="info-row">
                                            <div class="flex-grow-1">
                                                <div class="info-label">
                                                    <i class="bi bi-tag-fill"></i>
                                                    <span>Produto</span>
                                                </div>
                                                <div class="product-name">{{ $item->animal }}</div>
                                                <div class="text-muted small mt-1">
                                                    <i class="bi bi-clipboard-pulse me-1"></i>{{ $item->category }}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Status & Price Row -->
                                        <div class="info-row">
                                            <div>
                                                <div class="info-label">
                                                    <i class="bi bi-info-circle-fill"></i>
                                                    <span>Status</span>
                                                </div>
                                                <span class="status-badge {{ $statusClass }}">
                                                    <i class="bi {{ $statusIcon }}"></i>
                                                    {{ $statusText }}
                                                </span>
                                            </div>
                                            <div class="text-end">
                                                <div class="info-label">
                                                    <i class="bi bi-currency-dollar"></i>
                                                    <span>Valor</span>
                                                </div>
                                                <div class="price-display valor-{{ $item->id }}">
                                                    {{ 'R$ ' . number_format($item->value, 2, ',', '.') }}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Delivery & Payment -->
                                        <div class="row g-3">
                                            @if ($order->origin == 'sistema' || $order->origin == 'API' || $order->origin == 'email')
                                                <div class="col-md-6">
                                                    <div class="info-label">
                                                        <i class="bi bi-calendar-check"></i>
                                                        <span>Tempo de Entrega</span>
                                                    </div>
                                                    <select class="custom-select sel-price" name="days[]"
                                                        @if ($item->payment_status == 1) disabled @endif>
                                                        @foreach ($exames as $key => $exame)
                                                            @if ($exame->status == 1)
                                                                <option data-exame="{{ $exame->id }}"
                                                                    value="{{ $key }}-{{ $item->id }}-{{ $exame->id }}"
                                                                    data-value="{{ $exame->value }}"
                                                                    data-order="{{ $item->id }}" 
                                                                    data-id="{{ $order->id }}"
                                                                    @if ($item->exam_id == $exame->id) selected @endif>
                                                                    {{ $exame->title }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif

                                            @if ($payment)
                                                <div class="col-md-6">
                                                    @if ($payment->payment_type == 'boleto')
                                                        <div class="info-label">
                                                            <i class="bi bi-upc-scan"></i>
                                                            <span>Boleto</span>
                                                        </div>
                                                        <a href="{{ route('user.success', $order->id) }}" 
                                                           class="btn btn-outline-primary payment-btn w-100">
                                                            <i class="bi bi-file-earmark-text me-2"></i>Ver Boleto
                                                        </a>
                                                    @elseif ($payment->payment_type == 'pix')
                                                        <div class="info-label">
                                                            <i class="bi bi-qr-code"></i>
                                                            <span>PIX</span>
                                                        </div>
                                                        <a href="{{ route('user.success', $order->id) }}" 
                                                           class="btn btn-outline-success payment-btn w-100">
                                                            <i class="bi bi-qr-code-scan me-2"></i>Ver QR Code
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Hidden Fields -->
                                        <div class="d-none">
                                            <select class="form-select" name="selectedItems[]"
                                                @if ($item->payment_status == 1) disabled @endif>
                                                <option value="{{ $item->id }}-1">Pagar Agora</option>
                                                <option value="{{ $item->id }}-0">Pagar Depois</option>
                                            </select>
                                            <input class="form-check-input paynow" type="checkbox" 
                                                   value="{{ $item->id }}" name="paynow[]"
                                                   @if ($item->payment_status == 0) checked @else disabled @endif>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </form>
                </div>

                <!-- Summary Sidebar -->
                @if($hasItems ?? false)
                    <div class="col-md-3 col-12">
                        <div class="summary-card">
                            <h6 class="summary-title">
                                <i class="bi bi-receipt me-2"></i>
                                Resumo do Pedido
                            </h6>
                            
                            @php
                                $total = $order->orderRequestPayment
                                    ->where('payment_status', 0)
                                    ->sum('value');
                            @endphp
                            
                            <div class="total-amount total-price">
                                {{ 'R$ ' . number_format($total, 2, ',', '.') }}
                            </div>
                            
                            <input type="hidden" class="price-total" name="totalprice" value="{{ $total }}" form="orderForm">
                            
                            <button type="button" id="submitPay" class="checkout-btn">
                                <i class="bi bi-credit-card-2-front me-2"></i>
                                Finalizar Pagamento
                            </button>

                            <div class="mt-3 text-center">
                                <small class="text-muted">
                                    <i class="bi bi-shield-check me-1"></i>
                                    Pagamento 100% seguro
                                </small>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('select[name^="selectedItems"]').change(function() {
                updateTotal();
            });

            function updateTotal() {
                var totalPrice = 0;
                $('select[name^="selectedItems"]').each(function() {
                    var optionValue = $(this).val();
                    var optionParts = optionValue.split('-');
                    var itemId = optionParts[0];
                    var selectedOption = optionParts[1];

                    if (selectedOption === '1') {
                        var itemValueText = $(`.valor-${itemId}`).text();
                        var itemValue = parseFloat(itemValueText.replace('R$ ', '').replace(/\./g, '').replace(',', '.'));
                        totalPrice += itemValue;
                    }
                });

                $(`.total-price`).text(`R$ ${totalPrice.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`);
                $(`.price-total`).val(totalPrice.toFixed(2).replace('.', ','));
            }
        });
        
        $(document).on('change', '.sel-price', function() {
            var orderId = $('#orderId').val();
            var totalPrice = 0;
            
            $('.sel-price').each(function() {
                if (!$(this).attr('disabled')) {
                    totalPrice += parseFloat($(this).find(':selected').data('value'));
                }
            });

            var valor = parseFloat($(this).find(':selected').data('value'));
            var order = $(this).find(':selected').data('order');
            var exame = $(this).find(':selected').data('exame');

            $(`.valor-${order}`).text(`R$ ${valor.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`);
            $(`.total-price`).text(`R$ ${totalPrice.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`);
            $(`.price-total`).val(totalPrice.toFixed(2).replace('.', ','));

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
                    console.log('Value updated', data);
                }
            });
        });

        $(document).on('click', '.paynow', function() {
            var totalPrice = 0;
            $('.sel-price').each(function() {
                if (!$(this).attr('disabled')) {
                    if ($(this).closest('.order-card').find('.paynow').prop('checked'))
                        totalPrice += parseFloat($(this).find(':selected').data('value'));
                }
            });
            $(`.total-price`).text(`R$ ${totalPrice.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`);
            $(`.price-total`).val(totalPrice.toFixed(2).replace('.', ','));
        });

        $(document).on('click', '#submitPay', function() {
            var totalStr = $('.price-total').val();
            var total = parseFloat(totalStr.replace(',', '.'));
            var orderId = $('#orderId').val();
            
            var values = $("select[name^='days']").map(function(idx, ele) {
                return $(ele).val();
            }).get();
            
            var paynow = $("select[name^='selectedItems']").map(function(idx, ele) {
                return $(ele).val();
            }).get();

            $.ajax({
                type: 'post',
                url: `{{ route('user.payment.process') }}`,
                data: {
                    total: total,
                    orderId: orderId,
                    days: values,
                    paynow: paynow,
                },
                success: function(data) {
                    console.log(data);
                    window.location.href = `/user-payment/${orderId}`;
                },
                error: function(err) {
                    console.error('Payment processing error', err);
                    alert('Erro ao processar pagamento. Tente novamente.');
                }
            });
        });
    </script>
@endsection
