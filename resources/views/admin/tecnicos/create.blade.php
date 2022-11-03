@extends('layouts.admin')
@section('content')
    <div class="container">
        <div class="card my-4">
            <div class="card-body">
                <div>
                    <h4>Cadastrar Técnico</h4>
                </div>
                <form action="{{ route('techinical.store') }}" id="technical-forms" method="post">
                    <input type="hidden" name="order_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Nome do técnico</label>
                                <input type="text" name="professional_name" class="form-control" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">CPF/CNPJ</label>
                                <input type="text" name="document" id="cpfcnpj" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">CEP</label>
                                <input type="text" id="cep" name="zip_code" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Endereço</label>
                                <input type="text" name="address" class="form-control" id="address">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Número</label>
                                <input type="text" name="number" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Complemento</label>
                                <input type="text" name="complement" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">UF</label>
                                <input type="text" name="state" class="form-control" id="uf">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Bairro</label>
                                <input type="text" name="district" class="form-control" id="bairro">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Cidade</label>
                                <input type="text" name="city" class="form-control" id="cidade">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Celular</label>
                                <input type="text" name="cell" id="fone" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Telefone</label>
                                <input type="text" name="fone" id="fone" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Número da portaria mormo</label>
                                <input type="text" name="nr_portaria_mormo" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Registro profissional</label>
                                <input type="text" name="registro_profissional" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Conselho</label>
                                <input type="text" name="conselho" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3 text-center">
                                <button class="btn btn-success" id="technical-saves">Salvar</button>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).on('click', '#technical-saves', function() {
            var btn = $(this);
            var form = $('#technical-forms').serialize();
            var url = $('#technical-forms').attr('action');
            btn.html('<div class="spinner-border text-light" role="status"></div>');
            btn.prop('disabled', true);
            $('#technical-forms').find('input').prop('disabled', true);

            $.ajax({
                url: url,
                type: 'POST',
                data: form,
                success: (data) => {
                    console.log(data);
                    Swal.fire(
                        'Sucesso!',
                        'Técnico cadastrado com sucesso!',
                        'success'
                    )
                    window.location.reload();

                    // window.location.href = data;
                },
                error: (err) => {
                    // console.log(err);
                    btn.html('Salvar');
                    btn.prop('disabled', false);
                    $('#technical-forms').find('input').prop('disabled', false);

                    Swal.fire({
                        icon: 'error',
                        title: err.responseJSON.invalid
                    });
                }
            });
        });
    </script>
@endsection
