@extends('layouts.loja')
@section('content')
    <div class="container">
        <h1 class="text-primary">Minha Conta</h1>
        <div class="row gx-3">
            @component('layouts.partials.user-menu')
            @endcomponent
            <div class="col-8">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="text-primary">Alterar Endereço</h2>
                                
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('scripts')
    @endsection
