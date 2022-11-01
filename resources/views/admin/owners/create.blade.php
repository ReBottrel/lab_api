@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="card my-4">
            <div class="card-body">
                <div>
                    <h4>Editar Proprietario</h4>
                </div>
                <form action="{{ route('owner.store') }}" id="owner-form" method="post">
                    @csrf
                    {{-- <input type="hidden" name="order_id" value=""> --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Nome do proprietario</label>
                                <input type="text" name="owner_name" class="form-control" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">CPF/CNPJ</label>
                                <input type="text" name="document"  id="cpfcnpj" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Propriedade</label>
                                <input type="text" name="propriety"  class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">CEP</label>
                                <input type="text" id="cep" name="zip_code"  class="form-control">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Endereço</label>
                                <input type="text" name="address"  class="form-control" id="address">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Numero</label>
                                <input type="text" name="number"  class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Complemento</label>
                                <input type="text" name="complement"  class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">UF</label>
                                <input type="text" name="state"  class="form-control" id="uf">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Bairro</label>
                                <input type="text" name="district"  class="form-control" id="bairro">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Cidade</label>
                                <input type="text" name="city"  class="form-control" id="cidade">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Telefone</label>
                                <input type="text" name="fone"  id="fone" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Celular</label>
                                <input type="text" name="cell"  id="cell" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Email</label>
                                <input type="email" name="email"  class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3 text-center">
                                <button type="button" class="btn btn-success" id="owner-save">Salvar</button>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
@endsection
