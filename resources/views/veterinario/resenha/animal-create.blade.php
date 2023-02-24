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
                            <label for="exampleFormControlInput1" class="form-label">Nome do animal</label>
                            <input type="text" class="form-control" name="animal_name">
                        </div>
                        <div class="mb-3 cad-animal-content-input">
                            <label for="exampleFormControlInput1" class="form-label">Numero do registro</label>
                            <input type="text" class="form-control" name="register_number_brand">
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
            </form>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('.btnNext').click(function() {
                $('fieldset').slideToggle('slow', function() {
                    $(this).toggleClass('hidden');
                });
            });
            $('.btnPrev').click(function() {
                $('fieldset').slideToggle('slow', function() {
                    $(this).toggleClass('hidden');
                });
            });
        });
    </script>
@endsection
