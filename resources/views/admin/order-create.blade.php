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
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Nome do técnico</label>
                            <select class="js-example-basic-single" name="tecnico">
                                @foreach ($tecnicos as $tecnico)
                                    <option value="{{ $tecnico->id }}">{{ $tecnico->professional_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Numero da coleta</label>
                                <input type="text" name="collection_number" id="collection_number" class="form-control">
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
