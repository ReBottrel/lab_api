@extends('layouts.veterinario')
@section('content')
    @include('layouts.partials.vet-top')
    <div class="main-select">
        <div class="d-flex justify-content-center gap-3">
            <div class="">
                <button class="btn btn-select">SELECIONAR ANIMAL</button>
            </div>
            <div class="">
                <button class="btn btn-create">CADASTRAR ANIMAL</button>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('.btn-select').click(function() {
                window.location.href = "{{ route('vet.owner2') }}";
            });
            $('.btn-create').click(function() {
                window.location.href = "{{ route('vet.owner') }}";
            });
        });
    </script>
@endsection
