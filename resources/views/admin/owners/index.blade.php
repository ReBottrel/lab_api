@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Proprietarios</h1>
                <div class="row">
                    <div class="col-6">
                        <a href="{{ route('owner.create') }}" class="btn btn-primary">Criar Proprietario</a>
                    </div>
                    <div class="col-6">
                        <form action="" method="POST" class="form form-inline">
                            @csrf
                            <input type="search" name="filter" placeholder="Buscar por nome..."
                                class="form-control buscar-owner" value="{{ $filters['filter'] ?? '' }}">
                        </form>
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
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody class="filter">
                            @foreach ($owners as $owner)
                                <tr>
                                    <td>{{ $owner->owner_name }}</td>
                                    <td>{{ strtolower($owner->email) }}</td>
                                    <td>
                                        <a href="{{ route('owner.edit', $owner->id) }}"
                                            class="btn btn-sm btn-primary">Editar</a>
                                        <a href="{{ route('get.owners.details', $owner->id) }}"
                                            class="btn btn-sm btn-success">Detalhes</a>
                                        @if ($owner->user_id)
                                            <a href="{{ route('owner.user', $owner->user_id) }}"><button type="button"
                                                    class="btn btn-sm btn-alt-loci text-white">Ver Usuário</button></a>
                                        @else
                                            <button type="button" data-id="{{ $owner->id }}"
                                                class="btn btn-sm btn-info create-access">Criar Acesso</button>
                                        @endif

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
@section('js')
    <script>
        $(document).ready(function() {
            $('.create-access').click(function() {
                var id = $(this).data('id');
                $.ajax({
                    url: "{{ route('owner.access') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        // console.log(data)

                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso',
                            text: 'Acesso criado com sucesso',
                        });

                    },
                    error: function(data) {
                        console.log(data)
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Algo deu errado!',
                        });
                    }
                });
            });
            $('.buscar-owner').keyup(function() {
                var search = $(this).val();
                $.ajax({
                    url: "{{ route('owners.search') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        search: search
                    },
                    success: function(data) {
                        console.log(data);
                        $('.filter').html(data[0].viewRender);
                    }
                });
            });
        });
    </script>
@endsection
