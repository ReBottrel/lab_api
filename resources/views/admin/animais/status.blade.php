@extends('layouts.admin')

@section('content')
    <div class="container">
        <form action="{{ route('alterar.status.animal.store') }}" method="post">
            @csrf
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Numero do pedido</label>
                <input type="text" class="form-control" id="exampleFormControlInput1" name="order_id">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Novo Status</label>
                <input type="text" class="form-control" id="exampleFormControlInput1" name="status">
            </div>
            <button type="submit" class="btn btn-success">SALVAR</button>
        </form>
    </div>
@endsection
