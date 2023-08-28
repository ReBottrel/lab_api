@extends('layouts.admin')

@section('content')
    <div class="container alelos-compare">
        <input type="hidden" name="" id="ordem_id" value="{{ $ordem->id }}">
        <div class="row justify-content-center align-items-center">
            <div class="col-2 bg-light border rounded text-center">
                <h5>Compare alelos</h5>
            </div>
            @php
                $marcadores = [];
            @endphp

            <div class="col-2 bg-light border rounded text-center">
                <h5>{{ $mae->codlab ?? 'Sem verificação' }}</h5>
            </div>
            <div class="col-3 bg-light border rounded text-center">
                <h5>{{ $animal->codlab ?? 'Nao encontrado' }}</h5>
            </div>
            <div class="col-2 bg-light border rounded text-center">
                <h5>{{ $pai->codlab ?? 'Sem verificação' }}</h5>
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

                    @php
                        $marcadores = [];
                    @endphp

                    @foreach ($animal->alelos as $item)
                        @php
                            $marcadores[] = $item->marcador;
                        @endphp
                    @endforeach

                    @php
                        sort($marcadores); // Ordenar os marcadores
                    @endphp

                    @foreach ($marcadores as $marcador)
                        <div>
                            <p>
                                {{ $marcador }}
                            </p>
                        </div>
                    @endforeach

                </div>
            </div>
            <div class="col-2 bg-light border rounded">
                <div class="d-flex flex-column text-center mae">
                    <div class="row mt-2">
                        @if ($mae != null)
                            @foreach ($marcadores as $marcador)
                                @php
                                    $encontrado = false;
                                    // var_dump($marcador);
                                @endphp
                                @foreach ($mae->alelos as $item)
                                    @if (strtolower(trim($item->marcador)) == strtolower(trim($marcador)))
                                        @php
                                            $encontrado = true;
                                        @endphp
                                        <div class="col-6 @if ($item->alelo1 == '') py-2 @endif">
                                            @if ($item->alelo1 == '')
                                                *
                                            @else
                                                <p>{{ $item->alelo1 }}</p>
                                            @endif
                                        </div>
                                        <div class="col-6 @if ($item->alelo2 == '') py-2 @endif">
                                            @if ($item->alelo2 == '')
                                                *
                                            @else
                                                <p>{{ $item->alelo2 }}</p>
                                            @endif
                                        </div>
                                    @break
                                @endif
                            @endforeach
                            @if (!$encontrado)
                            @endif
                        @endforeach
                    @endif


                </div>
            </div>
        </div>

        <div class="col-3 bg-light border rounded">
            <div class="row">
                @foreach ($marcadores as $marcador)
                    @php
                        $encontrado = false;
                    @endphp
                    @foreach ($animal->alelos as $item)
                        @if ($item->marcador == $marcador)
                            @php
                                $encontrado = true;
                            @endphp
                            <div class="col-6">
                                @if ($item->alelo1 == '')
                                    <input class="form-control alelo1" data-id="{{ $item->id }}" type="text"
                                        value="*">
                                @else
                                    <input class="form-control alelo1" data-id="{{ $item->id }}" type="text"
                                        value="{{ $item->alelo1 }}">
                                @endif
                            </div>
                            <div class="col-6">
                                @if ($item->alelo2 == '')
                                    <input class="form-control alelo2" data-id="{{ $item->id }}" type="text"
                                        value="*">
                                @else
                                    <input class="form-control alelo2" data-id="{{ $item->id }}" type="text"
                                        value="{{ $item->alelo2 }}">
                                @endif
                            </div>
                        @break
                    @endif
                @endforeach
            @endforeach
        </div>
    </div>

    <div class="col-2 bg-light border rounded">
        <div class="d-flex flex-column text-center pai">
            <div class="row mt-2">
                @if ($pai != null)
                    @foreach ($marcadores as $marcador)
                        @php
                            $encontrado = false;
                        @endphp
                        @foreach ($pai->alelos as $item)
                            @if ($item->marcador == $marcador)
                                @php
                                    $encontrado = true;
                                @endphp
                                <div class="col-6 @if ($item->alelo1 == '') py-2 @endif">
                                    @if ($item->alelo1 == '')
                                        *
                                    @else
                                        <p>{{ $item->alelo1 }}</p>
                                    @endif
                                </div>
                                <div class="col-6 @if ($item->alelo2 == '') py-2 @endif">
                                    @if ($item->alelo2 == '')
                                        *
                                    @else
                                        <p>{{ $item->alelo2 }}</p>
                                    @endif
                                </div>
                            @break
                        @endif
                    @endforeach
                    @if (!$encontrado)
                        <!-- Lógica para o caso de o marcador não ser encontrado na mãe -->
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</div>
<div class="@if (empty($marcadores)) @else d-none @endif">
    <p>O produto não possuí alelos cadastrado <a href="{{ route('alelos.create') }}">Clique aqui para
            cadastrar</a></p>
