@extends('layouts.veterinario')
@section('content')
    @include('layouts.partials.vet-top')
    <div class="container">
        <form action="{{ route('vet.configs.store') }}" method="post">
            @csrf
            <div class="card mt-4">
                <input type="hidden" name="id" value="{{ auth()->user()->id }}">
                <div class="mb-3 p-3">
                    <label for="exampleFormControlInput1" class="form-label">Email</label>
                    <input type="text" class="form-control" value="{{ auth()->user()->email }}"
                        id="exampleFormControlInput1" disabled>
                </div>
                <div class="mb-3 p-3">
                    <label for="exampleFormControlInput1" class="form-label">Nome</label>
                    <input type="text" name="name" value="{{ auth()->user()->name }}" class="form-control"
                        id="exampleFormControlInput1">
                </div>
                <div class="mb-3 p-3">
                    <label for="exampleFormControlInput1" class="form-label">Senha</label>
                    <input type="password" name="password" class="form-control" id="exampleFormControlInput1">
                </div>
                <div class="mb-3 p-3">
                    <label for="exampleFormControlInput1" class="form-label">Confirmar Senha</label>
                    <input type="password" name="password-confirm" class="form-control" id="exampleFormControlInput1">
                </div>
                <div class="text-center mb-3">
                    <button type="submit" class="btn btn-alt-2">SALVAR</button>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {

            $('.btn-select').click(function() {
                window.location.href = "{{ route('vet.select') }}";
            });
        });
    </script>
@endsection
