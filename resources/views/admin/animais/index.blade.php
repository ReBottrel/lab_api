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
                            <input type="search" name="filter" placeholder="Buscar por nome..."
                                class="form-control buscar-animal" value="{{ $filters['filter'] ?? '' }}">
                        </form>
                    </div>
                    {{-- <div class="col-6">
                        <form>
                            <select class="form-select status-filter">
                                <optgroup label="Status">
                                    <option value="0"> Todos</option>
                                    <option value="1"> Aguardando amostra</option>
                                    <option value="2"> Amostra recebida</option>
                                    <option value="7"> Amostra aprovada</option>
                                    <option value="6"> Amostra reprovada</option>
                                    <option value="7"> Aguardando pagamento</option>
                                    <option value="9"> Pagamento confirmado</option>
                                    <option value="10"> Pedido concluído</option>
                                </optgroup>
                            </select>
                        </form>
                    </div> --}}
                </div>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Raça</th>
                                <th>Status</th>
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
                                        {{ $status }}
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-alt-loci text-white dropdown-toggle" href="#"
                                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                Ações
                                            </a>

                                            <ul class="dropdown-menu">

                                                <a href="{{ route('animais.show', $animal->id) }}" class="dropdown-item">Editar</a>
                                                <a href="{{ route('animais.status', $animal->id) }}" class="dropdown-item">Editar Status</a>
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
        });
    </script>
@endsection
