@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3>Editar pedido</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 my-3">
                        <h4>Editar proprietário e técnico</h4>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('order.owner.update', $order->id) }}" method="post">
                            @csrf
                            <div>
                                <p>Proprietário do pedido: {{ $order->creator }}</p>
                                <p>Proprietário associado ao pedido: {{ $order->user->name }}</p>
                            </div>
                            <div>
                                <label for="">Trocar proprietário</label>
                                <select class="js-example-basic-single" name="owner">
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="text-center mt-3">
                                <button type="submit" class="btn btn-primary">SALVAR</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('order.tecnico.update', $order->id) }}" method="post">
                            @csrf
                            <div>
                                <p>Técnico do pedido: {{ $order->technical_manager }}</p>
                                <p>Técnico associado ao pedido: {{ $order->tecnico->professional_name }}</p>
                            </div>
                            <div>
                                <label for="">Trocar proprietário</label>
                                <select class="js-example-basic-single" name="tecnico">
                                    @foreach ($tecnicos as $tecnico)
                                        <option value="{{ $tecnico->id }}">{{ $tecnico->professional_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="text-center mt-3">
                                <button type="submit" class="btn btn-primary">SALVAR</button>
                            </div>
                        </form>
                    </div>
                </div>
                <form action="{{ route('order.order.update', $order->id) }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="exampleFormControlInput1" class="form-label">Matricula do proprietário</label>
                            <input type="text" class="form-control" name="creator_number" value="{{ $order->creator_number }}">
                        </div>
                        <div>
                            <button class="btn btn-primary">SALVAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
