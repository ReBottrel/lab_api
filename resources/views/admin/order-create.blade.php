@extends('layouts.admin')
@section('content')
    <div class="container">
        <div class="card my-4">
            <div class="card-header">
                <h4>Criar pedido</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('order.store.painel') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Nome do proprietario</label>
                            <select class="js-owner-basic-single" name="owner">

                            </select>
                            <div>
                                <h6>*Obs se não encontro o proprietário <a href="{{ route('owner.create') }}">clique
                                        aqui</a> para cadastrar um novo</h6>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Nome do técnico</label>
                            <select class="js-tecnico-basic-single" name="tecnico">

                            </select>
                            <div>
                                <h6>*Obs se não encontro o técnico <a href="{{ route('techinical.create') }}">clique
                                        aqui</a> para
                                    cadastrar um novo</h6>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Data de recebimento</label>
                                <input type="date" class="form-control" name="collection_date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Tipo de exame</label>
                                <select class="form-select tipo-exame" id="tipo-exame" name="tipo"
                                    aria-label="Default select example">
                                    <option selected>Selecione o tipo de exame</option>
                                    <option value="1">DNA Genotipagem</option>
                                    <option value="2">DNA Homozigose</option>
                                    <option value="3">DNA Beta Caseína</option>
                                    <option value="4">Sorologia</option>
                                    <option value="5">Verificação de parentesco</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="exampleFormControlInput1" class="form-label">Parceiro</label>
                            <select class="form-select tipo-exame"  name="parceiro"
                                aria-label="Default select example">
                                <option selected>Selecione o parceiro</option>
                                @foreach ($parceiros as $parceiro)
                                    <option value="{{ $parceiro->nome }}">{{ $parceiro->nome }}</option>
                                @endforeach


                            </select>
                        </div>
                        <div class="col-md-12 my-4 ">
                            <button type="submit" class="btn btn-primary create-order">Próximo</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('.js-owner-basic-single').select2({
                placeholder: 'Selecione o proprietário',
                width: '100%',
                ajax: {
                    url: "{{ route('get.dados.owner') }}",
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
                                text: item.owner_name // Valor a ser exibido no Select2
                            };
                        });

                        return {
                            results: mappedData
                        };
                    },
                    cache: true
                },
                minimumInputLength: 2,
            });

            $('.js-tecnico-basic-single').select2({
                placeholder: 'Selecione o técnico',
                width: '100%',
                ajax: {
                    url: "{{ route('get.dados.tecnico') }}",
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
                                text: item.professional_name // Valor a ser exibido no Select2
                            };
                        });

                        return {
                            results: mappedData
                        };
                    },
                    cache: true
                },
                minimumInputLength: 2,
            });

        });

        $(document).on('click', '.create-order', function(e) {
            e.preventDefault();
            var tipo = $('#tipo-exame').val();
            var proprietario = $('.js-owner-basic-single').val();
            var tecnico = $('.js-tecnico-basic-single').val();
            console.log(tipo)
            if (tipo == 'Selecione o tipo de exame' || proprietario === null || tecnico === null) {
                Swal.fire(
                    'Atenção',
                    'Selecione um tipo de exame, proprietário e técnico',
                    'warning'
                );
            } else {
                $('form').submit();
            }
        });
    </script>
@endsection
