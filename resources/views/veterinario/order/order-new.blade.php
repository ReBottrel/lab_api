@extends('layouts.veterinario')
@section('content')
    @include('layouts.partials.vet-top')
    <div class="container">
        <div class="row">
            <div class="col-12 mt-3">
                <h3>Selecione os animais para o pedido</h3>
            </div>
        </div>
        <form action="{{ route('vet.order.store.new') }}" method="post">
            <input type="hidden" name="owner" value="{{ $owner->id }}">
            @csrf
            <div class="row">
                @foreach ($pedidos as $pedido)
                    @php
                        $animal = App\Models\Animal::where('id', $pedido->id_animal)->first();
                    @endphp
                    <div class="col-12 my-2  list-itens">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="pedidos[]" value="{{ $pedido->id }}"
                                id="flexCheckDefault-{{ $pedido->id }}">
                            <label class="form-check-label" for="flexCheckDefault-{{ $pedido->id }}">
                                {{ $animal->animal_name }}
                            </label>
                        </div>
                    </div>
                @endforeach

            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-create">FINALIZAR</button>
            </div>
        </form>
    </div>
@endsection
