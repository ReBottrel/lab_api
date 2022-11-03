@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Técnicos</h1>
                <div class="row">
                    <div class="col-4">
                        <a href="{{ route('techinical.create') }}" class="btn btn-primary">Criar Técnico</a>
                    </div>
                    <div class="col-8">
                        <form action="" method="POST" class="form form-inline">
                            @csrf
                            <input type="search" name="filter" placeholder="Buscar por nome..."
                                class="form-control buscar-tecnico" value="{{ $filters['filter'] ?? '' }}">
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
                                <th>Telefone</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody class="filter">
                            @foreach ($tecnicos as $tecnico)
                                <tr>
                                    <td>{{ $tecnico->professional_name }}</td>
                                    <td>{{ $tecnico->email }}</td>
                                    <td>{{ $tecnico->cell }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-alt-loci text-white dropdown-toggle" href="#"
                                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                Ações
                                            </a>

                                            <ul class="dropdown-menu">
                                                <a href="{{ route('techinical.edit', $tecnico->id) }}"
                                                    class="dropdown-item">Editar</a>
                                                <a href="" class="dropdown-item">Detalhes</a>
                                            </ul>
                                        </div>

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
@section('js')
    <script>
        $(document).ready(function() {
            $('.buscar-tecnico').on('keyup', function() {
                var search = $(this).val();
                $.ajax({
                    url: "{{ route('techinical.search') }}",
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
