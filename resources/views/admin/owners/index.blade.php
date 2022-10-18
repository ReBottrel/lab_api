@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Proprietarios</h1>
                <a href="{{ route('owner.create') }}" class="btn btn-primary">Criar Proprietario</a>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Telefone</th>
                            <th>Email</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($owners as $owner)
                            <tr>
                                <td>{{ $owner->owner_name }}</td>
                                <td>{{ $owner->fone }}</td>
                                <td>{{ $owner->email }}</td>
                                <td>
                                    <a href="{{ route('owner.edit', $owner->id) }}" class="btn btn-primary">Editar</a>
                                    {{-- <form action="#" method="POST" style="display: inline-block">
                                        @csrf
                                        @method('DELETE')
                                      
                                    </form> --}}
                                    <button class="btn btn-info">Criar Acesso</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $owners->links() }}
            </div>
        </div>

    </div>
@endsection
