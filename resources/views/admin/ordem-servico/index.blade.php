@extends('layouts.admin')
@section('content')
    <div class="container">
        <div>
            <h3>Todas a ordens de serviço</h3>
        </div>
        <div class="row">
            <div class="mb-3 col-6">
                <label for="exampleFormControlInput1" class="form-label">Buscar por proprietário ou por numero</label>
                <input type="text" class="form-control">
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Numero/Pedido</th>
                    <th scope="col">Proprietario</th>
                    <th>Data de criação</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ordemServicos as $item)
                    <tr>
                        <th scope="row">{{ $item->order_id }}</th>

                        <td>{{ $item->owner }}</td>
                        <th>{{ date('d/m/Y', strtotime($item->created_at)) }}</th>
                        <td>
                            <div class="row">
                                <div class="col-4"><a href="{{ route('ordem.servico.show', $item->id) }}"><button
                                            class="btn btn-primary"><i class="fa-solid fa-eye"></i>
                                        </button></a>
                                </div>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $ordemServicos->links() }}
    </div>
@endsection
