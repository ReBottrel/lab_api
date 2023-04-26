@extends('layouts.veterinario')
@section('content')
    @include('layouts.partials.vet-top')
    <div class="mt-5">
        <div class="container">
            <div id="output"></div>
            <div class="mb-3">
                <select class="js-example-basic-multiple" id="select-exam" name="exames[]" multiple="multiple">
                    @foreach ($exames as $exame)
                        <option data-value="{{ $exame->value }}" value="{{ $exame->id }}">{{ $exame->title }} |
                            {{ 'R$ ' . number_format($exame->value, 2, ',', '.') }} </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="">Data da coleta</label>
                <input type="text" class="form-control" name="" id="data">
                <input type="hidden" name="" id="total-input">
                <input type="hidden" name="" id="pedido" value="{{ $pedido->id }}">
            </div>
            <div>
                <h3 id="total">Total: </h3>
            </div>
            <div class="text-center mt-5">
                <button class="btn btn-alt-2 d-none" id="create-order">FINALIZAR</button>
            </div>
            <div class="my-5">
                <h2>Informações do animal</h2>
                <p>Nome: {{ $animal->animal_name }}</p>
                <p>Espécie: {{ $animal->specie }}</p>
                <p>Raça: {{ $animal->breed }}</p>

            </div>

        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2({
                placeholder: "Selecione o exame",
                allowClear: true,
                width: '100%',
            });
        });
        $(document).ready(function() {
            var total = 0;
            $('#select-exam').change(function() {
                var exames = $('#select-exam').val();
                var total = 0;
                exames.forEach(element => {
                    var value = $('option[value=' + element + ']').data('value');
                    total += value;
                });
                if (total > 0) {
                    $('#create-order').removeClass('d-none');
                } else {
                    $('#create-order').addClass('d-none');
                }
                $('#total').html('Total: R$ ' + total.toFixed(2).replace('.', ','));
                $('#total-input').val(total);
            });
            $('#create-order').click(function() {
                var exames = $('#select-exam').val();
                var total = $('#total-input').val();
                var pedido = $('#pedido').val();
                var data = $('#data').val();
                $.ajax({
                    url: "{{ route('vet.order.finish') }}",
                    type: "POST",
                    data: {
                        exames: exames,
                        total: total,
                        pedido: pedido,
                        data: data,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success == true) {
                            $('#output').html(
                                '<div class="alert alert-success" role="alert">Pedido finalizado com sucesso!</div>'
                                );
                            setTimeout(function() {
                                window.location.href = "{{ route('vet.index') }}";
                            }, 2000);
                        }

                    }
                });
            });
        });
    </script>
@endsection
