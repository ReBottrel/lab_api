@extends('layouts.admin')
@section('content')
    <div class="container">
        <div class="card my-4">
            <div class="card-body">
                <div>
                    <h4>Buscar proprietario</h4>
                </div>
                <form action="{{ route('animal') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $order->id }}">
                    <div class="row">
                        <div class="col-md-8">
                            <select class="js-example-basic-single" name="owner">
                                @foreach ($owners as $owner)
                                    <option value="{{ $owner->id }}">{{ $owner->owner_name }}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Continuar</button>
                        </div>
                    </div>
                </form>
                <div>
                    <h6>*Obs se não encontro o proprietário <a href="{{ route('owner.create') }}">clique aqui</a> para cadastrar um novo</h6>
                </div>
            </div>
        </div>
        <div class="card my-4">
            <div class="card-body">
                <div>
                    <h4>Proprietário do chamado: {{ $order->creator }}</h4>
                </div>
                @if ($order->owner)
                    <div>
                        <h4>
                            Proprietário associado: {{ $order->owner->owner_name }}
                        </h4>
                    </div>
                @endif

            </div>
        </div>
        @if ($order->owner)
            <div class="card my-4">
                <div class="card-body">
                    <div>
                        <h4>Editar o proprietário associado</h4>
                    </div>
                    <form action="{{ route('owner.update', $order->owner->id) }}" id="owner-form" method="post">
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Nome do proprietario</label>
                                    <input type="text" name="owner_name" class="form-control"
                                        value="{{ $order->owner->owner_name }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">CPF/CNPJ</label>
                                    <input type="text" name="document" value="{{ $order->owner->document }}"
                                        id="cpfcnpj" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Propriedade</label>
                                    <input type="text" name="propriety" value="{{ $order->owner->propriety }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">CEP</label>
                                    <input type="text" id="cep" value="{{ $order->owner->zip_code }}"
                                        name="zip_code" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Endereço</label>
                                    <input type="text" name="address" value="{{ $order->owner->address }}"
                                        class="form-control" id="address">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Numero</label>
                                    <input type="text" name="number" value="{{ $order->owner->number }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Complemento</label>
                                    <input type="text" name="complement" value="{{ $order->owner->complement }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">UF</label>
                                    <input type="text" name="state" value="{{ $order->owner->state }}"
                                        class="form-control" id="uf">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Bairro</label>
                                    <input type="text" name="district" value="{{ $order->owner->district }}"
                                        class="form-control" id="bairro">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Cidade</label>
                                    <input type="text" name="city" value="{{ $order->owner->city }}"
                                        class="form-control" id="cidade">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Telefone</label>
                                    <input type="text" name="fone" value="{{ $order->owner->fone }}"
                                        id="fone" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Cellar</label>
                                    <input type="text" name="cell" value="{{ $order->owner->cell }}"
                                        id="cell" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Email</label>
                                    <input type="email" name="email" value="{{ $order->owner->email }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3 text-center">
                                    <button class="btn btn-success" id="owner-save">Salvar</button>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection
