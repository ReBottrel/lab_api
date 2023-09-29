@extends('layouts.admin')

@section('content')
    <div class="container">
        <div>
            <h3>Relatórios</h3>
        </div>
        <div>
            <p><strong>Total de Laudos Concluídos:</strong> {{ $totalLaudos }}</p>
            <p><strong>Total de Laudos com Exclusão:</strong> {{ $totalLaudosExclusao }}</p>
            <p><strong>Total de Laudos com Exclusão Genitor:</strong> {{ $totalLaudosExclusaoGenitor }}</p>
            <p><strong>Total de Laudos com Exclusão Genitora:</strong> {{ $totalLaudosExclusaoGenitora }}</p>
        </div>
        <div>
            <a href="{{ route('get.laudo.total') }}" class="btn btn-primary">Baixar Laudos Concluídos</a>
            <a href="{{ route('get.laudo.total.exclusao') }}" class="btn btn-danger">Baixar Laudos com Exclusão</a>
            <a href="{{ route('get.laudo.total.exclusao.genitor') }}" class="btn btn-danger">Baixar Laudos com Exclusão genitor</a>
            <a href="{{ route('get.laudo.total.exclusao.genitora') }}" class="btn btn-danger">Baixar Laudos com Exclusão genitora</a>
        </div>
    </div>
@endsection
