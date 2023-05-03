@extends('layouts.veterinario')
@section('content')
    @include('layouts.partials.vet-top')

    <div class="cad-animal">
        <form action="{{ route('animal.update.data', $animal->id) }}" method="post">
            <input type="hidden" name="pedido" value="{{ $pedido }}">
            @csrf
            <div class="container">
                <div class="cad-animal-content">
                    <div class="cad-animal-content-title">
                        <h2>Informações do animal</h2>
                    </div>
                    <div class="mb-3 cad-animal-content-input">
                        <label for="exampleFormControlInput1" class="form-label">Data da coleta</label>
                        <input type="date" class="form-control" name="collect_date">
                    </div>
                    <div class="mb-3 cad-animal-content-input">
                        <label for="exampleFormControlInput1" class="form-label">Data de nascimento</label>
                        <input type="date" class="form-control" name="birth_date">
                    </div>
                    <div class="buttons">

                        <div class="my-3 text-end">
                            <button type="submit" class="btnNext btn btn-alt-1">Próximo</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    </div>
@endsection
