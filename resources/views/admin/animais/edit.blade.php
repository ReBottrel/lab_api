@extends('layouts.admin')

@section('content')
    <input type="hidden" name="id" class="id" value="{{ $animal->id }}">
    <div id="editar-produto">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h1>Editar Animal</h1>
                </div>
                <div class="card-body">
                    <form action="{{ route('animais.update', $animal->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">ID do sistema</label>
                                    <input type="text" name="id" id="id" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Nome do animal</label>
                                    <input type="text" name="animal_name" id="animal_name" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Verificação Extra(Mesmo
                                        Produto)</label>
                                    <input class="form-check-input" name="extra" type="checkbox" value=""
                                        id="extraVerify">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Codlab</label>
                                    <input type="text" name="codlab" id="codlab" class="form-control"
                                        @if ($laudo) @if ($laudo->status == 1) readonly @endif
                                        @endif>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">ID do animal</label>
                                    <input type="text" name="register_number_brand" id="register_number_brand"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Registro do animal</label>
                                    <input type="text" name="number_definitive" id="number_definitive"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Sexo</label>
                                    <select class="form-select" name="sex">
                                        <option value="F">F</option>
                                        <option value="M">M</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Espécie</label>
                                    <select class="form-select" name="especies">
                                        <option value="EQUINA">EQUINA</option>
                                        <option value="BOVINA">BOVINA</option>
                                        <option value="ASININO">ASININOS</option>
                                        <option value="MUARES">MUARES</option>
                                        <option value="PEGA_EQUINO">EQUINO PÊGA</option>
                                        <option value="EQUINA_TKY">EQUINA TKY</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Raça</label>
                                    <select class="form-select" name="breed">
                                        @foreach ($breeds as $breed)
                                            <option value="{{ $breed->name }}">{{ $breed->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Data de coleta</label>
                                    <input type="date" name="" id="birth_date" class="form-control">
                                </div>
                            </div> --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Idade</label>
                                    <input type="text" name="age" id="age" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Data de nascimento</label>
                                    <input type="date" name="birth_date" id="birth_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Numero do chip</label>
                                    <input type="text" name="chip_number" id="chip_number" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Numero de registro do
                                            pai</label>
                                        <input type="text" name="registro_pai" id="registro_pai"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Nome do
                                            pai</label>
                                        <select class="js-pai-basic-single" name="pai_id">

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Numero de registro da
                                            mãe</label>
                                        <input type="text" name="registro_mae" id="registro_mae"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Nome da mãe</label>
                                        <select class="js-mae-basic-single" name="mae_id">

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">Identificador/ REF do laudo</label>
                                    <input type="text" name="identificador" id="identificador" class="form-control">
                                </div>
                            </div>
                            <input type="hidden" name="mae" id="mae_animal">
                            <input type="hidden" name="pai" id="pai_animal">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Salvar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $(document).on('change', '#extraVerify', function() {

                if ($(this).is(':checked')) {
                    $('#extraVerify').val('1');


                } else {
                    $('#extraVerify').val('0');

                }
            });
            var id = $('.id').val();

            $.ajax({
                url: `{{ url('animal-edit') }}/${id}`,
                type: 'GET',
                success: (data) => {
                    console.log(data);
                    for (i in data.animal) {

                        if (i === 'birth_date') { // Verifica se a chave é 'data_nascimento'
                            var dataString = data.animal[i];
                            if (dataString != null) {
                                if (dataString.includes('T')) {
                                    // A data já está no formato 'YYYY-MM-DDTHH:MM:SS', não precisa de ajuste
                                    var dataObj = new Date(dataString);
                                } else {
                                    // A data está no formato 'YYYY-MM-DD', então acrescente 'T00:00:00' para evitar problemas
                                    var dataObj = new Date(dataString + 'T00:00:00');
                                }

                                var ano = dataObj.getFullYear();
                                var mes = String(dataObj.getMonth() + 1).padStart(2,
                                    '0'); // Note o +1 aqui para ajustar o mês
                                var dia = String(dataObj.getDate()).padStart(2, '0');
                                var dataFormatada = `${ano}-${mes}-${dia}`;
                                var inputField = $('#editar-produto').find(`[name="${i}"]`);
                                inputField.val(dataFormatada);
                            }
                        } else {
                            var inputField = $('#editar-produto').find(`[name="${i}"]`);
                            inputField.val(data.animal[i]);
                        }
                    }
                    if (data.pai) {
                        var $paiSelect = $('.js-pai-basic-single');
                        var newOption = new Option(data.pai.animal_name, data.pai.id, true, true);
                        $paiSelect.append(newOption).trigger('change');
                    }
                    if (data.mae) {
                        var $maeSelect = $('.js-mae-basic-single');
                        var newOption = new Option(data.mae.animal_name, data.mae.id, true, true);
                        $maeSelect.append(newOption).trigger('change');
                    }
                },
            });


            $('.js-pai-basic-single').select2({
                placeholder: 'Selecione o proprietário',
                width: '100%',
                ajax: {
                    url: "{{ route('get.dados.animal') }}",
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            page: params.page
                        };
                    },
                    processResults: function(data) {
                        var mappedData = data.map(function(item) {
                            return {
                                id: item.id,
                                text: item.animal_name + " (" + item.codlab + " - " + item
                                    .especies + ")" // Adicionando codlab e especies ao nome
                            };
                        });

                        return {
                            results: mappedData
                        };
                    },
                    cache: true
                },
                minimumInputLength: 2,
            }).on('change', function(e) {
                var fullText = $(this).select2('data')[0].text;
                var animalName = fullText.split(" (")[
                    0]; // Isso pega a parte do texto antes de " (Codlab - Especies)"
                $('#pai_animal').val(animalName);
            });
            $('.js-mae-basic-single').select2({
                placeholder: 'Selecione o proprietário',
                width: '100%',
                ajax: {
                    url: "{{ route('get.dados.animal') }}",
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            page: params.page
                        };
                    },
                    processResults: function(data) {
                        var mappedData = data.map(function(item) {
                            return {
                                id: item.id,
                                text: item.animal_name + " (" + item.codlab + " - " + item
                                    .especies + ")" // Adicionando codlab e especies ao nome
                            };
                        });

                        return {
                            results: mappedData
                        };
                    },
                    cache: true
                },
                minimumInputLength: 2,
            }).on('change', function(e) {
                var fullText = $(this).select2('data')[0].text;
                var animalName = fullText.split(" (")[
                    0]; // Isso pega a parte do texto antes de " (Codlab - Especies)"
                $('#mae_animal').val(animalName);
            });

        });
    </script>
@endsection
