@extends('layouts.loja')
@section('content')
    <div class="container">
        <h1 class="text-primary">Minha Conta</h1>
        <div class="row gx-3">
            @component('layouts.partials.user-menu')
            @endcomponent
            <div class="col-8">
                <div class="row">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home"
                                type="button" role="tab" aria-controls="nav-home" aria-selected="true">Cartão de
                                crédito</button>
                            <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile"
                                type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Pix</button>
                            <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-boleto"
                                type="button" role="tab" aria-controls="nav-profile"
                                aria-selected="false">Boleto</button>

                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab"
                            tabindex="0">
                            <div class="container my-3">
                                <div class="row justify-content-center">
                                    <div class="col-8">
                                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                                        <div class="row inputs">
                                            <div class="mb-3 col-12">
                                                <label for="exampleFormControlInput1" class="form-label">Numero do
                                                    cartão</label>
                                                <input type="text" class="form-control" id="cardNumber">
                                            </div>
                                            <div class="col-4">
                                                <div class="mb-3">
                                                    <label for="exampleFormControlInput1" class="form-label">MES</label>
                                                  
                                                    <select class="form-select" id="mounth"
                                                    aria-label="Default select example">

                                                    <option value="01" selected>01</option>
                                                    <option value="02">02</option>
                                                    <option value="03">03</option>
                                                    <option value="04">04</option>
                                                    <option value="05">05</option>
                                                    <option value="06">06</option>
                                                    <option value="07">07</option>
                                                    <option value="08">08</option>
                                                    <option value="09">09</option>
                                                    <option value="10">10</option>
                                                    <option value="11">11</option>
                                                    <option value="12">12</option>
                                                  
                                                </select>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="mb-3">
                                                    <label for="exampleFormControlInput1" class="form-label">Ano</label>
                                                    <select class="form-select" id="year"
                                                        aria-label="Default select example">

                                                        <option value="22" selected>2022</option>
                                                        <option value="23">2023</option>
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
                                            </div>
                                            <div class="col-4">
                                                <div class="mb-3">
                                                    <label for="exampleFormControlInput1" class="form-label">CVV</label>
                                                    <input type="text" class="form-control" id="cvv">
                                                </div>
                                            </div>
                                            <div class="mb-3 col-12">
                                                <label for="exampleFormControlInput1" class="form-label">Nome do
                                                    Titular</label>
                                                <input type="text" class="form-control" id="titularName">
                                            </div>
                                            <div class="col-12 text-center">
                                                <button type="button"
                                                    class="btn btn-secondary submitPayment">Finalizar</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab"
                            tabindex="0">

                            <div class="container my-3">
                                <div class="row justify-content-center">
                                    <div class="col-8">
                                        <div class="row">

                                            <div class="col-12 text-center">
                                                <button type="button"
                                                    class="btn btn-secondary submitPix">Finalizar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-boleto" role="tabpanel" aria-labelledby="nav-profile-tab"
                            tabindex="0">

                            <div class="container my-3">
                                <div class="row justify-content-center">
                                    <div class="col-8">
                                        <div class="row">

                                            <div class="col-12 text-center">
                                                <button type="button"
                                                    class="btn btn-secondary submitBoleto">Finalizar</button>
                                            </div>
                                        </div>
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
                success: function(data) {
                    console.log(data);
                    Swal.fire(
                        'Sucesso!',
                        'Compra aprovada!',
                        'success'
                    )
                    window.location.href = `{{ route('user.success') }}/${order_id}`;
                },
                beforeSend: function() {
                    $('.submitPayment').html("PROCESSANDO...");
                    $('.inputs').find('input').prop("disabled", true);

                },
                error: function(data) {
                    Swal.fire(
                        'Ops!',
                        'Revise os dados do cartão',
                        'error'
                    )
                    $('.submitPayment').html("Finalizar");
                    $('.inputs').find('input').prop("disabled", false);
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
                success: function(data) {
                    console.log(data);
                    Swal.fire(
                        'Sucesso!',
                        'Pedido feito com sucesso!',
                        'success'
                    )
                    window.location.href = `{{ route('user.success') }}/${order_id}`;
                },
                beforeSend: function() {
                    $('.submitPix').html("PROCESSANDO...");
                    $('.inputs').find('input').prop("disabled", true);

                },
                error: function(data) {
                    Swal.fire(
                        'Ops!',
                        'Revise os dados do cartão',
                        'error'
                    )
                    $('.submitPix').html("Finalizar");
                    $('.inputs').find('input').prop("disabled", false);
                }
            });
        });
    </script>
@endsection
