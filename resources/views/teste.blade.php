@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="header">Dados da API</h1>

    @php
        $data = json_decode($data, true); // Converte a string JSON para um array associativo
    @endphp

    <div class="row">
        <div class="col-md-6 my-3">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Exame</h2>
                </div>
                <div class="card-body">
                    <p class="card-text"><strong>ID:</strong> {{ $data['exame']['id'] }}</p>
                    <p class="card-text"><strong>Código:</strong> {{ $data['exame']['codigo'] }}</p>
                    <p class="card-text"><strong>Data de Cadastro:</strong> {{ $data['exame']['dataCadastro'] }}</p>
                    <p class="card-text"><strong>Data de Coleta:</strong> {{ $data['exame']['dataColeta'] }}</p>
                    <p class="card-text"><strong>Data de Resultado:</strong> {{ $data['exame']['dataResultado'] }}</p>
                    <p class="card-text"><strong>Laboratório:</strong> {{ $data['exame']['laboratorio'] }}</p>
                    <p class="card-text"><strong>Resultado:</strong> {{ $data['exame']['resultado'] }}</p>
                    <p class="card-text"><strong>Sigla:</strong> {{ $data['exame']['sigla'] }}</p>
                </div>
                <div class="card-footer">
                    <h4 class="card-subtitle">Alelos</h4>
                    <ul class="list-group">
                        @foreach ($data['exame']['alelos'] as $alelo)
                            <li class="list-group-item">Marcador: {{ $alelo['marcador'] }}, Alelo1: {{ $alelo['alelo1'] }},
                                Alelo2: {{ $alelo['alelo2'] }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6 my-3">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Técnico</h2>
                </div>
                <div class="card-body">
                    <p class="card-text"><strong>Nome:</strong> {{ $data['tecnico']['nome'] }}</p>
                    <p class="card-text"><strong>CPF/CNPJ:</strong> {{ $data['tecnico']['cpF_CNPJ'] }}</p>
                    <p class="card-text"><strong>UF:</strong> {{ $data['tecnico']['uf'] }}</p>
                    <p class="card-text"><strong>CRMV Número:</strong> {{ $data['tecnico']['crmvNumero'] }}</p>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h2 class="card-title">Animal</h2>
                </div>
                <div class="card-body">
                    <p class="card-text"><strong>ID:</strong> {{ $data['animal']['id'] }}</p>
                    <p class="card-text"><strong>Nome:</strong> {{ $data['animal']['nomeAnimal'] }}</p>
                    <p class="card-text"><strong>ID Pai:</strong> {{ $data['animal']['idPai'] }}</p>
                    <p class="card-text"><strong>ID Mãe:</strong> {{ $data['animal']['idMae'] }}</p>
                    <p class="card-text"><strong>Data de Nascimento:</strong> {{ $data['animal']['dataNascimento'] }}</p>
                    <p class="card-text"><strong>Registro:</strong> {{ $data['animal']['registro'] }}</p>
                    <p class="card-text"><strong>Sexo:</strong> {{ $data['animal']['sexo'] }}</p>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h2 class="card-title">Genitor</h2>
                </div>
                <div class="card-body">
                    <h4 class="card-subtitle">Exame</h4>
                    <p class="card-text"><strong>ID:</strong> {{ $data['genitor']['exame']['id'] }}</p>
                    <p class="card-text"><strong>Código:</strong> {{ $data['genitor']['exame']['codigo'] }}</p>
                    <p class="card-text"><strong>Data de Cadastro:</strong> {{ $data['genitor']['exame']['dataCadastro'] }}</p>
                    <p class="card-text"><strong>Data de Coleta:</strong> {{ $data['genitor']['exame']['dataColeta'] }}</p>
                    <p class="card-text"><strong>Data de Resultado:</strong> {{ $data['genitor']['exame']['dataResultado'] }}</p>
                    <p class="card-text"><strong>Laboratório:</strong> {{ $data['genitor']['exame']['laboratorio'] }}</p>
                    <p class="card-text"><strong>Resultado:</strong> {{ $data['genitor']['exame']['resultado'] }}</p>
                    <p class="card-text"><strong>Sigla:</strong> {{ $data['genitor']['exame']['sigla'] }}</p>
                </div>
                <div class="card-footer">
                    <h4 class="card-subtitle">Alelos</h4>
                    <ul class="list-group">
                        @foreach ($data['genitor']['exame']['alelos'] as $alelo)
                            <li class="list-group-item">Marcador: {{ $alelo['marcador'] }}, Alelo1:
                                {{ $alelo['alelo1'] }}, Alelo2: {{ $alelo['alelo2'] }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
