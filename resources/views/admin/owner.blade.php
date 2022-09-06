@extends('layouts.admin')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div>
                    <h4>Buscar proprietario</h4>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <select class="js-example-basic-single" name="state">
                            <option value="AL">Felipe da cruz machado</option>
                            ...
                            <option value="WY">Leandro </option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-primary">Continuar</button>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
