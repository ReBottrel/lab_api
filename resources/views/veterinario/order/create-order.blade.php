@extends('layouts.veterinario')
@section('content')
    @include('layouts.partials.vet-top')
    <div class="mt-5">
        <div class="container">
            <div id="output"></div>
            <div class="mb-3">
                <select class="js-example-basic-single" id="select-exam" name="exame">
                    <option value="">Selecione o exame</option>
                    @foreach ($exames as $exame)
                        <option data-value="{{ $exame->value }}" value="{{ $exame->id }}">{{ $exame->title }} |
                            {{ 'R$ ' . number_format($exame->value, 2, ',', '.') }} </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                {{-- <label for="">Data da coleta</label>
                <input type="text" class="form-control" name="" id="data"> --}}
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
            $('.js-example-basic-single').select2({
                placeholder: "Selecione o exame",
                allowClear: true,
                width: '100%',
            });
        });
        $(document).ready(function() {
            var total = 0;
            $('#select-exam').change(function() {
                var exame = $('#select-exam').val();
                var total = 0;
                if (!exame) {
                    $('#create-order').addClass('d-none');
                } else {
                    $('#create-order').removeClass('d-none');
                }
                var value = $('#select-exam option:selected').attr('data-value');
                total += parseFloat(value);

                $('#total').html('Total: R$ ' + total.toFixed(2).replace('.', ','));
                $('#total-input').val(total);
            });
            $('#create-order').click(function() {
                var exame = $('#select-exam').val();
                var total = $('#total-input').val();
                var pedido = $('#pedido').val();
                var data = $('#data').val();
                $.ajax({
                    url: "{{ route('vet.order.finish') }}",
                    type: "POST",
                    data: {
                        exame: exame,
                        total: total,
                        pedido: pedido,
                        data: data,
                        _token: "{{ csrf_token() }}"
                    },
                    beforeSend: function() {
                        $('#output').html(
                            '<div class="alert alert-info" role="alert">Finalizando resenha...</div>'
                        );
                        $("#create-order").prop("disabled", true);
                    },
                    success: function(response) {
                        if (response.success == true) {
                            $('#output').html(
                                '<div class="alert alert-success" role="alert">Resenha finalizada com sucesso!</div>'
                            );
                            setTimeout(function() {
                                window.location.href = "{{ url('/vet/finish') }}" +
                                    '/' + pedido;
                            }, 2000);
                        }

                    }
                });
            });
        });
    </script>
@endsection
