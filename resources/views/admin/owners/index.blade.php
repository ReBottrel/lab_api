@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Proprietarios</h1>
                <a href="{{ route('owner.create') }}" class="btn btn-primary">Criar Proprietario</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($owners as $owner)
                                <tr>
                                    <td>{{ $owner->owner_name }}</td>
                                    <td>{{ $owner->email }}</td>
                                    <td>
                                        <a href="{{ route('owner.edit', $owner->id) }}"
                                            class="btn btn-sm btn-primary">Editar</a>
                                        <a href="" class="btn btn-sm btn-success">Detalhes</a>
                                        <button type="button" class="btn btn-sm btn-info">Criar Acesso</button>
                                        @if ($owner->old_id)
                                            <a href="{{ route('get.animals', $owner->old_id) }}"><button type="button"
                                                    class="btn btn-sm btn-warning my-2">Ver animais</button></a>
                                        @else
                                            <a href="{{ route('get.animals', $owner->id) }}"><button type="button"
                                                    class="btn btn-sm btn-warning my-2">Ver animais</button></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $owners->links() }}
            </div>
        </div>

    </div>
@endsection
