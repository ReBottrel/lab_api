@extends('layouts.admin')

@section('content')
    <div class="container alelos-compare">
        <div class="row justify-content-center align-items-center">
            <div class="col-2 bg-light border rounded text-center">
                <h5>Animal</h5>
            </div>
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
                        @if ($mae != null)
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
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-3 bg-light border rounded">
                <div class="row" id="valores">

                </div>

            </div>
        </div>
        <div class="d-none" id="resultado">
            <div class="mensagem px-5 pt-2"></div>
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
            const msg = [
                `Conclui-se que o produto AnimalFilho não está qualificado pela genitora Mae e não está qualificado pelo genitor Pai.`,
                `Conclui-se que o produto AnimalFilho não está qualificado pela genitora Mae e está qualificado pelo genitor Pai.`,
                `Conclui-se que o produto AnimalFilho está qualificado pela genitora Mae e não está qualificado pelo genitor Pai.`,
                `Conclui-se que o produto AnimalFilho qualificado pela genitora Mae e está qualificado pelo genitor Pai.`,
                `Conclui-se que o produto AnimalFilho não está qualificado pela genitora Mae`,
                `Conclui-se que o produto AnimalFilho não está qualificado pelo genitor Pai.`,
                `Conclui-se que o produto AnimalFilho está qualificado pelo genitor Pai.`,
                `Conclui-se que o produto AnimalFilho está qualificado pela genitora Mae`,
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
                        if (response.alelos_mae == null) {
                            vMae = true;
                        }
                        if (response.alelos_pai == null) {
                            vPai = true;
                        }
                        msg.forEach(function(msgItem, index) {
                            msg[index] = msgItem
                                .replace('AnimalFilho', response.animal.animal_name)
                                .replace('Mae', response.animal.mae)
                                .replace('Pai', response.animal.pai);
                        });

                
                        response.animal.alelos.forEach(function(alelo) {
                            let incluidos = '';
                            let excluidos = '';
                            let verificaMae = false;
                            let verificaPai = false;
                            const marcador = alelo.marcador;

                 

                            if (vMae == false) {
                                response.alelos_mae.forEach(function(query) {
                                    if (query.marcador === marcador) {
                                        if (query.alelo1 === '' && query
                                            .alelo2 === '') {
                                            verificaMae = true;
                                            
                                        }
                                        incluidos += 'M';
                                    }
                                });
                            } else {
                                verificaMae = false;
                            }

                            if (vPai == false) {
                                response.alelos_pai.forEach(function(query) {
                                    if (query.marcador === marcador) {
                                        if (query.alelo1 === '' && query
                                            .alelo2 === '') {
                                            verificaPai = true;
                                           
                                        }
                                        incluidos += 'P';
                                    }
                                });
                            } else {
                                verificaPai = false;
                            }
                        
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
                                    if (!response.alelos_pai) {
                                        excluidos = '';
                                    }
                                    break;
                                case 'P':
                                    excluidos = 'M';
                                    if (!response.alelos_mae) {
                                        excluidos = '';
                                    }
                                    break;
                                default:
                                    excluidos = 'MP';
                                    if (!response.alelos_mae) {
                                        excluidos = 'P';
                                        break;
                                    }
                                    if (!response.alelos_pai) {
                                        excluidos = 'M';
                                        break;
                                    }
                                    break;
                            }
                    
                            if (verificaPai) {
                                incluidos = incluidos.replace('P', '');
                                excluidos = excluidos.replace('P', '');
                            }

                            if (verificaMae) {
                                incluidos = incluidos.replace('M', '');
                                excluidos = excluidos.replace('M', '');
                            }

                            const html = `<div class="row">
                        <div class="col-6">
                            <input class="form-control incluidos" name="incluidos[]" type="text" value="${incluidos}">
                        </div>
                        <div class="col-6">
                            <input class="form-control excluidos" name="excluidos[]" type="text" value="${excluidos}">
                        </div>
                    </div>`;

                            let paiEmae = false;
                            let naoMae = false;
                            let naoPai = false;
                            let paiVazio = false;
                            let maeVazio = false;

                            $('#valores').append(html);
                            $('.excluidos').each(function() {
                                const valor = $(this).val();
                                if (valor === 'MP') {
                                    paiEmae = true;
                                    return false; // interrompe a iteração
                                } else if (valor.includes('M') || valor
                                    .includes('P')) {
                                    naoMae = true;
                                    naoPai = true;
                                    return false; // interrompe a iteração
                                } else if (valor === '') {
                                    maeVazio = true;
                                    paiVazio = true;
                                    return false; // interrompe a iteração
                                }
                            });

                            if (!paiEmae) {
                                $('.mensagem').html(
                                    `<textarea class="form-control" id="conclusao" rows="3">${msg[3]}</textarea>`
                                );
                            }

                            if (paiEmae) {
                                $('.mensagem').html(
                                    `<textarea class="form-control" id="conclusao" rows="3">${msg[0]}</textarea>`
                                );
                            }

                            if (naoMae) {
                                $('.mensagem').html(
                                    `<textarea class="form-control" id="conclusao" rows="3">${msg[1]}</textarea>`
                                );
                            }

                            if (naoPai) {
                                $('.mensagem').html(
                                    `<textarea class="form-control" id="conclusao" rows="3">${msg[2]}</textarea>`
                                );
                            }
                            if (maeVazio) {
                                $('.mensagem').html(
                                    `<textarea class="form-control" id="conclusao" rows="3">${msg[4]}</textarea>`
                                );
                            }
                            if (paiVazio) {
                                $('.mensagem').html(
                                    `<textarea class="form-control" id="conclusao" rows="3">${msg[5]}</textarea>`
                                );
                            }


                        });

                        $('#resultado').removeClass('d-none');
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
