@extends('layouts.veterinario')
@section('content')
    @include('layouts.partials.vet-top')
    <div class="main">
        <div class="content row justify-content-around">
            <div class="col-4 menu-content" id="resenha">
                <div class="mt-3">
                    <div>
                        <img src="{{ asset('vet/img/resenha.png') }}" alt="">
                    </div>
                    <div>
                        <p>Criar Resenha</p>
                    </div>
                </div>

            </div>
            <div class="col-4 menu-content" id="animal">
                <div class="mt-3">
                    <div>
                        <img src="{{ asset('vet/img/animais.png') }}" alt="">
                    </div>
                    <div>
                        <p>Criar Pedido</p>
                    </div>
                </div>

            </div>

        </div>
        {{-- <div class="content row justify-content-around">
            <div class="col-4 menu-content" id="owner">
                <div class="mt-3">
                    <div>
                        <img src="{{ asset('vet/img/animais.png') }}" alt="">
                    </div>
                    <div>
                        <p>Proprietário</p>
                    </div>
                </div>

            </div>
            <div class="col-4 menu-content" id="configs">
                <div class="mt-3">
                    <div>
                        <img src="{{ asset('vet/img/animais.png') }}" alt="">
                    </div>
                    <div>
                        <p>Configurações</p>
                    </div>
                </div>

            </div>
        </div> --}}
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $(document).on('click', '#resenha', function() {
                window.location.href = "{{ route('vet.select') }}";
            });
            $(document).on('click', '#owner', function() {
                window.location.href = "{{ route('vet.owner.index') }}";
            });
            $(document).on('click', '#animal', function() {
                window.location.href = "{{ route('vet.order.owner.select') }}";
            });
            $(document).on('click', '#configs', function() {
                window.location.href = "{{ route('vet.configs') }}";
            });
        });
    </script>
@endsection
