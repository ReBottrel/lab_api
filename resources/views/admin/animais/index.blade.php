@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Animais</h1>
                <div class="row">

                    <div class="col-6">
                        <form action="" method="POST" class="form form-inline">
                            @csrf
                            <label for="exampleFormControlInput1" class="form-label">Buscar por nome</label>
                            <input type="search" name="filter" class="form-control buscar-animal"
                                value="{{ $filters['filter'] ?? '' }}">
                        </form>
                    </div>
                    <div class=" col-4">
                        <label for="exampleFormControlInput1" class="form-label">Buscar por codlab</label>
                        <input type="text" class="form-control" id="codlab">
                    </div>
                    <div class="col-2 mt-4">
                        <button class="btn btn-primary" id="buscar-codlab" style="margin-top: 6px;">BUSCAR CODLAB</button>
                    </div>

                </div>

            </div>
            <div class="card-body">
                <div>
                    <div>
                        <a href="{{ route('animais.create') }}" class="btn btn-alt-loci text-white">Novo Animal</a>
                    </div>
                </div>
                <div class="table">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Raça</th>
                                <th>Especie</th>
                                <th>Codlab</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody class="filter">
                            @foreach ($animais as $animal)
                                @if ($animal->status == 1)
                                    @php
                                        $status = 'Aguardando amostra';
                                    @endphp
                                @elseif($animal->status == 2)
                                    @php
                                        $status = 'Amostra recebida';
                                    @endphp
                                @elseif($animal->status == 3)
                                    @php
                                        $status = 'Em análise';
                                    @endphp
                                @elseif($animal->status == 4)
                                    @php
                                        $status = 'Análise concluída';
                                    @endphp
                                @elseif($animal->status == 5)
                                    @php
                                        $status = 'Resultado disponível';
                                    @endphp
                                @elseif($animal->status == 6)
                                    @php
                                        $status = 'Análise reprovada';
                                    @endphp
                                @elseif($animal->status == 7)
                                    @php
                                        $status = 'Análise Aprovada';
                                    @endphp
                                @elseif($animal->status == 8)
                                    @php
                                        $status = 'Recoleta solicitada';
                                    @endphp
                                @elseif($animal->status == 9)
                                    @php
                                        $status = 'Amostra paga';
                                    @endphp
                                @elseif($animal->status == 10)
                                    @php
                                        $status = 'Pedido Concluído';
                                    @endphp
                                @endif

                                <tr>
                                    <td>{{ $animal->animal_name }}</td>
                                    <td> {{ $animal->breed }} </td>
                                    <td>
                                        {{ $animal->especies ?? 'Sem especie' }}
                                    </td>
                                    <td>
                                        {{ $animal->codlab ?? 'Sem codlab' }}
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-alt-loci text-white dropdown-toggle" href="#"
                                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                Ações
                                            </a>

                                            <ul class="dropdown-menu">

                                                <a href="{{ route('animais.show', $animal->id) }}"
                                                    class="dropdown-item">Editar</a>
                                                <a href="{{ route('animais.status', $animal->id) }}"
                                                    class="dropdown-item">Editar Status</a>
                                            </ul>
                                        </div>

                                    </td>
                                </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>
                <div class="pagin">
                    {{ $animais->links() }}
                </div>
            </div>
        </div>

    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('.buscar-animal').keyup(function() {
                var query = $(this).val();
                if (query != '') {

                    $.ajax({
                        url: "{{ route('search.animal') }}",
                        method: "GET",
                        data: {
                            search: query,

                        },
                        success: function(data) {
                            // console.log(data[0].animal[0]);
                            $('.filter').html(data[0].viewRender);
                            $('.pagin').addClass('d-none');
                        }
                    });
                } else {
                    $('.pagin').removeClass('d-none');
                }
            });
            $(document).on('click', '#buscar-codlab', function() {
                var query = $('#codlab').val();
                if (query != '') {

                    $.ajax({
                        url: "{{ route('search.codlab.animal') }}",
                        method: "POST",
                        data: {
                            codlab: query
                        },
                        success: function(response) {
                            if (response.viewRender) {
                        
                                $('.filter').html(response.viewRender);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erro!',
                                    text: 'Animal não encontrado!',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        }
                    });
                } else {
                    $('.pagin').removeClass('d-none');
                }
            });
        });
    </script>
@endsection
