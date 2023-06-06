@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row">
            <h3>IMPORTAR ARQUIVO TXT PARA ALELOS</h3>
        </div>
        <div class="card">
            <form action="{{ route('import.txt') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="formFile" class="form-label">Selecione o arquivo para importar</label>
                        <input class="form-control" name="file" type="file" id="formFile">
                    </div>
                </div>
                <div class="p-4">
                    <button class="btn btn-primary" id="enviar" type="submit">
                        importar
                    </button>
                    <div class="d-none" id="msg">
                        <span>Aguarde...importando alelos</span>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).on('click', '#enviar', function() {
            $('#msg').removeClass('d-none');
            $(this).attr('disabled', true);
            $(this).text('Aguarde...');

            $(this).parents('form').submit();
        });
    </script>
@endsection
