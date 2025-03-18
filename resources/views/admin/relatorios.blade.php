@extends('layouts.admin')

@section('content')
<div class="relatorios-container">
    <div class="page-header">
        <h3>Relatórios</h3>
    </div>

    <div class="stats-cards">
        <div class="stat-card">
            <div class="stat-label">Total de Laudos Concluídos</div>
            <div class="stat-value">{{ $totalLaudos }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Laudos com Exclusão</div>
            <div class="stat-value">{{ $totalLaudosExclusao }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Exclusão Genitor</div>
            <div class="stat-value">{{ $totalLaudosExclusaoGenitor }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Exclusão Genitora</div>
            <div class="stat-value">{{ $totalLaudosExclusaoGenitora }}</div>
        </div>
    </div>

    <div class="action-buttons">
        <a href="{{ route('get.laudo.total') }}" class="btn btn-primary">
            <i class="fas fa-download"></i>
            Baixar Laudos Concluídos
        </a>
        <a href="{{ route('get.laudo.total.exclusao') }}" class="btn btn-danger">
            <i class="fas fa-file-alt"></i>
            Baixar Laudos com Exclusão
        </a>
        <a href="{{ route('get.laudo.total.exclusao.genitor') }}" class="btn btn-danger">
            <i class="fas fa-male"></i>
            Baixar Laudos com Exclusão Genitor
        </a>
        <a href="{{ route('get.laudo.total.exclusao.genitora') }}" class="btn btn-danger">
            <i class="fas fa-female"></i>
            Baixar Laudos com Exclusão Genitora
        </a>
    </div>

    <div class="search-section">
        <h3>Relatórios por Codlab</h3>
        <div class="search-form">
            <div class="form-group">
                <label for="codlab">Buscar codlab</label>
                <input type="search" class="form-control" id="codlab" placeholder="Digite o código...">
            </div>
            <button class="btn btn-search" id="enviar">
                <i class="fas fa-search"></i>
                Buscar
            </button>
        </div>
        <div id="genealogyTree"></div>
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
