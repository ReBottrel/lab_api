@extends('layouts.admin')

@section('content')
    <div id="editar-produto">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h1>Criar Animal</h1>
                </div>
                <div class="card-body">
                    <form id="animal-form" action="{{ route('animais.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Nome do animal</label>
                                    <input type="text" name="animal_name" id="animal_name" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Codlab</label>
                                    <input type="text" name="codlab" id="codlab" class="form-control">
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
                                        <option value="EQUINO_PEGA">EQUINO PÊGA</option>
                                        <option value="EQUINA_TKY">EQUINA TKY</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Raça</label>
                                    <select class="form-select" name="breed">
                                        <option value="MANGALARGA">MANGALARGA MARCHADOR</option>
                                        <option value="PÊGA">PÊGA</option>
                                        <option value="QUARTO DE MILHO">QUARTO DE MILHA</option>
                                        <option value="PAMPA">PAMPA</option>
                                        <option value="DESCONHECIDA">DESCONHECIDA</option>
                                        <option value="NELORE">NELORE</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Pelagem</label>
                                    <select class="form-select" name="fur">
                                        @foreach ($pelagens as $item)
                                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                                        @endforeach


                                    </select>
                                </div>
                            </div>

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
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Numero do chip</label>
                                    <input type="text" name="chip_number" id="chip_number" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Numero de registro do
                                        pai</label>
                                    <input type="text" name="registro_pai" id="registro_pai" class="form-control">
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
                                    <input type="text" name="registro_mae" id="registro_mae" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Nome da mãe</label>
                                    <select class="js-mae-basic-single" name="mae_id">

                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="mae" id="pai_animal">
                            <input type="hidden" name="pai" id="mae_animal">
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

            $('.js-pai-basic-single').select2({
                placeholder: 'Selecione o pai',
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
                placeholder: 'Selecione a mãe',
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

            $('#animal-form').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var url = form.attr('action');
                var type = form.attr('method');
                var data = form.serialize();
                $.ajax({
                    url: url,
                    type: type,
                    data: data,
                    dataType: 'json',
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Aguarde...',
                            html: 'Salvando dados',
                            allowOutsideClick: false,
                            showCancelButton: false,
                            showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading()
                            },
                        });
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Sucesso!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'Fechar',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "{{ route('animais') }}";
                            }
                        })
                    },
                    error: function(response) {

                    }
                });
            });

        })
    </script>
@endsection
