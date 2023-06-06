@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="mb-3 col-6">
            <label for="exampleFormControlInput1" class="form-label">Buscar animal pelo nome</label>
            <input type="text" class="form-control" id="buscar-nome">
        </div>
        <div id="animal-info">

        </div>
        <div id="animalForm" class="d-none">
            <form id="form">
                <input type="hidden" name="animal_name">
                <input type="hidden" id="especie">
                @csrf
                <div class="card px-2">
                    <div id="marcadores">
                 
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary">SALVAR</button>
                    </div>
            </form>

        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $(document).on('blur', '#buscar-nome', function() {
                var name = $(this).val();
                if (name != '') {
                    $.ajax({
                        url: "{{ route('animais.buscar') }}",
                        method: "POST",
                        data: {
                            name: name,
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(data) {
                            console.log(data);
                            $('#animal-info').fadeIn();
                            $('#animal-info').html(`
                            <p>Nome do animal: ${data.animal.animal_name}</p>
                            `);
                            $('input[name="animal_name"]').val(data.animal.animal_name);
                            $('#animalForm').removeClass('d-none');
                            $('#marcadores').html(data.view);
                        }
                    });
                }
            });


        });
        $(document).ready(function() {
            $('form').submit(function(e) {
                e.preventDefault(); // Evita que o formulário seja enviado normalmente

                // Obtém os dados do formulário
                var formData = $(this).serialize();

                // Envia a solicitação AJAX
                $.ajax({
                    url: '{{ route('alelos.store.custom') }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // Manipule a resposta do servidor aqui
                        console.log(response);
                        if (response.success == 'ok') {
                            Swal.fire({
                                title: 'Sucesso!',
                                text: 'Alelos criado com sucesso!',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href =
                                        "{{ route('admin') }}";
                                }
                            });
                        }
                        if (response.error == 'existe') {
                            Swal.fire({
                                title: 'Erro!',
                                text: 'Alelos já importados!',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href =
                                        "{{ route('alelos.create') }}";
                                }
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        // Manipule os erros da solicitação aqui
                        console.error(error);
                    }
                });
            });
        });
    </script>
@endsection
