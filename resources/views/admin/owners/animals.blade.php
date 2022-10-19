@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Animais</h1>
                <a href="" class="btn btn-primary">Criar Animal</a>
            </div>
            <div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>CodLab</th>
                            <th>Espécie</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse ($animals as $animal)
                            <tr>
                                <td>{{ $animal->animal_name }}</td>
                                <td>{{ $animal->codlab }}</td>
                                <td>{{ $animal->breed }}</td>
                                <td>
                                    <a href="" class="btn btn-sm btn-primary">Editar</a>
                                    <a href="" class="btn btn-sm btn-success">Detalhes</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td>Sem animal</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
