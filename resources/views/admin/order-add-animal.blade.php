@extends('layouts.admin')
@section('content')
    <div class="container">
        <div class="card my-4">
            <div class="card-header">
                <h4>Criar pedido add produto</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Criador: {{ $order->creator }}</h4>
                    </div>
                    <div class="col-md-6">
                        <h4>Técnico: {{ $order->technical_manager }}</h4>
                    </div>
                </div>
                <div class="my-5">
                    <div class="my-3">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Adicionar
                            Produto</button>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Produto</th>
                                <th scope="col">Numero de registro</th>
                                <th scope="col">Sexo</th>
                                <th scope="col">Acões</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($animals as $item)
                                <tr>
                                    <th scope="row">{{ $item->animal_name }}</th>
                                    <td>{{ $item->register_number_brand }}</td>
                                    <td>{{ $item->sex }}</td>
                                    <td>
                                        <div>
                                            <a href="{{ route('admin.produto.delete', $item->id) }}"> <button
                                                    class="btn btn-danger">Apagar</button></a>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">Nenhum produto adicionado ao pedido</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($animals->count() > 0)
                    <div class="text-center">
                        <a href="{{ route('order.end.painel', $order->id) }}"> <button
                                class="btn btn-success text-white">Finalizar pedido</button></a>
                    </div>
                @endif

            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Adicionar produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.order-add-animal-post') }}" method="post">
                    @csrf
                    <input type="hidden" name="order" value="{{ $order->id }}">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Nome do animal</label>
                                    <input type="text" name="animal_name" id="animal_name" class="form-control">
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
                                            <option value="{{ $specie->name }}" data-specie="{{ $specie->id }}">
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
                                    <label for="exampleFormControlInput1" class="form-label">Data de coleta</label>
                                    <input type="date" name="" id="" class="form-control">
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
                            <div class="col-md-6 type-verify d-none">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Tipo de verificação</label>
                                    <select class="form-select verify-type" name="extra_verify">
                                        <option>Selecione a verificação</option>
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
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 pai">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Numero de registro do
                                        pai</label>
                                    <input type="text" name="registro_pai" id="registro_pai" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6 pai">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Espécie do pai</label>
                                    <select class="form-select" name="especie_pai">
                                        <option>Selecione a espécie</option>
                                        @foreach ($species as $specie)
                                            <option value="{{ $specie->name }}">{{ $specie->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 pai">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Nome do pai</label>
                                    <input type="text" name="pai" id="pai" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6 mae">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Numero de registro da
                                        mãe</label>
                                    <input type="text" name="registro_mae" id="registro_mae" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6 mae">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Espécie da mãe</label>
                                    <select class="form-select" name="especie_mae">
                                        <option>Selecione a espécie</option>
                                        @foreach ($species as $specie)
                                            <option value="{{ $specie->name }}">{{ $specie->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mae">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Nome da mãe</label>
                                    <input type="text" name="mae" id="mae" class="form-control">
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
        });
    </script>
@endsection
