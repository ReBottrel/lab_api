@extends('layouts.veterinario')
@section('content')
    @include('layouts.partials.vet-top')
    <div class="lt-2">
        <div class="container">
            <div class="my-4">
                <h3>Selecione o proprietário</h3>
                <select class="js-example-basic-single" name="state">
                </select>
                <div class="my-3">
                    <button class="btn btn-primary">CONTINUAR</button>
                </div>
            </div>
            <div class="mt-4">
                <h3>Ou cadastrar novo proprietário</h3>
                <div class="">
                    <form action="">
                        <fieldset>
                            <div class="cad-animal-content">
                                <div class="cad-animal-content-title">
                                    <h5>Informações do proprietario</h5>
                                </div>
                                <div class="mb-3 cad-animal-content-input">
                                    <label for="exampleFormControlInput1" class="form-label">Nome do proprietário</label>
                                    <input type="text" class="form-control" name="animal_name">
                                </div>
                                <div class="mb-3 cad-animal-content-input">
                                    <label for="exampleFormControlInput1" class="form-label">Documento (CPF/CNPJ)</label>
                                    <input type="text" class="form-control" name="register_number_brand">
                                </div>
                                <div class="mb-3 cad-animal-content-input">
                                    <label for="exampleFormControlInput1" class="form-label">Email</label>
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
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {

            $('.btn-select').click(function() {
                window.location.href = "{{ route('vet.select') }}";
            });
            $('.btn-create').click(function() {
                window.location.href = "{{ route('animal.create') }}";
            });
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
