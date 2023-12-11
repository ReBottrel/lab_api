@extends('layouts.admin')

@section('content')
    <div class="container">
        <div>
            <h3>Relatórios</h3>
        </div>
        <div>
            <p><strong>Total de Laudos Concluídos:</strong> {{ $totalLaudos }}</p>
            <p><strong>Total de Laudos com Exclusão:</strong> {{ $totalLaudosExclusao }}</p>
            <p><strong>Total de Laudos com Exclusão Genitor:</strong> {{ $totalLaudosExclusaoGenitor }}</p>
            <p><strong>Total de Laudos com Exclusão Genitora:</strong> {{ $totalLaudosExclusaoGenitora }}</p>
        </div>
        <div>
            <a href="{{ route('get.laudo.total') }}" class="btn btn-primary">Baixar Laudos Concluídos</a>
            <a href="{{ route('get.laudo.total.exclusao') }}" class="btn btn-danger">Baixar Laudos com Exclusão</a>
            <a href="{{ route('get.laudo.total.exclusao.genitor') }}" class="btn btn-danger">Baixar Laudos com Exclusão
                genitor</a>
            <a href="{{ route('get.laudo.total.exclusao.genitora') }}" class="btn btn-danger">Baixar Laudos com Exclusão
                genitora</a>
        </div>
        <div class="mt-5">
            <h3>Relatórios por Codlab</h3>
        </div>
        <div>
            <div class="row">
                <div class="col-8">
                    <label for="exampleFormControlInput1" class="form-label">Buscar codlab</label>
                    <input type="search" class="form-control" id="codlab">
                </div>
                <div class="col-4 mt-4">
                    <button class="btn btn-primary" id="enviar">BUSCAR</button>
                </div>
            </div>
            <div id="genealogyTree">
                <!-- Aqui você vai inserir a representação da árvore genealógica -->
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        function buildGenealogyTree(data) {
            let treeHtml = '<ul>';
            treeHtml += '<li>' + data.animal_name;

            if (data.pai) {
                treeHtml += '<ul>';
                treeHtml += `<li> PAI: ${data.pai_rel.animal_name} / CODLAB: ${data.pai_rel.codlab}  </li>`;
                treeHtml += buildGenealogyTree(data.pai_rel); // Chamada recursiva para o pai
                treeHtml += '</ul>';
            }

            if (data.mae) {
                treeHtml += '<ul>';
                treeHtml += `<li> MÂE: ${data.mae_rel.animal_name} / CODLAB: ${data.mae_rel.codlab}  </li>`;
                treeHtml += buildGenealogyTree(data.mae_rel); // Chamada recursiva para a mãe
                treeHtml += '</ul>';
            }

            treeHtml += '</li>';
            treeHtml += '</ul>';

            return treeHtml;
        }

        $('#enviar').click(function() {
            let codlab = $('#codlab').val()
            $.ajax({
                url: "{{ route('get.codlab.relatorio') }}",
                type: 'POST',
                data: {
                    codlab: codlab
                },
                success: function(data) {
                    console.log(data);
                    $('#genealogyTree').html('');

                    // Verifique se há dados para a árvore genealógica
                    if (data) {
                        // Chame a função recursiva para criar a árvore
                        let treeHtml = buildGenealogyTree(data);

                        // Adicione a árvore à div
                        $('#genealogyTree').html(treeHtml);
                    }
                }
            });
        });
    </script>
@endsection
