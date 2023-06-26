@extends('layouts.admin')

@section('content')
    <div class="container alelos-compare">
        <div class="row justify-content-center align-items-center">
            <div class="col-2 bg-light border rounded text-center">
                <h5>Compare alelos</h5>
            </div>
            @php
                $marcadores = [];
            @endphp

            <div class="col-2 bg-light border rounded text-center">
                <h5>{{ $mae->id ?? 'Sem verificação' }}</h5>
            </div>
            <div class="col-3 bg-light border rounded text-center">
                <h5>{{ $animal->id ?? 'Nao encontrado' }}</h5>
            </div>
            <div class="col-2 bg-light border rounded text-center">
                <h5>{{ $pai->id ?? 'Sem verificação' }}</h5>
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

                    @foreach ($animal->alelos as $key => $item)
                        @php
                            $marcadores[] = $item->marcador;
                        @endphp
                        <div>
                            <p>
                                {{ $item->marcador }}
                            </p>
                        </div>
                    @endforeach


                </div>
            </div>
            @php
                $marcadores = array_values($marcadores); // Reindexar os marcadores
            @endphp
            <div class="col-2 bg-light border rounded">
                <div class="d-flex flex-column text-center mae">
                    <div class="row mt-2">
                        @if ($mae != null)
                            @foreach ($marcadores as $marcador)
                                @php
                                    $encontrado = false;
                                @endphp
                                @foreach ($mae->alelos as $item)
                                    @if ($item->marcador == $marcador)
                                        @php
                                            $encontrado = true;
                                        @endphp
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

    </div>
</div>
<div class="d-none" id="resultado">
    <div class="mensagem px-5 pt-2">

    </div>
    <div class="mb-3 px-5 pt-2">
        <label for="exampleFormControlTextarea1" class="form-label">Observação</label>
        <textarea class="form-control" id="obs" rows="3"></textarea>
    </div>
    <div class="d-flex">
        <div>
            <button class="btn btn-primary" id="gerar-laudo">GERAR LAUDO</button>
        </div>
    </div>
    <input type="hidden" name="" id="laudo">
</div>
<div id="buttons" class="d-none my-3">
    <div class="d-flex">
        <div>
            <button class="btn btn-primary" id="ver-laudo">
                VER LAUDO
            </button>
            <button class="btn btn-primary" id="pdf">
                GERAR PDF E ASSINAR
            </button>
            <button class="btn btn-primary" id="finalizar">
                FINALIZAR
            </button>
        </div>
    </div>
