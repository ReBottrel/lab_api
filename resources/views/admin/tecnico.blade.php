@extends('layouts.admin')
@section('content')
    <div class="container">
        <div class="card my-4">
            <div class="card-body">
                <div>
                    <h4>Buscar Técnico</h4>
                </div>
                <form action="{{ route('technical.add') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $order->id }}">
                    <div class="row">
                        <div class="col-md-8">
                            <select class="js-example-basic-single" name="tecnico">
                                @foreach ($tecnicos as $tecnico)
                                    <option value="{{ $tecnico->id }}">{{ $tecnico->professional_name }}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Continuar</button>
                        </div>
                    </div>
                </form>
                <div>
                    <h6>*Obs se não encontro o técnico <a href="{{ route('techinical.create') }}">clique aqui</a> para
                        cadastrar um novo</h6>
                </div>
            </div>
        </div>
        <div class="card my-4">
            <div class="card-body">
                <div>
                    <h4>Técnico do chamado: {{ $order->technical_manager }}</h4>
                </div>
                @if ($order->tecnico)
                    <div>
                        <h4>
                            Técnico associado: {{ $order->tecnico->professional_name }}
                        </h4>
                    </div>
                @endif

            </div>
        </div>
        @if ($order->tecnico)
            <div class="card my-4">
                <div class="card-body">
                    <div>
                        <h4>Editar Técnico</h4>
                    </div>
                    <form action="{{ route('techinical.update', $order->tecnico->id) }}" id="technical-form" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Nome do técnico</label>
                                    <input type="text" name="professional_name" class="form-control"
                                        value="{{ $order->tecnico->professional_name }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">CPF/CNPJ</label>
                                    <input type="text" name="document" value="{{ $order->tecnico->document }}"
                                        id="cpfcnpj" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">CEP</label>
                                    <input type="text" id="cep" name="zip_code"
                                        value="{{ $order->tecnico->zip_code }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Endereço</label>
                                    <input type="text" name="address" value="{{ $order->tecnico->address }}"
                                        class="form-control" id="address">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Número</label>
                                    <input type="text" name="number" value="{{ $order->tecnico->number }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Complemento</label>
                                    <input type="text" name="complement" value="{{ $order->tecnico->complement }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">UF</label>
                                    <input type="text" name="state" value="{{ $order->tecnico->state }}"
                                        class="form-control" id="uf">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Bairro</label>
                                    <input type="text" name="district" value="{{ $order->tecnico->district }}"
                                        class="form-control" id="bairro">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Cidade</label>
                                    <input type="text" name="city" value="{{ $order->tecnico->city }}"
                                        class="form-control" id="cidade">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Celular</label>
                                    <input type="text" name="cell" value="{{ $order->tecnico->cell }}"
                                        id="fone" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Telefone</label>
                                    <input type="text" name="fone" value="{{ $order->tecnico->fone }}"
                                        id="fone" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Email</label>
                                    <input type="email" name="email" value="{{ $order->tecnico->email }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Número da portaria
                                        mormo</label>
                                    <input type="text" name="nr_portaria_mormo"
                                        value="{{ $order->tecnico->nr_portaria_mormo }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Registro profissional</label>
                                    <input type="text" name="registro_profissional"
                                        value="{{ $order->tecnico->registro_profissional }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Conselho</label>
                                    <input type="text" name="conselho" value="{{ $order->tecnico->conselho }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3 text-center">
                                    <button type="submit" class="btn btn-success">Salvar</button>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).on('click', '#technical-save', function() {
            console.log('cliec');
            var btn = $(this);
            var form = $('#technical-form').serialize();
            var url = $('#technical-form').attr('action');
            btn.html('<div class="spinner-border text-light" role="status"></div>');
            btn.prop('disabled', true);
            $('#technical-form').find('input').prop('disabled', true);

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
                    $('#technical-form').find('input').prop('disabled', false);

                    Swal.fire({
                        icon: 'error',
                        title: err.responseJSON.invalid
                    });
                }
            });
        });
    </script>
@endsection
