@extends('layouts.veterinario')
@section('content')
    @include('layouts.partials.vet-top')
    <div class="mt-5">
        <div class="container">
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Selecione o exame</label>
                <select class="form-control custom-select" name="exame" id="">
                    <option value="">Selecione</option>
                    @foreach ($exames as $exame)
                        <option value="{{ $exame->id }}">{{ $exame->title }} | {{ $exame->value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
@endsection
