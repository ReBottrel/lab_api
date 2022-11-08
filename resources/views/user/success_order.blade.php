@extends('layouts.loja')
@section('content')
    <div class="container">
        <h1 class="text-primary">Minha Conta</h1>
        <div class="row gx-3">
            @component('layouts.partials.user-menu')
            @endcomponent
            <div class="col-8">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="text-primary">Pedido #00{{ $pixreponse->order_request_id }}</h2>
                            <p class="text-secondary">Pedido realizado com sucesso!</p>

                            <p class="text-secondary">Acompanhe o status do seu pedido na página de pedidos.</p>
                            @if ($pixreponse->payment_type == 'pix')
                                <div class="col-12">
                                    <div class="col-md-8">
                                        <h4>Escaneie o QRCODE</h4>
                                        <img src="{{ $pixreponse->pixqrcode }}" alt="">
                                    </div>
                                    <div class="col-md-8">
                                        <h4>ou copie o código</h4>
                                        <code class="code">{{ $pixreponse->pixcode }}</code>

                                    </div>
                                    <div class="col-md-8 text-center my-3">
                                        <button class="btn btn-primary copiar">Copiar</button>
                                    </div>
                                </div>
                            @endif
                            @if ($pixreponse->payment_type == 'boleto')
                                <div class="col-12">
                                    <div class="col-md-8">
                                        <h4>Boleto</h4>
                                        <a href="{{ $pixreponse->boleto }}" class="text-decoration-none" target="_blank" style="font-size: 1.5em;">Clique aqui para abrir o
                                            boleto</a>
                                    </div>
                                    <div class="col-md-8">
                                        <h4>ou copie o código de barras</h4>
                                        <code class="code">{{ $pixreponse->pixcode }}</code>

                                    </div>
                                    <div class="col-md-8 text-center my-3">
                                        <button class="btn btn-primary copiar">Copiar</button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('scripts')
        <script>
            $(document).ready(function() {
                $('.copiar').click(function() {
                    var $temp = $("<input>");
                    $("body").append($temp);
                    $temp.val($('.code').text()).select();
                    document.execCommand("copy");
                    $temp.remove();
                    $(this).text('Copiado');
                });
            });
        </script>
    @endsection
