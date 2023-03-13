@extends('layouts.veterinario')
@section('content')
    @include('layouts.partials.vet-top')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="mt-4">
                    <h3>Proprietários</h3>
                    <div class="mt-4">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                        <div class="me-3">
                                            <a href="{{ route('vet.owner.create') }}" class="btn btn-alt-2">Cadastrar novo
                                                proprietário</a>
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
                                                    <th scope="col">Documento</th>
                                                    <th scope="col">Email</th>
                                                    <th scope="col">Whatsapp</th>
                                                    <th scope="col">Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($owners as $owner)
                                                    <tr>
                                                        <td>{{ $owner->owner_name }}</td>
                                                        <td>{{ $owner->document }}</td>
                                                        <td>{{ $owner->email }}</td>
                                                        <td>{{ $owner->cell }}</td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <div class="me-2">
                                                                    <a href="#"
                                                                        class="btn btn-alt-2">Editar</a>
                                                                </div>
                                            
                                                            </div>
                                                        </td>
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
