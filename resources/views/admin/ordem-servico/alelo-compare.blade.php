@extends('layouts.admin')

@section('content')
    <div class="container alelos-compare">
        <div class="row justify-content-center align-items-center">
            <div class="col-2 bg-light border rounded text-center">
                <h5>Animal</h5>
            </div>
            <div class="col-2 bg-light border rounded text-center">
                <h5>{{ $mae->codlab ?? 'Nao encontrado' }}</h5>
            </div>
            <div class="col-3 bg-light border rounded text-center">
                <h5>{{ $animal->codlab ?? 'Nao encontrado' }}</h5>
            </div>
            <div class="col-2 bg-light border rounded text-center">
                <h5>{{ $pai->codlab ?? 'Nao encontrado' }}</h5>
            </div>
            <div class="col-3 bg-light border rounded text-center">
                <button type="button" data-ordem="{{ $ordem->id }}" id="analisar"
                    class="btn btn-primary">ANALISAR</button>
            </div>
        </div>
        <div class="row">
            <div class="col-2 bg-light border rounded">
                <h5 class="text-center">Marcador</h5>
            </div>
            <div class="col-2 bg-light border rounded">
                <h5 class="text-center">Alelos</h5>
            </div>
            <div class="col-3 bg-light border rounded">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="text-center">Alelo A</h5>
                    </div>
                    <div>
                        <h5 class="text-center">Alelo B</h5>
                    </div>
                </div>
            </div>
            <div class="col-2 bg-light border rounded">
                <h5 class="text-center">Alelos</h5>
            </div>
            <div class="col-3 bg-light border rounded">
                <div class="d-flex justify-content-around align-items-center">
                    <div>
                        <h5 class="text-center">Inclui</h5>
                    </div>
                    <div>
                        <h5 class="text-center">Exclui</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-2 bg-light border rounded">
                <div class="d-flex flex-column text-center mt-2">
                    @foreach ($animal->alelos as $item)
                        <div>
                            <p>
                                {{ $item->marcador }}
                            </p>
                        </div>
                    @endforeach


                </div>
            </div>
            <div class="col-2 bg-light border rounded">
                <div class="d-flex flex-column text-center mae">
                    <div class="row mt-2">
                        @foreach ($mae->alelos as $item)
                            <div class="col-6">
                                @if ($item->alelo1 == '')
                                    *
                                @else
                                    <p>{{ $item->alelo1 }}</p>
                                @endif

                            </div>
                            <div class="col-6">
                                @if ($item->alelo2 == '')
                                    *
                                @else
                                    <p>{{ $item->alelo2 }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-3 bg-light border rounded">
                <div class="row">
                    @foreach ($animal->alelos as $item)
                        <div class="col-6">
                            @if ($item->alelo1 == '')
                                <input class="form-control" type="text" value="*">
                            @else
                                <input class="form-control" type="text" value="{{ $item->alelo1 }}">
                            @endif
                        </div>
                        <div class="col-6">
                            @if ($item->alelo2 == '')
                                <input class="form-control" type="text" value="*">
                            @else
                                <input class="form-control" type="text" value="{{ $item->alelo2 }}">
                            @endif
                        </div>
                    @endforeach

                </div>
            </div>
            <div class="col-2 bg-light border rounded">
                <div class="d-flex flex-column text-center pai">
                    <div class="row mt-2">
                        @foreach ($pai->alelos as $item)
                            <div class="col-6">
                                @if ($item->alelo1 == '')
                                    *
                                @else
                                    <p>{{ $item->alelo1 }}</p>
                                @endif
                            </div>
                            <div class="col-6">
                                @if ($item->alelo2 == '')
                                    *
                                @else
                                    <p>{{ $item->alelo2 }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-3 bg-light border rounded">
                <div class="row" id="valores">

                </div>
            </div>
        </div>
        <div class="d-none" id="resultado">
            <div class="mensagem p-5"></div>
            <div class="d-flex">
                <div>
                    <button class="btn btn-primary" id="concluir">GERAR LAUDO</button>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('#analisar').click(function() {
                let ordem = $(this).data('ordem');
                $('#valores').html('');
                $.ajax({
                    url: "{{ route('alelo.analise') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        ordem: ordem,
                    },
                    success: function(response) {

                        for (let i = 0; i < response.animal.alelos.length; i++) {
                            var incluidos = '';
                            var excluidos = '';
                            var verificaMae = false;
                            var verificaPai = false;
                            var marcador = response.animal.alelos[i].marcador;

                            response.alelos_mae.map(function(query) {
                                if (query.marcador === marcador) {
                                    if (query.alelo1 == '' && query.alelo2 == '') {
                                        verificaMae = true;
                                    }
                                    incluidos += 'M';
                                }

                            });

                            response.alelos_pai.map(function(query) {
                                if (query.marcador === marcador) {
                                    if (query.alelo1 == '' && query.alelo2 == '') {
                                        verificaPai = true;
                                        console.log(query.alelo1 + '-' + query.alelo2 +
                                            '-' + query.marcador)
                                    }
                                    incluidos += 'P';

                                }
                            });


                            if (verificaPai) {
                                incluidos = incluidos.replace('P', '');
                            }
                            if (verificaMae) {

                                incluidos = incluidos.replace('M', '');

                            }
                            switch (incluidos) {
                                case 'MP':
                                    excluidos = '';
                                    break;
                                case 'M':
                                    excluidos = 'P';
                                    break;
                                case 'P':
                                    excluidos = 'M';
                                    break;
                                default:
                                    excluidos = 'MP';

                            }
                            if (verificaPai) {
                                incluidos = incluidos.replace('P', '');
                                excluidos = excluidos.replace('P', '');
                            }
                            if (verificaMae) {
                                incluidos = incluidos.replace('M', '');
                                excluidos = excluidos.replace('M', '');
                            }

                            console.log(response);

                            var html = `<div class="row">
                                <div class="col-6">
                                    <input class="form-control" id="incluidos" name="incluidos[]" type="text" value="${incluidos}">
                                </div>
                                <div class="col-6">
                                    <input class="form-control excluidos" id="excluidos" name="excluidos[]" type="text" value="${excluidos}">
                                </div>
                            </div>`;

                            paiEmae = false;
                            naoMae = false;
                            naoPai = false;

                            $('#valores').append(html);
                            $('.excluidos').each(function() {
                                var valor = $(this).val();
                                if (valor === 'MP') {
                                    paiEmae = true;
                                    return false; // interrompe a iteração
                                } else if (valor.includes('M') || valor.includes('P')) {
                                    naoMae = true;
                                    naoPai = true;
                                    return false; // interrompe a iteração
                                }
                            });
                            if (paiEmae) {
                                $('.mensagem').html(
                                    `<p> 
                                Conclui-se que o animal  <strong>${response.animal.animal_name}</strong>
                                não é filho de <strong>${response.animal.mae}</strong>
                                e <strong>${response.animal.pai}</strong>
                                </p>`);
                            }
                            if (naoMae) {
                                $('.mensagem').html(
                                    `<p> 
                                Conclui-se que o animal ${response.animal.animal_name}
                                não é filho de ${response.animal.mae}.
                                </p>`);
                            }
                            if (naoPai) {
                                $('.mensagem').html(
                                    `<p> 
                                Conclui-se que o animal ${response.animal.animal_name}
                                não é filho de ${response.animal.pai}.
                                </p>`);
                            }

                        }
                        $('#resultado').removeClass('d-none');
                    }
                });
            });
            $(document).on('click', '#concluir', function() {
                window.open("{{ route('laudo') }}", '_blank');
            });
        });
    </script>
@endsection
