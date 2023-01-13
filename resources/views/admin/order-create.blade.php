@extends('layouts.admin')
@section('content')
    <div class="container">
        <div class="card my-4">
            <div class="card-header">
                <h4>Criar pedido</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('order.store.painel') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Nome do proprietario</label>
                            <select class="js-example-basic-single" name="owner">
                                @foreach ($owners as $owner)
                                    <option value="{{ $owner->id }}">{{ $owner->owner_name }}</option>
                                @endforeach
                            </select>
                            <div>
                                <h6>*Obs se não encontro o proprietário <a href="{{ route('owner.create') }}">clique
                                        aqui</a> para cadastrar um novo</h6>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Nome do técnico</label>
                            <select class="js-example-basic-single" name="tecnico">
                                @foreach ($tecnicos as $tecnico)
                                    <option value="{{ $tecnico->id }}">{{ $tecnico->professional_name }}</option>
                                @endforeach
                            </select>
                            <div>
                                <h6>*Obs se não encontro o técnico <a href="{{ route('techinical.create') }}">clique
                                        aqui</a> para
                                    cadastrar um novo</h6>
                            </div>
                        </div>
                        {{-- <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Numero da coleta</label>
                                <input type="text" name="collection_number" id="collection_number" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">ID da coleta</label>
                                <input type="text" name="uid" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Data da coleta</label>
                                <input type="date" name="collection_date" class="form-control">
                            </div>
                        </div> --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Data de recebimento</label>
                                <input type="date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Tipo de exame</label>
                                <select class="form-select tipo-exame" name="tipo" aria-label="Default select example">
                                    <option selected>Selecione o tipo de exame</option>
                                    <option value="1">DNA Genotipagem</option>
                                    <option value="2">DNA Homozigose</option>
                                    <option value="3">DNA Beta Caseína</option>
                                    <option value="4">Sorologia</option>
                                    <option value="5">Verificação de parentesco</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 my-4 ">
                            <button type="submit" class="btn btn-primary create-order">Próximo</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')

@endsection
