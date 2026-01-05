@extends('layouts.loja')

@section('content')
    <style>
        .payment-wrapper {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            min-height: 100vh;
            padding: 3rem 0;
        }

        .payment-header {
            margin-bottom: 2.5rem;
        }

        .payment-title {
            font-size: 2.25rem;
            font-weight: 800;
            background: linear-gradient(135deg, #1e293b, #475569);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .section-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 
                0 1px 3px rgba(0, 0, 0, 0.03),
                0 4px 12px rgba(0, 0, 0, 0.04),
                0 0 0 1px rgba(255, 255, 255, 0.5) inset;
            padding: 2.5rem;
            margin-bottom: 1.75rem;
            transition: all 0.3s ease;
        }

        .section-card:hover {
            box-shadow: 
                0 4px 6px rgba(0, 0, 0, 0.05),
                0 10px 20px rgba(0, 0, 0, 0.06),
                0 0 0 1px rgba(255, 255, 255, 0.5) inset;
            transform: translateY(-2px);
        }

        .section-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1.75rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            display: flex;
            align-items: center;
            gap: 0.875rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f1f5f9;
        }

        .section-title i {
            color: #6366f1;
            font-size: 1.375rem;
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            padding: 0.625rem;
            border-radius: 12px;
        }

        .coupon-input-group {
            display: flex;
            gap: 1rem;
            align-items: stretch;
        }

        .coupon-input {
            flex: 1;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .coupon-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            outline: none;
            background: #fff;
        }

        .coupon-input::placeholder {
            color: #94a3b8;
        }

        .coupon-btn {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border: none;
            border-radius: 12px;
            padding: 1rem 2.5rem;
            font-weight: 700;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
            white-space: nowrap;
        }

        .coupon-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.35);
            background: linear-gradient(135deg, #5558e3, #7c3aed);
        }

        .order-info {
            background: linear-gradient(135deg, #fefce8, #fef3c7);
            border-radius: 16px;
            padding: 2rem;
            border: 2px solid #fde68a;
            position: relative;
            overflow: hidden;
        }

        .order-info::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
            animation: shimmer 3s linear infinite;
        }

        @keyframes shimmer {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .order-info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.875rem 0;
            border-bottom: 1px solid rgba(251, 191, 36, 0.3);
            position: relative;
            z-index: 1;
        }

        .order-info-item:last-child {
            border-bottom: none;
            font-weight: 700;
            font-size: 1.25rem;
            color: #92400e;
            margin-top: 0.5rem;
            padding-top: 1.25rem;
            border-top: 2px solid #fbbf24;
        }

        .order-info-label {
            color: #78350f;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .order-info-value {
            font-weight: 700;
            color: #92400e;
            font-size: 1.05rem;
        }

        .payment-tabs .nav-tabs {
            border: none;
            gap: 0.75rem;
            margin-bottom: 2.5rem;
            background: #f8fafc;
            padding: 0.5rem;
            border-radius: 16px;
        }

        .payment-tabs .nav-link {
            background: transparent;
            border: 2px solid transparent;
            border-radius: 12px;
            padding: 1.125rem 1.75rem;
            font-weight: 700;
            color: #64748b;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.625rem;
            flex: 1;
            justify-content: center;
        }

        .payment-tabs .nav-link:hover {
            background: #fff;
            color: #6366f1;
            border-color: #e0e7ff;
        }

        .payment-tabs .nav-link.active {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-color: transparent;
            color: white;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .form-label {
            font-size: 0.8125rem;
            font-weight: 700;
            color: #475569;
            margin-bottom: 0.625rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .form-control, .form-select {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .form-control:focus, .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            outline: none;
            background: #fff;
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        .payment-btn {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border: none;
            border-radius: 14px;
            padding: 1.25rem 3.5rem;
            font-size: 1.05rem;
            font-weight: 800;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            transition: all 0.3s ease;
            box-shadow: 
                0 4px 12px rgba(99, 102, 241, 0.3),
                0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .payment-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .payment-btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .payment-btn:hover {
            transform: translateY(-3px);
            box-shadow: 
                0 8px 24px rgba(99, 102, 241, 0.4),
                0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .payment-btn:active {
            transform: translateY(-1px);
        }

        .payment-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .pix-info, .boleto-info {
            text-align: center;
            padding: 3rem 2rem;
            background: linear-gradient(135deg, #fefce8, #fef3c7);
            border-radius: 16px;
            border: 2px dashed #fbbf24;
        }

        .pix-icon, .boleto-icon {
            font-size: 5rem;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1.5rem;
            filter: drop-shadow(0 4px 8px rgba(99, 102, 241, 0.2));
        }

        .payment-description {
            color: #78350f;
            margin-bottom: 2.5rem;
            font-size: 1rem;
            line-height: 1.6;
            font-weight: 500;
        }

        .security-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #f0fdf4;
            color: #15803d;
            padding: 0.625rem 1.25rem;
            border-radius: 50rem;
            font-size: 0.8125rem;
            font-weight: 600;
            border: 1px solid #bbf7d0;
            margin-top: 1.5rem;
        }

        .card-number-wrapper {
            position: relative;
        }

        .card-brand-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 2rem;
            opacity: 0.3;
            transition: opacity 0.3s ease;
        }

        .card-brand-icon.active {
            opacity: 1;
        }

        .payment-method-card {
            background: linear-gradient(135deg, #ffffff, #fafafa);
            border-radius: 16px;
            padding: 2rem;
            border: 2px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .payment-method-card:hover {
            border-color: #6366f1;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.1);
        }

        .form-row-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #e5e7eb, transparent);
            margin: 2rem 0;
        }

        .input-group-icon {
            position: relative;
        }

        .input-group-icon .form-control {
            padding-left: 3rem;
        }

        .input-group-icon i {
            position: absolute;
            left: 1.125rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.125rem;
            z-index: 10;
        }

        .payment-features {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .payment-feature {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #64748b;
            font-size: 0.875rem;
        }

        .payment-feature i {
            color: #10b981;
            font-size: 1.125rem;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .processing {
            animation: pulse 1.5s ease-in-out infinite;
        }

        @media (max-width: 991px) {
            .payment-wrapper {
                padding: 2rem 0;
            }

            .payment-title {
                font-size: 1.75rem;
            }

            .section-card {
                padding: 1.75rem;
            }

            .payment-tabs .nav-tabs {
                flex-direction: column;
            }

            .payment-btn {
                padding: 1.125rem 2.5rem;
                font-size: 0.95rem;
            }

            .payment-features {
                gap: 1rem;
            }

            .coupon-input-group {
                flex-direction: column;
            }

            .coupon-btn {
                width: 100%;
            }
        }
    </style>

    <div class="payment-wrapper">
        <div class="container">
            <div class="payment-header">
                <h1 class="payment-title">Finalizar Pedido</h1>
                <p class="text-muted">Complete o pagamento para confirmar seu pedido</p>
            </div>

            <div class="row gx-4">
                @component('layouts.partials.user-menu')
                @endcomponent

                <div class="col-md-9 col-12">
                    <!-- Coupon Section -->
                    <div class="section-card">
                        <h4 class="section-title">
                            <i class="bi bi-ticket-perforated"></i>
                            Cupom de Desconto
                        </h4>
                        <div class="coupon-input-group">
                            <input type="text" class="coupon-input" id="cupom-code"
                                placeholder="Digite o código do cupom">
                            <button class="coupon-btn submit-cupom">
                                <i class="bi bi-check-circle me-2"></i>Aplicar
                            </button>
                        </div>
                    </div>

                    <!-- Order Info -->
                    <div class="section-card">
                        <h4 class="section-title">
                            <i class="bi bi-receipt"></i>
                            Informações do Pedido
                        </h4>
                        <div class="order-info">
                            <div class="order-info-item">
                                <span class="order-info-label">Número do Pedido</span>
                                <span class="order-info-value">#{{ $order->id }}</span>
                            </div>
                            <div class="order-info-item">
                                <span class="order-info-label">Total do Pedido</span>
                                <span class="order-info-value total-order">{{ 'R$ ' . number_format($order->total, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <div class="section-card">
                        <h4 class="section-title">
                            <i class="bi bi-credit-card"></i>
                            Forma de Pagamento
                        </h4>

                        <input type="hidden" name="order_id" value="{{ $order->id }}">

                        <div class="payment-tabs">
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <button class="nav-link active" id="nav-credit-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-credit" type="button" role="tab">
                                        <i class="bi bi-credit-card-2-front"></i>
                                        Cartão de Crédito
                                    </button>
                                    <button class="nav-link" id="nav-pix-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-pix" type="button" role="tab">
                                        <i class="bi bi-qr-code"></i>
                                        PIX
                                    </button>
                                    <button class="nav-link" id="nav-boleto-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-boleto" type="button" role="tab">
                                        <i class="bi bi-upc-scan"></i>
                                        Boleto
                                    </button>
                                </div>
                            </nav>

                            <div class="tab-content" id="nav-tabContent">
                                <!-- Credit Card -->
                                <div class="tab-pane fade show active" id="nav-credit" role="tabpanel">
                                    <div class="payment-method-card">
                                        <div class="row inputs">
                                            <div class="col-12 mb-3">
                                                <label class="form-label">
                                                    <i class="bi bi-credit-card"></i>Número do Cartão
                                                </label>
                                                <div class="card-number-wrapper">
                                                    <input type="text" class="form-control" id="cardNumber"
                                                        placeholder="0000 0000 0000 0000">
                                                    <i class="bi bi-credit-card-fill card-brand-icon"></i>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">
                                                    <i class="bi bi-calendar-event"></i>Mês
                                                </label>
                                                <select class="form-select" id="mounth">
                                                    <option value="01">01 - Janeiro</option>
                                                    <option value="02">02 - Fevereiro</option>
                                                    <option value="03">03 - Março</option>
                                                    <option value="04">04 - Abril</option>
                                                    <option value="05">05 - Maio</option>
                                                    <option value="06">06 - Junho</option>
                                                    <option value="07">07 - Julho</option>
                                                    <option value="08">08 - Agosto</option>
                                                    <option value="09">09 - Setembro</option>
                                                    <option value="10">10 - Outubro</option>
                                                    <option value="11">11 - Novembro</option>
                                                    <option value="12">12 - Dezembro</option>
                                                </select>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">
                                                    <i class="bi bi-calendar-check"></i>Ano
                                                </label>
                                                <select class="form-select" id="year">
                                                    <option value="24">2024</option>
                                                    <option value="25">2025</option>
                                                    <option value="26">2026</option>
                                                    <option value="27">2027</option>
                                                    <option value="28">2028</option>
                                                    <option value="29">2029</option>
                                                    <option value="30">2030</option>
                                                    <option value="31">2031</option>
                                                    <option value="32">2032</option>
                                                    <option value="33">2033</option>
                                                    <option value="34">2034</option>
                                                    <option value="35">2035</option>
                                                </select>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">
                                                    <i class="bi bi-shield-lock"></i>CVV
                                                </label>
                                                <input type="text" class="form-control" id="cvv" placeholder="123" maxlength="4">
                                            </div>

                                            <div class="form-row-divider"></div>

                                            <div class="col-12 mb-4">
                                                <label class="form-label">
                                                    <i class="bi bi-person"></i>Nome do Titular
                                                </label>
                                                <input type="text" class="form-control" id="titularName"
                                                    placeholder="Nome como está no cartão" style="text-transform: uppercase;">
                                            </div>

                                            <div class="col-12 text-center">
                                                <button type="button" class="payment-btn submitPayment">
                                                    <i class="bi bi-lock-fill me-2"></i>Pagar Agora
                                                </button>
                                                
                                                <div class="payment-features">
                                                    <div class="payment-feature">
                                                        <i class="bi bi-shield-check"></i>
                                                        <span>Pagamento Seguro</span>
                                                    </div>
                                                    <div class="payment-feature">
                                                        <i class="bi bi-lock-fill"></i>
                                                        <span>Dados Criptografados</span>
                                                    </div>
                                                    <div class="payment-feature">
                                                        <i class="bi bi-check-circle-fill"></i>
                                                        <span>Compra Protegida</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- PIX -->
                                <div class="tab-pane fade" id="nav-pix" role="tabpanel">
                                    <div class="pix-info">
                                        <i class="bi bi-qr-code pix-icon"></i>
                                        <p class="payment-description">
                                            Ao confirmar, você receberá um QR Code para realizar o pagamento via PIX.
                                            O pagamento é processado instantaneamente.
                                        </p>
                                        <button type="button" class="payment-btn submitPix">
                                            <i class="bi bi-qr-code me-2"></i>Gerar QR Code PIX
                                        </button>
                                    </div>
                                </div>

                                <!-- Boleto -->
                                <div class="tab-pane fade" id="nav-boleto" role="tabpanel">
                                    <div class="boleto-info">
                                        <i class="bi bi-upc-scan boleto-icon"></i>
                                        <p class="payment-description">
                                            Ao confirmar, você receberá um boleto bancário.
                                            O prazo de compensação é de até 3 dias úteis.
                                        </p>
                                        <button type="button" class="payment-btn submitBoleto">
                                            <i class="bi bi-file-earmark-text me-2"></i>Gerar Boleto
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).on('click', '.submit-cupom', function() {
            var cupom = $('#cupom-code').val();
            var order_id = $('input[name=order_id]').val();
            
            $.ajax({
                url: "{{ route('discount.apply') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    cupom: cupom,
                    order_id: order_id,
                },
                beforeSend: function() {
                    $('.submit-cupom').html('<span class="spinner-border spinner-border-sm me-2"></span>Aplicando...');
                    $('.submit-cupom').prop('disabled', true);
                },
                success: function(data) {
                    if (!data[0]) {
                        $('.total-order').text(`R$ ${data.total.toFixed(2).replace('.', ',')}`);
                        $('.total-order').css("color", "#15803d");
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso!',
                            text: 'Cupom aplicado com sucesso!',
                            confirmButtonColor: '#6366f1'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Ops!',
                            text: 'Cupom indisponível ou inválido',
                            confirmButtonColor: '#6366f1'
                        });
                    }
                    $('.submit-cupom').html('<i class="bi bi-check-circle me-2"></i>Aplicar');
                    $('.submit-cupom').prop('disabled', false);
                }
            });
        });

        $('#cardNumber').mask('0000 0000 0000 0000');
        $('#cvv').mask('0000');

        $(document).on('click', '.submitPayment', function() {
            var cardNumber = $('#cardNumber').val();
            var mounth = $('#mounth').val();
            var year = $('#year').val();
            var order_id = $('input[name=order_id]').val();
            var cvv = $('#cvv').val();
            var titularName = $('#titularName').val();
            
            $.ajax({
                url: "{{ route('user.checkout') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    payment_type: 'credit',
                    installments: 1,
                    order_id: order_id,
                    card: {
                        card_number: cardNumber,
                        expiration_month: mounth,
                        expiration_year: year,
                        security_code: cvv,
                        holder_name: titularName
                    }
                },
                beforeSend: function() {
                    $('.submitPayment').html('<span class="spinner-border spinner-border-sm me-2"></span>Processando...');
                    $('.submitPayment').prop('disabled', true);
                    $('.inputs').find('input, select').prop("disabled", true);
                },
                success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        text: 'Compra aprovada com sucesso!',
                        confirmButtonColor: '#6366f1'
                    }).then(() => {
                        window.location.href = `{{ route('user.success') }}/${order_id}`;
                    });
                },
                error: function(data) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ops!',
                        text: 'Revise os dados do cartão e tente novamente',
                        confirmButtonColor: '#6366f1'
                    });
                    $('.submitPayment').html('<i class="bi bi-lock-fill me-2"></i>Pagar Agora');
                    $('.submitPayment').prop('disabled', false);
                    $('.inputs').find('input, select').prop("disabled", false);
                }
            });
        });

        $(document).on('click', '.submitPix', function() {
            var order_id = $('input[name=order_id]').val();
            
            $.ajax({
                url: "{{ route('user.checkout') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    payment_type: 'pix',
                    installments: 1,
                    order_id: order_id,
                },
                beforeSend: function() {
                    $('.submitPix').html('<span class="spinner-border spinner-border-sm me-2"></span>Processando...');
                    $('.submitPix').prop('disabled', true);
                },
                success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        text: 'QR Code gerado com sucesso!',
                        confirmButtonColor: '#6366f1'
                    }).then(() => {
                        window.location.href = `{{ route('user.success') }}/${order_id}`;
                    });
                },
                error: function(data) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ops!',
                        text: 'Erro ao gerar PIX. Entre em contato com o suporte.',
                        confirmButtonColor: '#6366f1'
                    });
                    $('.submitPix').html('<i class="bi bi-qr-code me-2"></i>Gerar QR Code PIX');
                    $('.submitPix').prop('disabled', false);
                }
            });
        });

        $(document).on('click', '.submitBoleto', function() {
            var order_id = $('input[name=order_id]').val();
            
            $.ajax({
                url: "{{ route('user.checkout') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    payment_type: 'boleto',
                    installments: 1,
                    order_id: order_id,
                },
                beforeSend: function() {
                    $('.submitBoleto').html('<span class="spinner-border spinner-border-sm me-2"></span>Processando...');
                    $('.submitBoleto').prop('disabled', true);
                },
                success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        text: 'Boleto gerado com sucesso!',
                        confirmButtonColor: '#6366f1'
                    }).then(() => {
                        window.location.href = `{{ route('user.success') }}/${order_id}`;
                    });
                },
                error: function(data) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ops!',
                        text: 'Erro ao gerar boleto. Entre em contato com o suporte.',
                        confirmButtonColor: '#6366f1'
                    });
                    $('.submitBoleto').html('<i class="bi bi-file-earmark-text me-2"></i>Gerar Boleto');
                    $('.submitBoleto').prop('disabled', false);
                }
            });
        });
    </script>
@endsection
