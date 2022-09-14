@component('mail::message')
#
<p>Seu pedido foi recebido acesse a area de cliente para ver as seus pedidos</p>
<p>Para acessar ao sistema, <a href="{{ url('/user-dashboard') }}">clique aqui</a> ou copie e cole no seu navegador {{ url('/user-dashboard') }}</p>
<p>Seu login é <strong>{{$user}}</strong></p>
<p>Sua senha é <strong>{{$senha}}</strong></p>
Obrigado,<br>
{{ config('app.name') }}
@endcomponent
