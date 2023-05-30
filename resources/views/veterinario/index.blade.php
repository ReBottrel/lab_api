@extends('layouts.veterinario')
@section('content')
    @include('layouts.partials.vet-top')
    <div class="main">
        <div class="main-title">
            <p>Seja bem vindo! <span>{{ Auth::user()->name }}</span> </p>
        </div>
        <div class="content d-flex justify-content-center gap-5">
            <div class="menu-content" id="resenha">
                <a href="{{ route('vet.select') }}">
                    <iconify-icon icon="clarity:list-solid" width="50" height="50">
                    </iconify-icon>
                    <p class="mb-0">Criar Resenha</p>
                </a>
            </div>

            <div class="menu-content" id="animal">
                <a href="{{ route('vet.order.owner.select') }}">
                    <iconify-icon icon="mdi:horse-variant-fast" width="50" height="50"></iconify-icon>
                    <p class="mb-0">Criar Pedido</p>
                </a>
                {{-- <div class="mt-3">
                    <div>
                        <img src="{{ asset('vet/img/animais.png') }}" alt="">
                    </div>
                    <div>
                        <p>Criar Pedido</p>
                    </div>
                </div> --}}

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
