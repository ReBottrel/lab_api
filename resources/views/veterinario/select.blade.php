@extends('layouts.veterinario')
@section('content')
    @include('layouts.partials.vet-top')
    <div class="main-select">
        <div class="row justify-content-center">
            <div class="col-6">
                <button class="btn btn-select">SELECIONAR ANIMAL</button>
            </div>
            <div class="col-6">
                <button class="btn btn-create">CRIAR ANIMAL</button>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('.btn-select').click(function() {
                window.location.href = "{{ route('vet.select') }}";
            });
            $('.btn-create').click(function() {
                window.location.href = "{{ route('vet.owner') }}";
            });
        });
    </script>
@endsection
