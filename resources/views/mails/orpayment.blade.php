@component('mail::message')

{{$msg}}

Clique nesse botão ou copie o link de pagaemnto com login.

@component('mail::button', ['url' => $link])
Botão de redirecionamento
@endcomponent

    {{$link}}

@endcomponent
