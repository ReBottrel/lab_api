@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3>Detalhes do proprietario</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Detalhes do proprietario</h4>
                        <p><strong>Nome: </strong>{{ $owner->owner_name }}</p>
                        <p><strong>E-mail: </strong>{{ strtolower($owner->email) }}</p>
                        <p><strong>Documento: </strong>{{ $owner->document }}</p>
                        <p><strong>Telefone: </strong>{{ $owner->fone }}</p>
                        <p><strong>Celular: </strong>{{ $owner->cell }}</p>
                        <p><strong>Endereço: </strong>{{ $owner->address }}, {{ $owner->number }}</p>
                        <p><strong>Bairro: </strong>{{ $owner->district }}</p>
                        <p><strong>Complemento: </strong>{{ $owner->complement }}</p>
                        <p><strong>Cidade: </strong>{{ $owner->city }}</p>
                        <p><strong>Estado: </strong>{{ $owner->state }}</p>
                        <p><strong>CEP: </strong>{{ $owner->zip_code }}</p>
                    </div>
                    <div class="col-md-6">
                        <h4>Propriedades</h4>
                        {{-- @if ($owner->properties->count() > 0)
                            <ul>
                                @foreach ($owner->properties as $property)
                                    <li><a
                                            href="{{ route('admin.properties.show', $property->id) }}">{{ $property->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>No properties found.</p>
                        @endif --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
