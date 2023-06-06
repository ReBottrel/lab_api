@extends('layouts.admin')
@section('content')
    <div class="container">
        <div>
            <h3>Ordem de serviço numero: #{{ $ordem->id }}</h3>
        </div>
        <div class="d-flex my-4">
            <div>
                <button type="button" class="btn btn-primary" id="imprimir"><i class="fa-solid fa-print"></i> Imprimir
                    laudo</button>
            </div>
        </div>
        <div class="conteudo d-none">
            @include('admin.ordem-servico.include.print', get_defined_vars())
        </div>
        @foreach ($ordemServicos as $ordemServico)
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
                            <a href="{{ route('alelo.compare', $ordemServico->id) }}"> <button class="btn btn-primary"><i
                                        class="fa-solid fa-tag"></i> Laudo</button></a>
                        </div>
                        {{-- <div class="mx-2">
                            <a href="{{ route('alelo.compare', $ordemServico->id) }}"> <button class="btn btn-primary"><i
                                        class="fa-solid fa-tag"></i> Importar alelos</button></a>
                        </div> --}}
                    </div>
                </div>
            </div>
        @endforeach

    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Importar txt</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('import.txt') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Selecione o arquivo para importar</label>
                            <input class="form-control" name="file" type="file" id="formFile">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" id="enviar">Importar</button>
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
        $(document).on('click', '#enviar', function(){
            $(this).attr('disabled', true);
            $(this).text('Aguarde...');
            $(this).parents('form').submit();
        });
    </script>
@endsection
