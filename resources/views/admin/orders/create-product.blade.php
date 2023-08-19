@extends('layouts.admin')
@section('content')
    <div class="container">
        <div class="card my-4">
            <div class="card-header">
                <h4>Criar ou adicionar produto</h4>
            </div>
            <div class="card-body">
                <div class="mb-5">
                    <div>
                        <h5>Inserir animal existente</h5>
                    </div>
                    <form action="{{ route('admin.order-update-animal') }}" method="post">
                        @csrf
                        <input type="hidden" value="{{ $order->id }}" name="order">
                        <div class="row">
                            <div class="col-md-8">
                                <label for="">Buscar animal </label>
                                <input type="search" class="form-control" id="search-input"
                                    placeholder="Digite o nome do animal..">
                                <div class="my-1 d-none" id="div-results">
                                    <select name="animal" class="form-control" id="resuts">

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">Adicionar</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="mt-4">
                    <h5>Cadastrar novo animal</h5>
                </div>
                <form action="{{ route('admin.order-add-animal-post') }}" method="post">
                    @csrf
                    <input type="hidden" name="order" value="{{ $order->id }}">
                    <div class="row">
                        <input type="hidden" name="id">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Nome do animal</label>
                                    <input type="text" name="animal_name" id="animal_name" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Registro do animal</label>
                                    <input type="text" class="form-control">
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
                                    <label for="exampleFormControlInput1" class="form-label">CHIP do animal</label>
                                    <input type="text" name="chip_number" id="register_number_brand"
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
                                    <select class="form-select species" name="especies">
                                        <option>Selecione e espécie</option>
                                        @foreach ($species as $specie)
                                            <option value="{{ $specie->description }}" data-specie="{{ $specie->id }}">
                                                {{ $specie->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Raça</label>
                                    <select class="form-select breeds" name="breed">
                                        <option>Selecione a raça</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Tipo de coleta</label>
                                    <select class="form-select" name="tipo">
                                        @foreach ($samples as $sample)
                                            <option value="{{ $sample->name }}">{{ $sample->name }}</option>
                                        @endforeach


                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Data de coleta</label>
                                    <input type="date" name="data_coleta" id="" class="form-control">
                                </div>
                            </div>
                            {{-- <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Data de recebimento</label>
                                    <input type="date" name="data_recebimento" id="" class="form-control">
                                </div>
                            </div> --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Data do chamado</label>
                                    <input type="date" name="data_laboratorio" id="" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Data de nascimento</label>
                                    <input type="date" name="birth_date" id="birth_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Idade</label>
                                    <input type="text" name="age" id="age" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <input class="form-check-input extra-verification" type="checkbox" value=""
                                    id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Verificação de parentesco
                                </label>
                            </div>
                            <div class="col-md-6 type-verify ">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Tipo de exame</label>
                                    <select class="form-select verify-type" name="verify_code">
                                        <option value="semverify">Selecione o tipo de verificação</option>
                                        <option value="ASIGN" data-verify="1">ASIGN</option>
                                        <option value="ASIMD" data-verify="2">ASIMD</option>
                                        <option value="ASIPD" data-verify="3">ASIPD</option>
                                        <option value="ASITR" data-verify="4">ASITR</option>
                                        <option value="BOVGN" data-verify="1">BOVGN</option>
                                        <option value="BOVMD" data-verify="2">BOVMD</option>
                                        <option value="BOVPD" data-verify="3">BOVPD</option>
                                        <option value="BOVTR" data-verify="4">BOVTR</option>
                                        <option value="CPGN" data-verify="1">CPGN</option>
                                        <option value="CAPMD" data-verify="2">CAPMD</option>
                                        <option value="CAPPD" data-verify="3">CAPPD</option>
                                        <option value="CAPTR" data-verify="4">CAPTR</option>
                                        <option value="EQUGN" data-verify="1">EQUGN</option>
                                        <option value="EQUMD" data-verify="2">EQUMD</option>
                                        <option value="EQUPD" data-verify="3">EQUPD</option>
                                        <option value="EQUTR" data-verify="4">EQUTR</option>
                                        <option value="MUAGN" data-verify="1">MUAGN</option>
                                        <option value="MUAMD" data-verify="2">MUAMD</option>
                                        <option value="MUAPD" data-verify="3">MUAPD</option>
                                        <option value="MUATR" data-verify="4">MUATR</option>
                                        <option value="PEGGN" data-verify="1">EQUPEGGN</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex">
                                <div class="row mx-2">
                                    <div class="col-md-12 pai my-2">
                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1" class="form-label">Numero de registro do
                                                pai</label>
                                            <input type="text" name="registro_pai" id="registro_pai"
                                                class="form-control registro_pai">
                                        </div>
                                    </div>
                                    <div class="col-md-12 pai my-2">
                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1" class="form-label">Espécie do
                                                pai</label>
                                            <select class="form-select" name="especie_pai">
                                                <option>Selecione a espécie</option>
                                                @foreach ($species as $specie)
                                                    <option value="{{ $specie->name }}">{{ $specie->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 pai my-2">
                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1" class="form-label">Nome do
                                                pai</label>
                                            <select class="js-pai-basic-single" name="pai_id">

                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mx-2">
                                    <div class="col-md-12 mae my-2">
                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1" class="form-label">Numero de registro da
                                                mãe</label>
                                            <input type="text" name="registro_mae" id="registro_mae"
                                                class="form-control registro_mae">
                                        </div>
                                    </div>
                                    <input type="hidden" name="pai" id="pai_animal">
                                    <input type="hidden" name="mae" id="mae_animal">
                                    <div class="col-md-12 mae my-2">
                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1" class="form-label">Espécie da
                                                mãe</label>
                                            <select class="form-select" name="especie_mae">
                                                <option>Selecione a espécie</option>
                                                @foreach ($species as $specie)
                                                    <option value="{{ $specie->name }}">{{ $specie->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mae my-2">
                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1" class="form-label">Nome da mãe</label>
                                            <select class="js-mae-basic-single" name="mae_id">

                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Salvar</button>
                            </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
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
                                id: item.id, // ID da opção
                                text: item.animal_name // Valor a ser exibido no Select2
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
                var selectedAnimalName = $(this).select2('data')[0].text;
                $('#pai_animal').val(selectedAnimalName);
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
                                id: item.id, // ID da opção
                                text: item.animal_name // Valor a ser exibido no Select2
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
                var selectedAnimalName = $(this).select2('data')[0].text;
                $('#mae_animal').val(selectedAnimalName);
            });
            $(document).ready(function() {
                $('#search-input').on('keyup', function() {
                    var query = $(this).val();
                    console.log(query.length)

                    $.ajax({
                        url: '{{ route('get.registros.animais') }}',
                        type: 'GET',
                        data: {
                            query: query
                        },
                        dataType: 'json',
                        success: function(data) {
                            // Faça algo com os resultados da busca
                            $('#resuts').empty();
                            $.each(data, function(index, item) {

                                $('#div-results').removeClass('d-none');
                                $('#resuts').append(
                                    `<option value="${item.id}">${item.animal_name}</option>`
                                )
                            });
                            if (query.length <= 1) {
                                $('#div-results').addClass('d-none');
                            }
                        }
                    });
                });
            });

            $('#birth_date').change(function() {
                var birth_date = $('#birth_date').val();
                var date = new Date(birth_date);
                var today = new Date();
                var age = Math.floor((today - date) / (365.25 * 24 * 60 * 60 * 1000));
                $('#age').val(age);
            });

            $('.species').change(function() {
                var specie = $(this).find(':selected').data('specie');
                $.ajax({
                    url: '/get-breeds/' + specie,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        console.log(response)
                        var len = 0;
                        if (response != null) {
                            len = response.length;
                        }
                        if (len > 0) {
                            $(".breeds").empty();
                            for (var i = 0; i < len; i++) {
                                var id = response[i].id;
                                var name = response[i].name;
                                var option = "<option value='" + name + "'>" + name +
                                    "</option>";
                                $(".breeds").append(option);
                            }
                        }
                    }
                });
            });
            $('.extra-verification').change(function() {
                if ($(this).is(':checked')) {
                    $('.extra-verification').val('1');
                    $('.type-verify').removeClass('d-none');

                } else {
                    $('.extra-verification').val('0');
                    $('.type-verify').addClass('d-none');
                }
            });
            $('.type-verify').change(function() {
                var type = $(this).find(':selected').data('verify');
                if (type == 1) {
                    $('.pai').addClass('d-none');
                    $('.mae').addClass('d-none');
                }
                if (type == 2) {
                    $('.mae').removeClass('d-none');
                    $('.pai').addClass('d-none');
                }
                if (type == 3) {
                    $('.mae').addClass('d-none');
                    $('.pai').removeClass('d-none');
                }
                if (type == 4) {
                    $('.pai').removeClass('d-none');
                    $('.mae').removeClass('d-none');
                }

            });
            $('.registro_pai').on('blur', function() {
                var registro = $(this).val();
                $.ajax({
                    url: '/get-pai/',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        registro: registro
                    },
                    success: function(response) {
                        console.log(response.animal_name)
                        $('#pai').val(response.animal_name);

                    }
                });
            });
            $('.registro_mae').on('blur', function() {
                var registro = $(this).val();
                $.ajax({
                    url: '/get-mae/',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        registro: registro
                    },
                    success: function(response) {
                        console.log(response.animal_name)
                        $('#mae').val(response.animal_name);

                    }
                });
            });
            $(document).on('click', '.edit', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: '/order-edit-animal/' + id,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: id
                    },
                    success: (data) => {
                        console.log(data.animal)
                        if (id) {
                            for (i in data.animal) {
                                $('#edit-app').find(`[name="${i}"]`).val(data.animal[i]);
                            }
                        }

                    }
                });

            });


        });
    </script>
@endsection
