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
                                    <input type="text" class="form-control" name="email">
                                </div>
                            </div>
                            <div class="buttons">
                                <div class="my-3 text-end">
                                    <button type="button" class="btnNext btn btn-alt-1">Próximo</button>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="hidden">
                            <div class="cad-animal-content">
                                <div class="cad-animal-content-title">
                                    <h5>Contatos do proprietário</h5>
                                </div>
                                <div class="mb-3 cad-animal-content-input">
                                    <label for="exampleFormControlInput1" class="form-label">Telefone</label>
                                    <input type="text" class="form-control" name="animal_name">
                                </div>
                                <div class="mb-3 cad-animal-content-input">
                                    <label for="exampleFormControlInput1" class="form-label">Whatsapp</label>
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
                        <fieldset class="hidden">
                            <div class="cad-animal-content">
                                <div class="cad-animal-content-title">
                                    <h5>Endereço do proprietário</h5>
                                </div>
                                <div class="mb-3 cad-animal-content-input">
                                    <label for="zip_code" class="form-label">CEP</label>
                                    <input type="text" class="form-control" name="zip_code" id="zip_code">
                                </div>

                                <div class="mb-3 cad-animal-content-input">
                                    <label for="address" class="form-label">Endereço</label>
                                    <input type="text" class="form-control" name="address" id="address">
                                </div>

                                <div class="mb-3 cad-animal-content-input">
                                    <label for="number" class="form-label">Número</label>
                                    <input type="text" class="form-control" name="number" id="number">
                                </div>

                                <div class="mb-3 cad-animal-content-input">
                                    <label for="complement" class="form-label">Complemento</label>
                                    <input type="text" class="form-control" name="complement" id="complement">
                                </div>

                                <div class="mb-3 cad-animal-content-input">
                                    <label for="district" class="form-label">Bairro</label>
                                    <input type="text" class="form-control" name="district" id="district">
                                </div>
                                <div class="mb-3 cad-animal-content-input">
                                    <label for="city" class="form-label">Cidade</label>
                                    <input type="text" class="form-control" name="city" id="city">
                                </div>

                            </div>
                            <div class="buttons">
                                <div class="my-3 text-end">
                                    <button type="button" class="btnPrev btn btn-alt-1">Anterior</button>
                                </div>
                                <div class="my-3 text-end">
                                    <button type="button" class="btnNext btn btn-alt-1">Finalizar</button>
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
