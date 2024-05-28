@extends('layouts.admin')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card shadow-sm">
                    <div class="card-header bg-danger text-white">
                        <h1>Erro na data de pagamento cod: 2001</h1>
                    </div>
                    <div class="card-body">
                        <p>Este erro ocorre quando a data de pagamento não é encontrada. Siga os passos abaixo para corrigir:</p>

                        <div class="step mt-4">
                            <h3 class="text-primary">Passo 1: Verifique se foi gerado o pagamento para o cliente</h3>
                            <p>Certifique-se de que foi gerado o pagamento para o cliente. Para ver isso, basta clicar no pedido e ir em relatório do pedido. Em seguida, exclua a ordem de serviço.</p>
                        </div>

                        <div class="step mt-4">
                            <h3 class="text-primary">Passo 2: Gerando o pagamento</h3>
                            <p>Gere o pagamento novamente. Se já existir o pagamento gerado e estiver aguardando pagamento, coloque como pago (se o número do pedido for abaixo de 1200, contate o administrador).</p>
                        </div>

                        <div class="step mt-4">
                            <h3 class="text-primary">Passo 3: Gere a ordem novamente</h3>
                            <p>Após colocar o pedido como pago, gere a ordem novamente.</p>
                        </div>

                        <div class="step mt-4">
                            <h3 class="text-primary">Passo 4: Recarregue a Página</h3>
                            <p>Após fazer as correções, recarregue a página ou volte para a tela anterior para verificar se o problema foi resolvido.</p>
                        </div>

                        <div class="step mt-4">
                            <h3 class="text-primary">Passo 5: Contate o Suporte</h3>
                            <p>Se o problema persistir, entre em contato com o suporte técnico para assistência adicional.</p>
                        </div>

                        <div class="mt-4">
                            <a href="javascript:history.back()" class="btn btn-primary">Voltar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
