@extends('layouts.admin')

@section('content')
    <div class="container">
        <div>
            <h3>Relatórios</h3>
        </div>
        <div>
            <p><strong>Total de Laudos Concluídos:</strong> {{ $totalLaudos }}</p>
            <p><strong>Total de Laudos com Exclusão:</strong> {{ $totalLaudosExclusao }}</p>
        </div>
        <div>
            <a href="{{ route('get.laudo.total') }}" class="btn btn-primary">Baixar Laudos Concluídos</a>
            <a href="{{ route('get.laudo.total.exclusao') }}" class="btn btn-danger">Baixar Laudos com Exclusão</a>
        </div>
    </div>
@endsection
