@extends('layouts.veterinario')

@section('content')
    @include('veterinario.includes.back-button')
    <div class="">
        <div class="cad-animal">
            <form action="{{ route('vet.animal.store') }}" method="post">

                @csrf
                <fieldset>
                    <div class="cad-animal-content">
                        <div class="cad-animal-content-title">
                            <h2>Informações do animal</h2>
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Nome do animal</label>
                            <input type="text" class="form-control" name="animal_name">
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Numero do registro</label>
                            <input type="text" class="form-control" name="register_number_brand">
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
                            <select name="especies" id="" class="form-control">
                                @foreach ($especies as $especie)
                                    <option value="{{ $especie->id }}">{{ $especie->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Raça</label>
                            <select name="raca" id="" class="form-control">
                                @foreach ($breeds as $raca)
                                    <option value="{{ $raca->id }}">{{ $raca->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Sexo</label>
                            <select name="sex" id="" class="form-control">
                                <option value="M">Macho</option>
                                <option value="F">Fêmea</option>
                            </select>
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Idade</label>
                            <input type="text" class="form-control" name="age" id="idade">
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Data de nascimento</label>
                            <input type="date" class="form-control" name="birth_date" id="birth_date">
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
                            <input type="text" class="form-control" name="animal_name">
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Estado</label>
                            <select name="state" id="state" class="form-control">
                            </select>
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Estado</label>
                            <select name="city" id="city" class="form-control">
                            </select>
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Cadastro OESA</label>
                            <input type="text" class="form-control" name="animal_name">
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Numero de animais existente na
                                propriedade</label>
                            <input type="text" class="form-control" name="animal_name">
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
                            <input type="date" class="form-control" name="collect_date">
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Numero da requisição AIE</label>
                            <input type="text" class="form-control" name="numero_aie">
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Numero da requisição MORMO</label>
                            <input type="text" class="form-control" name="numero_mormo">
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
                            <select name="fur" id="" class="form-control">
                                @foreach ($furs as $fur)
                                    <option value="{{ $fur->name }}">{{ $fur->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Descrição e Observação</label>
                            <textarea type="text" class="form-control" name="description" rows="3"></textarea>
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
                            <h2>Informações do proprietário</h2>
                        </div>
                        <select class="js-example-basic-single" name="owner_id">
                            @foreach ($owners as $owner)
                                <option value="{{ $owner->id }}">{{ $owner->owner_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="buttons">
                        <div class="my-3 text-end">
                            <button type="button" class="btnPrev btn btn-alt-1">Anterior</button>
                        </div>
                        <div class="my-3 text-end">
                            <button type="submit" class="btnStep btn btn-alt-1">Próximo</button>
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
