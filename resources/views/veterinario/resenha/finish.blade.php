@extends('layouts.veterinario')
@section('content')
    @include('layouts.partials.vet-top')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-5 mb-5">
                    <div class="card-body">
                        <h2 class="text-center">Resenha finalizada com sucesso!</h2>
                        <div class="text-center">
                            <a href="{{ route('vet.index') }}" class="btn btn-alt-2">Voltar para o
                                dashboard</a>
                        </div>
                        <div class="text-center my-3">
                            <a href="{{ route('view.resenha', $pedido->id) }}" class="btn btn-alt-2">Ver resenha e imprimir</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
