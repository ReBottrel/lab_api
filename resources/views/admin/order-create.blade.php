@extends('layouts.admin')
@section('content')
    <div class="container">
        <div class="card my-4">
            <div class="card-header">
                <h4>Criar pedido</h4>
            </div>
            <div class="card-body">
                <form action="">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="exampleFormControlInput1" class="form-label">Nome do proprietario</label>
                            <select class="js-example-basic-single" name="owner">
                                @foreach ($owners as $owner)
                                    <option value="{{ $owner->id }}">{{ $owner->owner_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="exampleFormControlInput1" class="form-label">Nome do técnico</label>
                            <select class="js-example-basic-single" name="owner">
                                @foreach ($tecnicos as $tecnico)
                                    <option value="{{ $tecnico->id }}">{{ $tecnico->professional_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 my-4">
                            <button type="button" class="btn btn-primary">Adicionar animal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
