@extends('layouts.admin')
@section('content')
    <div class="container">
        <div class="card my-4">
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
        <div class="card my-4">
            <div class="card-body">
                <div>
                    <h4>Cadastrar Proprietario</h4>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Nome do proprietario</label>
                            <input type="text" class="form-control" value="{{ $order->creator }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">CPF/CNPJ</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">CEP</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Endereço</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Numero</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Complemento</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">UF</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Bairro</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Cidade</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Telefone</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Email</label>
                            <input type="email" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3 text-center">
                            <button class="btn btn-success">Salvar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
