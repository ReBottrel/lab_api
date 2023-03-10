@extends('layouts.veterinario')

@section('content')
    @include('veterinario.includes.back-button')
    <div class="">
        <div class="cad-animal">
            <form action="">
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
                        <button type="button" class="btnNext btn btn-alt-1">Próximo</button>
                    </div>
                </fieldset>
                <fieldset class="hidden">
                    <div class="cad-animal-content">
                        <div class="cad-animal-content-title">
                            <h2>Informações do animal</h2>
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Espécie</label>
                            <input type="text" class="form-control" name="animal_name">
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Raça</label>
                            <input type="text" class="form-control" name="register_number_brand">
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Sexo</label>
                            <select name="" id="" class="form-control">
                                <option value="">Macho</option>
                                <option value="">Fêmea</option>
                            </select>
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Idade</label>
                            <input type="text" class="form-control" name="register_number_brand">
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Data de nascimento</label>
                            <input type="date" class="form-control" name="register_number_brand">
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
                            <label for="exampleFormControlInput1" class="form-label">Pelagem</label>
                            <input type="text" class="form-control" name="animal_name">
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Observação</label>
                            <textarea type="text" class="form-control" name="register_number_brand" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="buttons">
                        <div class="my-3 text-end">
                            <button type="button" class="btnPrev btn btn-alt-1">Anterior</button>
                        </div>
                        <div class="my-3 text-end">
                            <button type="button" class="btnStep btn btn-alt-1">Próximo</button>
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
            $(document).on('click', '.btnStep', function(){
                window.location.href = "{{ route('resenha.step1') }}";
            });
        });
    </script>
@endsection