</div>
</div>
@endsection
@section('js')
<script>
    $(document).ready(function() {
        vMae = false;
        vPai = false;
        let msg = [
            `Conclui-se que o produto AnimalFilho não está qualificado pela genitora Mae e não está qualificado pelo genitor Pai.`, // 0
            `Conclui-se que o produto AnimalFilho não está qualificado pela genitora Mae e está qualificado pelo genitor Pai.`, // 1
            `Conclui-se que o produto AnimalFilho está qualificado pela genitora Mae e não está qualificado pelo genitor Pai.`, // 2
            `Conclui-se que o produto AnimalFilho está qualificado pela genitora Mae e está qualificado pelo genitor Pai.`, // 3
            `Conclui-se que o produto AnimalFilho não está qualificado pela genitora Mae`, // 4
            `Conclui-se que o produto AnimalFilho não está qualificado pelo genitor Pai.`, // 5
            `Conclui-se que o produto AnimalFilho está qualificado pelo genitor Pai.`, // 6
            `Conclui-se que o produto AnimalFilho está qualificado pela genitora Mae`, // 7
        ];
        $('#analisar').click(function() {
            const ordem = $(this).data('ordem');
            $('#valores').html('');
            $.ajax({
                url: "{{ route('alelo.analise') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    ordem: ordem,
                },
                success: function(response) {
                    console.log(response);

                    $('#resultado').removeClass('d-none');
                    $('#buttons').removeClass('d-none');

                    if (response.laudoMae === null && response.laudoPai === null) {
                        const html = `<div class="row">
                    <div class="col-6">
                        <input class="form-control incluidos" name="incluidos[]" type="text" value="">
                    </div>
                    <div class="col-6">
                        <input class="form-control excluidos" name="excluidos[]" type="text" value="">
                    </div>
                </div>`;
                        $('#valores').append(html);
                    } else {
                        let incluidosMae = [];
                        let excluidosMae = [];
                        if (response.laudoMae !== null) {
                            response.laudoMae.forEach(function(query) {
                                incluidosMae.push(query.include == 'M' ? 'M' : '');
                                excluidosMae.push(query.include == 'V' ? '' : (query
                                    .include == '' ? 'M' : ''));
                            });
                        }

                        let incluidosPai = [];
                        let excluidosPai = [];
                        if (response.laudoPai !== null) {
                            response.laudoPai.forEach(function(query) {
                                incluidosPai.push(query.include == 'P' ? 'P' : '');
                                excluidosPai.push(query.include == 'V' ? '' : (query
                                    .include == '' ? 'P' : ''));
                            });
                        }

                        let length = Math.max(incluidosMae.length, incluidosPai.length);
                        for (let i = 0; i < length; i++) {
                            const html = `<div class="row">
                        <div class="col-6">
                            <input class="form-control incluidos" name="incluidos[]" type="text" value="${incluidosMae[i] || ''}${incluidosPai[i] || ''}">
                        </div>
                        <div class="col-6">
                            <input class="form-control excluidos" name="excluidos[]" type="text" value="${excluidosMae[i] || ''}${excluidosPai[i] || ''}">
                        </div>
                    </div>`;
                            $('#valores').append(html);
                        }
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


                    let mpfalse = false;
                    let naoExisteP = false;
                    let naoExisteM = false;
                    let mptrue = false;
                    let existeP = false;
                    let existeM = false;

                    $('.excluidos').each(function() {
                        const excluidos = $(this).val();
                        if (excluidos === 'MP') {
                            mpfalse = true;
                            return false; // interrompe a iteração
                        }
                        if (excluidos === 'P') {
                            naoExisteP = true;
                            return false; // interrompe a iteração
                        }
                        if (excluidos === 'M') {
                            naoExisteM = true;
                            return false; // interrompe a iteração
                        }

                    });

                    $('.incluidos').each(function() {
                        const incluidos = $(this).val();
                        if (incluidos === 'MP') {
                            mptrue = true;
                            return false; // interrompe a iteração
                        }
                        if (incluidos === 'P') {
                            existeP = true;
                            return false; // interrompe a iteração
                        }
                        if (incluidos === 'M') {
                            existeM = true;
                            return false; // interrompe a iteração
                        }

                    });

                    if (mptrue == true && mpfalse == false && naoExisteP == false &&
                        naoExisteM == false) {
                        $('.mensagem').append(msg[3]);

                    } else if (mptrue == true && mpfalse == true) {
                        $('.mensagem').append(msg[0]);
                    } else if (mptrue == true && naoExisteP == true) {
                        $('.mensagem').append(msg[2]);
                    } else if (mptrue == true && naoExisteM == true) {
                        $('.mensagem').append(msg[1]);
                    } else if (mptrue == false && naoExisteM == true && existeP == false && existeM == true) {
                        $('.mensagem').append(msg[4]);
                    } else if (mptrue == false && naoExisteM == false && existeP == false && existeM == true) {
                        $('.mensagem').append(msg[7]);
                    } else if (mptrue == false && naoExisteP == true && existeM == false && existeP == true) {
                        $('.mensagem').append(msg[5]);
                    } else if (mptrue == false && naoExisteP == false && existeM == false && existeP == true) {
                        $('.mensagem').append(msg[6]);
                    } else if(mptrue == false && mpfalse == false){
                        $('.mensagem').append();
                    }



                    console.log('mptrue' + mptrue, 'naoExisteP' + naoExisteP, 'naoExisteM' +
                        naoExisteM, 'mpfalse' + mpfalse, 'existeP' + existeP,
                        'existeM' + existeM);

                }
            });
        });





        $(document).on('keyup', '.incluidos', function() {
            const valor = $(this).val();
            replaced = valor.toUpperCase();
            valor1 = $(this).val(replaced);
            console.log(replaced);
            switch (replaced) {
                case 'MP':
                    $(this).parent().parent().find('.excluidos').val('');
                    break;
                case 'M':
                    if (vPai == true) {
                        $(this).parent().parent().find('.excluidos').val('');
                        break;
                    } else {
                        $(this).parent().parent().find('.excluidos').val('P');
                        break;
                    }

                    case 'P':
                        if (vMae == true) {
                            $(this).parent().parent().find('.excluidos').val('');
                            break;
                        } else {
                            $(this).parent().parent().find('.excluidos').val('M');
                            break;
                        }

                        default:
                            $(this).parent().parent().find('.excluidos').val('MP');
            }

            let mpfalse = true;
            let naoExisteP = true;
            let naoExisteM = true;
            let todosContemP = true;
            $('.excluidos').each(function() {
                const excluidos = $(this).val();
                if (excluidos === 'P') {
                    naoExisteP = false;
                    return false; // interrompe a iteração
                }
                if (excluidos === 'M') {
                    naoExisteM = false;
                    return false; // interrompe a iteração
                }

            });
            $('.incluidos').each(function() {
                const incluidos = $(this).val();

                if (incluidos !== 'MP') {
                    mpfalse = false;
                    return false; // interrompe a iteração
                    // } else if (valor.includes('M') || valor.includes('P')) {
                    //     naoMae = true;
                    //     naoPai = true;
                    //     mpfalse = true;
                    //     return false; // interrompe a iteração
                    // }
                }

            });
            // if (todosContemP) {
            //     console.log("Todos os campos contêm a letra 'P'.");
            // } else {
            //     console.log("Pelo menos um campo não contém a letra 'P'.");
            // }
            if (vPai == false) {
                if (naoExisteP) {
                    $('#conclusao').val(msg[6]);
                } else {
                    console.log('existe P incluidos')
                }
            }
            if (vMae == false) {
                if (naoExisteM) {
                    $('#conclusao').val(msg[7]);
                } else {

                }
            }
            if (mpfalse) {
                $('#conclusao').val(msg[3]);
            }

        });
        $(document).on('keyup', '.excluidos', function() {
            const valor = $(this).val();
            replaced = valor.toUpperCase();
            valor1 = $(this).val(replaced);

            switch (replaced) {
                case 'MP':
                    $(this).parent().parent().find('.incluidos').val('');
                    break;
                case 'M':
                    if (vPai == true) {
                        $(this).parent().parent().find('.incluidos').val('');
                        break;
                    } else {
                        $(this).parent().parent().find('.incluidos').val('P');
                        break;
                    }

                    case 'P':
                        if (vMae == true) {
                            $(this).parent().parent().find('.incluidos').val('');
                            break;
                        } else {
                            $(this).parent().parent().find('.incluidos').val('M');
                            break;
                        }

                        default:
                            $(this).parent().parent().find('.incluidos').val('MP');
            }

            let mpfalse = true;
            let naoExisteP = true;
            let naoExisteM = true;
            let todosContemP = true;
            $('.excluidos').each(function() {
                const excluidos = $(this).val();
                if (excluidos === 'P') {
                    naoExisteP = false;
                    return false; // interrompe a iteração
                }
                if (excluidos === 'M') {
                    naoExisteM = false;
                    return false; // interrompe a iteração
                }

            });
            if (vPai == false) {
                if (naoExisteP) {
                    console.log('nao existe P')
                } else {
                    $('#conclusao').val(msg[5]);
                }
            }
            if (vMae == false) {
                if (naoExisteM) {
                    console.log('nao existe M')
                } else {
                    $('#conclusao').val(msg[4]);
                }
            }
        });
    });


    $(document).ready(function() {
        $('#gerar-laudo').click(function() {
            let ordem = $('#analisar').data('ordem');
            let obs = $('#obs').val();
            let conclusao = $('#conclusao').val();
            let laudo = $('#laudo').val();
            $.ajax({
                url: "{{ route('gerar.laudo') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    ordem: ordem,
                    obs: obs,
                    conclusao: conclusao,
                    laudo: laudo,
                },
                success: function(response) {
                    console.log(response);
                    $('#buttons').removeClass('d-none');
                    $('#laudo').val(response.id);
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
            $.ajax({
                url: "{{ route('finalizar.laudo') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    laudo: laudo,
                },
                success: function(response) {
                    console.log(response);
                    Swal.fire({
                        icon: 'success',
                        title: 'Laudo finalizado com sucesso!',
                        showConfirmButton: true,
                        timer: 1500,
                        timerProgressBar: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href =
                                "{{ route('ordem.servico.all') }}";
                        }
                    })
                },



            });
        });
    });
</script>
@endsection