</div>
<div class="col-3 bg-light border rounded">
    <div class="row" id="valores">

    </div>
    <div class="mt-2" id="salvar-btn">
        <button class="btn btn-primary" type="button" id="salvar">SALVAR VALORES</button>
    </div>
</div>
</div>
<div class="@if (!$result) d-none @endif" id="resultado">
<div class="mensagem px-5 pt-2">
    <textarea class="form-control resultadoAnalise" rows="3">
@if ($laudo)
{{ $laudo->conclusao }}
@endif
</textarea>
</div>
<div class="mb-3 px-5 pt-2">
    <label for="exampleFormControlTextarea1" class="form-label">Observação</label>
    <textarea class="form-control" id="obs" rows="3">
        @if ($laudo)
{{ $laudo->observacao }}
@endif
    </textarea>
</div>
<div class="mb-3 px-5 pt-2">
    <label for="exampleFormControlTextarea1" class="form-label">Retificação</label>
    <input class="form-control" id="ret"
        value="@if ($laudo) {{ $laudo->ret }} @endif">

</div>
<div class="mb-3 px-5 pt-2">
    <label for="exampleFormControlTextarea1" class="form-label">DATA FIXADA PARA VERIFICACAO</label>
    <input class="form-control" id="data_ret" type="date"
        value="@if ($laudo && $laudo->data_retificacao) {{ $laudo->data_retificacao }} @endif">

</div>
<div class="d-flex">
    <div>
        <button class="btn btn-primary" id="gerar-laudo">GERAR LAUDO</button>
    </div>
</div>
<input type="hidden" name="" id="laudo">
</div>
<div id="buttons" class="d-none my-3">
@php
    $status = 0;
    if ($laudo) {
        if ($laudo->status == 1) {
            $status = 1;
        } else {
            $status = 0;
        }
    }
@endphp
<div class="d-flex">
    <div>
        <button class="btn btn-primary" id="ver-laudo">
            VER LAUDO
        </button>
        <button class="btn btn-primary" id="pdf">
            GERAR PDF E ASSINAR
        </button>
        <button class="btn text-white @if ($status == 1) btn-success @else btn-primary @endif"
            id="finalizar">
            @if ($status == 1)
                FINALIZADO <i class="fa-solid fa-check"></i>
            @else
                FINALIZAR
            @endif

        </button>
    </div>
