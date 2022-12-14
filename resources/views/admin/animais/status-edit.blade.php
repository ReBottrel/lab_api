@extends('layouts.admin')

@section('content')
    <input type="hidden" name="id" class="id" value="{{ $animal->id }}">
    <div id="editar-produto">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h1>Editar Status do Animal</h1>
                </div>
                <div class="card-body">
                    <form action="{{ route('animais.status.update', $animal->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Status </label>
                                    <select class="form-select" name="status">
                                        <option value="1"> Aguardando amostra</option>
                                        <option value="2"> Amostra recebida</option>
                                        <option value="7"> Amostra aprovada</option>
                                        <option value="6"> Amostra reprovada</option>
                                        <option value="10"> Pedido concluído</option>
                                        <option value="11"> Aguardando pagamento</option>
                                        <option value="9"> Pagamento confirmado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Salvar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            var id = $('.id').val();
            console.log(id);
            $.ajax({
                url: `{{ url('animal-get-status') }}/${id}`,
                type: 'GET',
                success: (data) => {
                    console.log(data);
                    for (i in data) {

                        $('#editar-produto').find(`[name="${i}"]`).val(data[i]);
                    }
                }
            });
        });
    </script>
@endsection
