@extends('layouts.veterinario')
@section('content')
    @include('layouts.partials.vet-top')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="mt-4">
                    <h3>Animais</h3>
                    <div class="mt-4">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                        <div class="me-3">
                                            <a href="{{ route('vet.animal.create') }}" class="btn btn-alt-2">Cadastrar novo
                                                Animal</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Nome</th>
                                                    <th scope="col">Espécie</th>
                                                    <th scope="col">Sexo</th>
                                                    <th scope="col">Pelagem</th>
                                                 
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($animais as $animal)
                                                    <tr>
                                                        <td>{{ $animal->animal_name }}</td>
                                                        <td>{{ $animal->especies }}</td>
                                                        <td>{{ $animal->sex }}</td>
                                                        <td>{{ $animal->fur }}</td>
                                                    
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
