@extends('layouts.veterinario')

@section('content')
    @include('veterinario.includes.back-button')
    <div class="">
        <div class="cad-animal">
            <form action="{{ route('animal.store') }}" method="post">
                <input type="hidden" name="pedido_id" value="{{ $pedido }}">
                @csrf
                <fieldset>
                    <div class="cad-animal-content">
                        <div class="cad-animal-content-title">
                            <h2>Informações do animal</h2>
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Nome do animal</label>
                            <input type="text" class="form-control" name="animal_name" required>
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Numero do registro</label>
                            <input type="text" class="form-control" name="register_number_brand" required>
                        </div>
                    </div>
                    <div class="my-3 text-end">
                        <button type="button" class="btnNext btn btn-altvet_id-1">Próximo</button>
                    </div>
                </fieldset>
                <fieldset class="hidden">
                    <div class="cad-animal-content">
                        <div class="cad-animal-content-title">
                            <h2>Informações do animal</h2>
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Espécie</label>
                            <select name="especies" id="" class="form-control" required>
                                @foreach ($especies as $especie)
                                    <option value="{{ $especie->name }}">{{ $especie->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Raça</label>
                            <select name="breed" id="" class="form-control" required>
                                @foreach ($breeds as $raca)
                                    <option value="{{ $raca->name }}">{{ $raca->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Sexo</label>
                            <select name="sex" id="" class="form-control" required>
                                <option value="M">Macho</option>
                                <option value="F">Fêmea</option>
                            </select>
                        </div>

                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Data de nascimento</label>
                            <input type="date" class="form-control" name="birth_date" id="birth_date" required>
                        </div>
                    </div>
                    <div class="buttons">
                        <div class="my-3 text-end">
                            <button type="button" class="btnPrev btn btn-alt-1">Anterior</button>
                        </div>
                        <div class="my-3 text-end">
                            <button type="button" class="btnNext btn btn-alt-1">Próximo</button>
                        </div>
                    </div>


                </fieldset>
                <fieldset class="hidden">
                    <div class="cad-animal-content">
                        <div class="cad-animal-content-title">
                            <h2>Informações do animal</h2>
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Local onde se encontra</label>
                            <input type="text" class="form-control" name="animal_location" required>
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Estado</label>
                            <select name="state" id="state" class="form-control" required>
                            </select>
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Municipio</label>
                            <select name="city" id="city" class="form-control" required>
                            </select>
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Cadastro OESA</label>
                            <input type="text" class="form-control" name="oesa_cad" required>
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Numero de animais existente na
                                propriedade</label>
                            <input type="text" class="form-control" name="number_existing_equines" required>
                        </div>

                    </div>

                    <div class="buttons">
                        <div class="my-3 text-end">
                            <button type="button" class="btnPrev btn btn-alt-1">Anterior</button>
                        </div>
                        <div class="my-3 text-end">
                            <button type="button" class="btnNext btn btn-alt-1">Próximo</button>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="hidden">
                    <div class="cad-animal-content">
                        <div class="cad-animal-content-title">
                            <h2>Informações do animal</h2>
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Data da coleta</label>
                            <input type="date" class="form-control" name="collect_date" required>
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Numero da requisição AIE</label>
                            <input type="text" class="form-control" name="numero_aie" required>
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Numero da requisição MORMO</label>
                            <input type="text" class="form-control" name="numero_mormo" required>
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Portaria de habilitação</label>
                            <input type="text" class="form-control" name="portaria_habilitacao" required>
                        </div>
                        <div class="buttons">
                            <div class="my-3 text-end">
                                <button type="button" class="btnPrev btn btn-alt-1">Anterior</button>
                            </div>
                            <div class="my-3 text-end">
                                <button type="button" class="btnNext btn btn-alt-1">Próximo</button>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="hidden">
                    <div class="cad-animal-content">
                        <div class="cad-animal-content-title">
                            <h2>Informações do animal</h2>
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Pelagem</label>
                            <select name="fur" id="" class="form-control" required>
                                @foreach ($furs as $fur)
                                    <option value="{{ $fur->name }}">{{ $fur->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Utilidade</label>
                            <select class="form-select" name="utility" aria-label="Default select example" required>
                                <option selected>Selecione </option>
                                <option value="esporte">Esporte</option>
                                <option value="exibição">Exibição</option>

                            </select>
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Classificação</label>
                            <select class="form-select" name="classification"aria-label="Default select example" required>
                                <option selected>Selecione</option>
                                <option value="h">h</option>
                                <option value="hb">hb</option>
                            </select>
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Descrição e Observação</label>
                            <textarea type="text" class="form-control" name="description" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="buttons">
                        <div class="my-3 text-end">
                            <button type="button" class="btnPrev btn btn-alt-1">Anterior</button>
                        </div>
                        <div class="my-3 text-end">
                            <button type="submit" class="btnNext btn btn-alt-1">Próximo</button>
                        </div>
                    </div>
                </fieldset>

            </form>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $(document).on('blur', '#idade', function() {
                $('#birth_date').prop('disabled', true);
            });
            $(document).on('blur', '#birth_date', function() {
                $('#idade').prop('disabled', true);
            });
            $.ajax({
                url: '{{ route('get.states') }}',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#state').empty();
                    $.each(data, function(index, val) {
                        $('#state').append('<option value="' + val.sigla + '">' + val
                            .nome +
                            '</option>');
                    });
                }
            });


            $(document).on('change', '#state', function() {
                var state_id = $(this).val();
                $.ajax({
                    url: '{{ route('get.cities') }}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        state_id: state_id
                    },
                    success: function(data) {
                        $('#city').empty();
                        $.each(data, function(index, val) {
                            $('#city').append('<option value="' + val.nome + '">' + val
                                .nome +
                                '</option>');
                        });
                    }
                });
            });
            $('.btnNext').click(function() {
                var $this = $(this);
                var $current = $this.parents('fieldset');
                var $next = $current.next('fieldset');

                var $inputs = $current.find(
                    'input[required], select[required], textarea[required]'
                    ); // Seleciona todos os inputs, selects e textareas obrigatórios dentro do fieldset atual

                // Verifica se algum dos inputs obrigatórios não foi preenchido
                var isInvalid = false;
                $inputs.each(function() {
                    if (!$(this).val().trim()) {
                        isInvalid = true;
                        return false; // Sai do loop se encontrar um campo não preenchido
                    }
                });

                if (isInvalid) {
                    alert(
                    'Por favor, preencha todos os campos obrigatórios.'); // Exibe uma mensagem de alerta
                    return; // Impede a navegação para a próxima seção
                }

                $current.animate({
                    opacity: 0
                }, 500, function() {
                    $current.addClass('hidden');
                    $next.removeClass('hidden');
                    $next.animate({
                        opacity: 1
                    }, 500);
                });
            });

            $('.btnPrev').click(function() {
                var $this = $(this);
                var $current = $this.parents('fieldset');
                var $prev = $current.prev('fieldset');

                $current.animate({
                    opacity: 0
                }, 500, function() {
                    $current.addClass('hidden');
                    $prev.removeClass('hidden');
                    $prev.animate({
                        opacity: 1
                    }, 500);
                });
            });
        });
    </script>
@endsection
