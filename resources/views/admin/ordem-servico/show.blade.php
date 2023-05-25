@extends('layouts.admin')
@section('content')
    <div class="container">
        <div>
            <h3>Ordem de serviço numero: #{{ $ordem->id }}</h3>
        </div>
        <div class="row my-4">
            <div>
                <button class="btn btn-primary"><i class="fa-solid fa-print"></i></button>
            </div>
        </div>
        @foreach ($ordemServicos as $ordemServico)
            <div class="ordem-servico">
                <div class="card">
                    <div class="card-title">Animal ID</div>
                    <div class="card-data">{{ $ordemServico->animal_id }}</div>


                    <div class="card-title">Animal</div>
                    <div class="card-data">{{ $ordemServico->animal }}</div>

                    <div class="card-title">Código Lab</div>
                    <div class="card-data">{{ $ordemServico->codlab }}</div>

                    <div class="card-title">ID ABCCMM</div>
                    <div class="card-data">{{ $ordemServico->id_abccmm }}</div>

                    <div class="card-title">Tipo de Exame</div>
                    <div class="card-data">{{ $ordemServico->tipo_exame }}</div>

                    <div class="card-title">Proprietário</div>
                    <div class="card-data">{{ $ordemServico->proprietario }}</div>

                    <div class="card-title">Técnico</div>
                    <div class="card-data">{{ $ordemServico->tecnico }}</div>

                    <div class="card-title">Data esperada</div>
                    <div class="card-data">{{ date('d/m/Y', strtotime($ordemServico->data)) }}</div>

                    <div class="card-title">Observação</div>
                    <div class="card-data">{{ $ordemServico->observacao }}</div>

                    <div class="row my-4">
                        <div>
                            <a href="{{ route('gerar.barcode', $ordemServico->id) }}"> <button class="btn btn-primary"><i
                                        class="fa-solid fa-tag"></i></button></a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
@endsection