</div>
</div>
</div>
@endsection
@section('js')
<script>
    $(document).ready(function() {
        var id = $('#ordem_id').val();
        $.ajax({
            url: `{{ url('/get-result-alelo') }}/ ${id}`,
            type: 'GET',
            data: {
                _token: "{{ csrf_token() }}",
            },
            success: function(response) {
                console.log(response);

                const incluidosString = response.incluido ||
                    '[]'; // Definir como string vazia se for null ou undefined
                const excluidosString = response.excluido ||
                    '[]'; // Definir como string vazia se for null ou undefined

                const incluidos = JSON.parse(
                    incluidosString); // Converter a string em array JavaScript
                const excluidos = JSON.parse(
                    excluidosString); // Converter a string em array JavaScript

                // Verificar o tamanho do array mais longo entre incluidos e excluidos
                const length = Math.max(incluidos.length, excluidos.length);

                // Iterar com base no tamanho do array mais longo
                for (let i = 0; i < length; i++) {
                    const incluido = incluidos[i] ||
                        ''; // Definir como string vazia se for null ou undefined
                    const excluido = excluidos[i] ||
                        ''; // Definir como string vazia se for null ou undefined

                    const html = `<div class="row">
                    <div class="col-6">
                        <input class="form-control incluidos" name="incluidos[]" type="text" value="${incluido}">
                    </div>
                    <div class="col-6">
                        <input class="form-control excluidos" name="excluidos[]" type="text" value="${excluido}">
                    </div>
                </div>`;
                    $('#valores').append(html);
                }
            }
        });
    });




    $(document).ready(function() {
        $('.alelo1').keyup(function() {
            const id = $(this).data('id');
            const alelo1 = $(this).val().toUpperCase(); // Transforma para maiúsculas
            $.ajax({
                url: "{{ route('alelo.update') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    alelo1: alelo1,
                },
                success: function(response) {
                    console.log(response);
                }
            });
        });

        $('.alelo2').keyup(function() {
            const id = $(this).data('id');
            const alelo2 = $(this).val().toUpperCase(); // Transforma para maiúsculas
            $.ajax({
                url: "{{ route('alelo.update') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    alelo2: alelo2,
                },
                success: function(response) {
                    console.log(response);
                }
            });
        });

        $(document).on('click', '#salvar', function() {
            const ordem = $('#analisar').data('ordem');
            const incluidos = [];
            const excluidos = [];
            $('.incluidos').each(function() {
                incluidos.push($(this).val());
            });
            $('.excluidos').each(function() {
                excluidos.push($(this).val());
            });
            $.ajax({
                url: "{{ route('result.store') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    ordem: ordem,
                    incluidos: incluidos,
                    excluidos: excluidos,
                },
                success: function(response) {
                    console.log(response);

                }
            });

        });
    });




    $(document).ready(function() {
        vMae = false;
        vPai = false;
        let msgs = [
            `Conclui-se que o produto AnimalFilho não está qualificado pela genitora Mae (registro_mae) e não está qualificado pelo genitor Pai (registro_pai).`, // 0
            `Conclui-se que o produto AnimalFilho não está qualificado pela genitora Mae (registro_mae) e está qualificado pelo genitor Pai (registro_pai).`, // 1
            `Conclui-se que o produto AnimalFilho está qualificado pela genitora Mae (registro_mae) e não está qualificado pelo genitor Pai (registro_pai).`, // 2
            `Conclui-se que o produto AnimalFilho está qualificado pela genitora Mae (registro_mae) e está qualificado pelo genitor Pai (registro_pai).`, // 3
            `Conclui-se que o produto AnimalFilho não está qualificado pela genitora Mae (registro_mae).`, // 4
            `Conclui-se que o produto AnimalFilho não está qualificado pelo genitor Pai (registro_pai).`, // 5
            `Conclui-se que o produto AnimalFilho está qualificado pelo genitor Pai (registro_pai).`, // 6
            `Conclui-se que o produto AnimalFilho está qualificado pela genitora Mae (registro_mae)`, // 7
        ];
        $('#analisar').click(function() {
            const ordem = $(this).data('ordem');
            $('#valores').html('');
            $('.resultadoAnalise').html('');
            $('#salvar-btn').removeClass('d-none');
            $.ajax({
                url: "{{ route('alelo.analise') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    ordem: ordem,
                },
                success: function(response) {
                    console.log(response);

                    let vMae = response.laudoMae === null;
                    let vPai = response.laudoPai === null;

                    $('#resultado').removeClass('d-none');

                    let incluidos = [];
                    let excluidos = [];

                    let incluidosMae = response.laudoMae ? response.laudoMae.map(
                        query => (query.include === 'V') ? '' : query.include
                    ) : [];
                    let excluidosMae = response.laudoMae ? response.laudoMae.map(
                        query => (query.include === 'V') ? '' : (query.include === '' ?
                            'M' : '')
                    ) : [];

                    let incluidosPai = response.laudoPai ? response.laudoPai.map(
                        query => (query.include === 'V') ? '' : query.include
                    ) : [];
                    let excluidosPai = response.laudoPai ? response.laudoPai.map(
                        query => (query.include === 'V') ? '' : (query.include === '' ?
                            'P' : '')
                    ) : [];

                    let length = Math.max(incluidosMae.length, incluidosPai.length);
                    for (let i = 0; i < length; i++) {
                        incluidos.push(
                            `${incluidosMae[i] || ''}${incluidosPai[i] || ''}`);
                        excluidos.push(
                            `${excluidosMae[i] || ''}${excluidosPai[i] || ''}`);
                    }
                    if (response.animal && response.animal.alelos) {
                        let markersAndValues = [];
                        const alelosLength = response.animal.alelos.length;
                        const minLength = Math.min(alelosLength, incluidos.length, excluidos
                            .length); // pega o menor tamanho entre os três arrays

                        for (let i = 0; i < incluidos.length; i++) {
                            markersAndValues.push({
                                marker: response.animal.alelos[i].marcador ||
                                    'No Marker', // Note a correção aqui
                                included: incluidos[i] || '',
                                excluded: excluidos[i] || ''
                            });
                        }
                        // Ordenar o array com base nos marcadores
                        markersAndValues.sort((a, b) => a.marker.localeCompare(b.marker));

                        // Usar o array ordenado para gerar seus inputs
                        markersAndValues.forEach(item => {
                            const html = `<div class="row">
        <div class="col-6">
            <input class="form-control incluidos" name="incluidos[]" type="text"  value="${item.included}">
        </div>
        <div class="col-6">
            <input class="form-control excluidos" name="excluidos[]" type="text" value="${item.excluded}">
        </div>
    </div>`;
                            $('#valores').append(html);
                        });
                    } else {
                        console.error(
                            "Data missing: Check if 'response.animal.alelos' exists in the AJAX response."
                        );
                    }





                    const msg = [
                        `Conclui-se que o produto ${response.animal.animal_name} não está qualificado pela genitora ${response.mae ? response.mae.animal_name : ''} (${response.mae && response.mae.number_definitive ? response.mae.number_definitive : '*****'}) e não está qualificado pelo genitor ${response.pai ? response.pai.animal_name : ''} (${response.pai && response.pai.number_definitive ? response.pai.number_definitive : '*****'}).`,
                        `Conclui-se que o produto ${response.animal.animal_name} não está qualificado pela genitora ${response.mae ? response.mae.animal_name : ''} (${response.mae && response.mae.number_definitive ? response.mae.number_definitive : '*****'}) e está qualificado pelo genitor ${response.pai ? response.pai.animal_name : ''} (${response.pai && response.pai.number_definitive ? response.pai.number_definitive : '*****'}).`,
                        `Conclui-se que o produto ${response.animal.animal_name} está qualificado pela genitora ${response.mae ? response.mae.animal_name : ''} (${response.mae && response.mae.number_definitive ? response.mae.number_definitive : '*****'}) e não está qualificado pelo genitor ${response.pai ? response.pai.animal_name : ''} (${response.pai && response.pai.number_definitive ? response.pai.number_definitive : '*****'}).`,
                        `Conclui-se que o produto ${response.animal.animal_name} está qualificado pela genitora ${response.mae ? response.mae.animal_name : ''} (${response.mae && response.mae.number_definitive ? response.mae.number_definitive : '*****'}) e está qualificado pelo genitor ${response.pai ? response.pai.animal_name : ''} (${response.pai && response.pai.number_definitive ? response.pai.number_definitive : '*****'}).`,
                        `Conclui-se que o produto ${response.animal.animal_name} não está qualificado pela genitora ${response.mae ? response.mae.animal_name : ''} (${response.mae && response.mae.number_definitive ? response.mae.number_definitive : '*****'})`,
                        `Conclui-se que o produto ${response.animal.animal_name} não está qualificado pelo genitor ${response.pai ? response.pai.animal_name : ''} (${response.pai && response.pai.number_definitive ? response.pai.number_definitive : '*****'}).`,
                        `Conclui-se que o produto ${response.animal.animal_name} está qualificado pelo genitor ${response.pai ? response.pai.animal_name : ''} (${response.pai && response.pai.number_definitive ? response.pai.number_definitive : '*****'}).`,
                        `Conclui-se que o produto ${response.animal.animal_name} está qualificado pela genitora ${response.mae ? response.mae.animal_name : ''} (${response.mae && response.mae.number_definitive ? response.mae.number_definitive : '*****'}).`,
                    ];


                    let animal_name = response.animal.animal_name;
                    let pai_name = '';
                    let pai_registro = '';
                    let mae_name = '';
                    let mae_registro = '';
                    if (vPai == false) {
                        pai_name = response.pai.animal_name;
                        pai_registro = response.pai.number_definitive || '*****';
                    }
                    if (vMae == false) {
                        mae_name = response.mae.animal_name;
                        mae_registro = response.mae.number_definitive || '*****';
                    }

                    msgs = msgs.map(msg => msg.replace('AnimalFilho', animal_name)
                        .replace(
                            'Pai', pai_name).replace('Mae', mae_name).replace(
                            'registro_pai', pai_registro).replace('registro_mae',
                            mae_registro));


                    let mpfalse = false;
                    let naoExisteP = false;
                    let naoExisteM = false;
                    let mptrue = false;
                    let existeP = false;
                    let existeM = false;
                    let ms = '';


                    $('.excluidos').each(function() {
                        const excluidos = $(this).val();
                        if (excluidos === 'MP') {
                            mpfalse = true;

                        }
                        if (excluidos === 'P') {
                            naoExisteP = true;

                        }
                        if (excluidos === 'M') {
                            naoExisteM = true;

                        }

                    });

                    $('.incluidos').each(function() {
                        const incluidos = $(this).val();
                        if (incluidos === 'MP') {
                            mptrue = true;

                        }
                        if (incluidos === 'P') {
                            existeP = true;

                        }
                        if (incluidos === 'M') {
                            existeM = true;

                        }

                    });
                    console.log(mptrue, mpfalse, naoExisteP, naoExisteM, existeP,
                        existeM);
                    if (mptrue == true && mpfalse == false && naoExisteP == false &&
                        naoExisteM == false) {

                        $('.resultadoAnalise').val(msg[3]);

                    } else if (mptrue == true && mpfalse == true) {
                        $('.resultadoAnalise').val(msg[0]);
                    } else if (mptrue == true && naoExisteP == true) {
                        $('.resultadoAnalise').val(msg[2]);
                    } else if (mptrue == true && naoExisteM == true) {
                        $('.resultadoAnalise').val(msg[1]);
                    } else if (mptrue == false && naoExisteM == true && existeP ==
                        false &&
                        existeM == true) {
                        $('.resultadoAnalise').val(msg[4]);
                    } else if (mptrue == false && naoExisteM == false && existeP ==
                        false &&
                        existeM == true) {
                        $('.resultadoAnalise').val(msg[7]);
                    } else if (mptrue == false && naoExisteP == true && existeM ==
                        false &&
                        existeP == true) {
                        $('.resultadoAnalise').val(msg[5]);
                    } else if (mptrue == false && naoExisteP == false && existeM ==
                        false &&
                        existeP == true) {
                        $('.resultadoAnalise').val(msg[6]);
                    } else if (mptrue == false && mpfalse == false) {
                        $('.resultadoAnalise').val();
                    }


                }
            });

        });
    });


    $(document).ready(function() {
        $('#gerar-laudo').click(function() {
            let ordem = $('#analisar').data('ordem');
            let obs = $('#obs').val();
            let conclusao = $('.resultadoAnalise').val();
            let laudo = $('#laudo').val();
            let ret = $('#ret').val();
            let data_ret = $('#data_ret').val();
            $.ajax({
                url: "{{ route('gerar.laudo') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    ordem: ordem,
                    obs: obs,
                    conclusao: conclusao,
                    laudo: laudo,
                    ret: ret,
                    data_ret: data_ret

                },
                success: function(response) {
                    console.log(response);
                    $('#buttons').removeClass('d-none');
                    $('#laudo').val(response.id);
                },
                error: function(response) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Insira a data de coleta no pedido, antes de gerar o laudo.',
                    })
                }
            });
        });
        $(document).on('click', '#ver-laudo', function() {
            let laudo = $('#laudo').val();
            window.open(`/ver-laudo/${laudo}`, '_blank');
        });
        $(document).on('click', '#pdf', function() {
            let laudo = $('#laudo').val();
            window.open(`/gerar-pdf/${laudo}`, '_blank');
        });
        $(document).on('click', '#finalizar', function() {
            let laudo = $('#laudo').val();
            $(this).html('ENVIANDO...');
            $.ajax({
                url: "{{ route('pre.confirm') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    laudo: laudo,
                },
                success: function(response) {
                    console.log(response);
                    if (response.parceiro == null) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Não foi possível enviar o laudo, pois não existe parceiro cadastrado para o produto.',
                        });
                        $('#finalizar').html('FINALIZAR');
                        return false;
                    } else {
                        Swal.fire({
                            title: 'Você tem certeza?',
                            text: "Você está enviando o laudo para o seguinte parceiro " +
                                response.parceiro.nome,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Sim, continuar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: "{{ route('finalizar.laudo') }}",
                                    type: 'POST',
                                    data: {
                                        _token: "{{ csrf_token() }}",
                                        laudo: laudo,
                                    },
                                    success: function(response) {
                                        console.log(response);
                                        if (response[1].nome ==
                                            'ABCCMM') {
                                            if (response[0]
                                                .SetCertificateResult ==
                                                '000 - ABCCMM: Sucesso'
                                            ) {
                                                Swal.fire(
                                                    'Sucesso!',
                                                    'Laudo enviado com sucesso.',
                                                    'success'
                                                )
                                                $('#finalizar')
                                                    .html(
                                                        'FINALIZAR'
                                                    );
                                                // window.location.href =
                                                //     "{{ route('laudos') }}";
                                            } else {
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Oops...',
                                                    text: response[
                                                            0
                                                        ]
                                                        .SetCertificateResult,

                                                });
                                                $('#finalizar')
                                                    .html(
                                                        'FINALIZAR'
                                                    );
                                            }

                                        } else {
                                            Swal.fire(
                                                'Sucesso!',
                                                'Laudo enviado com sucesso.',
                                                'success'
                                            )
                                            $('#finalizar').html(
                                                'FINALIZAR');
                                            window.location.href =
                                                "{{ route('laudos') }}";
                                        }
                                    }

                                })
                            }
                        })
                    }
                }


            });
        });
    });
</script>
@endsection
