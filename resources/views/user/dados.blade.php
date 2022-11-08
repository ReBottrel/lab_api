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
                            <h2 class="text-primary">Dados Pessoais</h2>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="list">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">Nome: {{ $user->name }}</li>
                                            <li class="list-group-item">Documento: {{ $user->info->document }}</li>
                                            <li class="list-group-item">E-mail: {{ $user->email }}</li>
                                            <li class="list-group-item">Celular: {{ $user->info->phone }}</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <a href="#" class="btn btn-primary">Alterar Dados</a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="list">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">CEP: {{ $user->info->zip_code }}</li>
                                            <li class="list-group-item">Endereço: {{ $user->info->address }},
                                                {{ $user->info->number }}</li>
                                            <li class="list-group-item">Bairro: {{ $user->info->district }}</li>
                                            <li class="list-group-item">Cidade: {{ $user->info->city }}</li>
                                            <li class="list-group-item">Estado: {{ $user->info->state }}</li>
                                            <li class="list-group-item">Complemento: {{ $user->info->complement }}</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <a href="#" class="btn btn-primary">Alterar Endereço</a>
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
    @endsection
