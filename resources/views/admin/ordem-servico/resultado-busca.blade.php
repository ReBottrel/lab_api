@extends('layouts.admin')
@section('content')
    <div class="container">
        <div>
            <h3>Ordem de serviço numero:</h3>
        </div>
        <div class="d-flex my-4">
            <div>
                <button type="button" class="btn btn-primary" id="imprimir"><i class="fa-solid fa-print"></i> Imprimir
                    OS</button>
            </div>
        </div>
        <div class="conteudo d-none">
            @include('admin.ordem-servico.include.print', get_defined_vars())
        </div>
            <div class="ordem-servico">
                <div class="card-alt">
                    <div class="card-title">Animal ID</div>
                    <div class="card-data">{{ $ordemServico->animal_id }}</div>


                    <div class="card-title">Animal</div>
                    <div class="card-data">{{ $ordemServico->animal }}</div>

                    <div class="card-title">Código Lab</div>
                    <div class="card-data">{{ $ordemServico->codlab }}</div>

                    <div class="card-title">ID ABCCMM</div>
                    <div class="card-data">{{ $ordemServico->id_abccmm }}</div>

                    <div class="card-title">Tipo de Exame</div>
                    <div class="card-data">{{ $ordemServico->tipo_exame }}</div>

                    <div class="card-title">Proprietário</div>
                    <div class="card-data">{{ $ordemServico->proprietario }}</div>

                    <div class="card-title">Técnico</div>
                    <div class="card-data">{{ $ordemServico->tecnico }}</div>

                    <div class="card-title">Data esperada</div>
                    <div class="card-data">{{ date('d/m/Y', strtotime($ordemServico->data)) }}</div>

                    <div class="card-title">Observação</div>
                    <div class="card-data">{{ $ordemServico->observacao }}</div>

                    <div class="d-flex my-4">
                        <div class="">
                            <a href="{{ route('gerar.barcode', $ordemServico->id) }}"> <button class="btn btn-primary"><i
                                        class="fa-solid fa-tag"></i> Imprimir etiqueta</button></a>
                        </div>
                        <div class="mx-2">
                            <a href="{{ route('alelo.compare', $ordemServico->id) }}"> <button class="btn btn-alt-2"><i
                                        class="fa-solid fa-tag"></i> Laudo</button></a>
                        </div>

                        <div class="mx-2">
                            <a href="#"> <button id="openModal" data-bs-toggle="modal"
                                    data-id="{{ $ordemServico->id }}" data-bs-target="#exampleModal"
                                    class="btn btn-primary"><i class="fa-solid fa-tag"></i>
                                    Data da entrada na area técnica</button></a>


                        </div>
                        <div class="mx-2">
                            <button type="button" id="dataAnalise" data-bs-toggle="modal" data-bs-target="#modalData"
                                data-id="{{ $ordemServico->id }}" class="btn btn-primary"><i class="fa-solid fa-tag"></i>
                                Data da análise</button>
                        </div>

                    </div>
                </div>
            </div>
   

    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Leitura do código de barras</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    @csrf
                    <input type="hidden" name="ordem_id" id="ordem_id">
                    <div class="modal-body">
                        <div class="mb-3 col-4">
                            <label for="formFile" class="form-label">Data da leitura do código</label>
                            <input class="form-control" name="file" id="data" type="date">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" id="enviar">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modalData" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Inserir data da análise</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    @csrf
                    <input type="hidden" name="ordem_id" id="id">
                    <div class="modal-body">
                        <div class="mb-3 col-4">
                            <label for="formFile" class="form-label">Data da análise</label>
                            <input class="form-control" name="file" id="dataAn" type="date">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" id="save">Salvar apenas um</button>
                        {{-- <button type="button" class="btn btn-primary" id="replicar">Replicar para todos</button> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $('#imprimir').click(function() {
            var conteudo = $('.conteudo').html();
            var tela_impressao = window.open('about:blank');
            tela_impressao.document.write(conteudo);
            tela_impressao.window.print();
            tela_impressao.window.close();
        });
        $(document).on('click', '#openModal', function() {
            let id = $(this).data('id');
            $('#ordem_id').val(id);
        });

        $(document).on('click', '#enviar', function() {
            let data = $('#data').val();
            let ordem_id = $('#ordem_id').val();

            $.ajax({
                url: "{{ route('data.bar.store') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    ordem_id: ordem_id,
                    data: data
                },
                success: function(data) {
                    console.log(data);
                    $('#exampleModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        text: 'Data salva com sucesso!',
                    });
                }

            });
        });
        $(document).on('click', '#dataAnalise', function() {
            let id = $(this).data('id');
            $('#id').val(id);

        });
        $(document).on('click', '#save', function() {
            let data = $('#dataAn').val();
            let id = $('#id').val();

            $.ajax({
                url: "{{ route('data.analise') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    data: data
                },
                success: function(data) {
                    console.log(data);
                    $('#modalData').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        text: 'Data salva com sucesso!',
                    });
                }

            });
        });
      
    </script>
@endsection
