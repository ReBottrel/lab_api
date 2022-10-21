@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Técnicos</h1>
                <div class="row">
                    <div class="col-6">
                        <a href="#" class="btn btn-primary">Criar Técnico</a>
                    </div>
                    <div class="col-6">
                        <p>Em breve filtros</p>
                    </div>
                </div>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Telefone</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tecnicos as $tecnico)
                                <tr>
                                    <td>{{ $tecnico->professional_name }}</td>
                                    <td>{{ $tecnico->email }}</td>
                                    <td>{{ $tecnico->cell }}</td>
                                    <td>
                                        <a href="{{ route('owner.edit', $tecnico->id) }}"
                                            class="btn btn-sm btn-primary">Editar</a>
                                        <a href="" class="btn btn-sm btn-success">Detalhes</a>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $tecnicos->links() }}
            </div>
        </div>

    </div>
@endsection
