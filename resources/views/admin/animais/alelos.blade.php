@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="mb-3 col-4">
            <label for="exampleFormControlInput1" class="form-label">Buscar registro</label>
            <input type="text" class="form-control" id="registro">
        </div>
        <div class="spinner-border d-none" id="spiner" role="status">
            <span class="visually-hidden">Buscando aguarde...</span>
        </div>
        <div id="importar" class="d-none">
            <button class="btn btn-primary">IMPORTAR</button>
        </div>
        <div class="row d-none" id="info-api">
            <div class="col-md-6 my-3">
                <div class="card">
                    <div class="card mt-3">
                        <div class="card-header">
                            <h2 class="card-title">Animal</h2>
                        </div>
                        <div class="card-body animal">

                        </div>
                    </div>

                    <div class="card-header">
                        <h2 class="card-title">Exame</h2>
                    </div>
                    <div class="card-body exame">

                    </div>
                    <div class="card-footer">
                        <h4 class="card-subtitle">Alelos</h4>
                        <ul class="list-group alelos">

                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6 my-3">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Técnico</h2>
                    </div>
                    <div class="card-body tecnico">

                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-header">
                        <h2 class="card-title">Proprietário</h2>
                    </div>
                    <div class="card-body cliente">

                    </div>
                </div>


                <div class="card mt-3">
                    <div class="card-header">
                        <h2 class="card-title">Genitores</h2>
                    </div>
                    <div class="card-body genitor-animal">
                        <h4 class="card-subtitle">Genitor</h4>

                    </div>
                    <div class="card-footer genitora-animal">
                        <h4 class="card-subtitle">Genitora</h4>
                        <ul class="list-group">

                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@section('js')
    <script>
        $(document).on('blur', '#registro', function() {
            var registro = $(this).val();
            $('#spiner').removeClass('d-none');
            
            $.ajax({
                url: "{{ route('alelos.api') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    registro: registro
                },
                dataType: 'json',


                success: function(data) {
                    console.log(data);
                    $('#info-api').removeClass('d-none');
                    $('.exame').html(`
                        <p class="card-text"><strong>ID:</strong> ${data.exame.id}</p>
                        <p class="card-text"><strong>Código:</strong> ${data.exame.codigo}</p>
                        <p class="card-text"><strong>Data de Cadastro:</strong> ${data.exame.dataCadastro}</p>
                        <p class="card-text"><strong>Data de Coleta:</strong> ${data.exame.dataColeta}</p>
                        <p class="card-text"><strong>Data de Resultado:</strong> ${data.exame.dataResultado}</p>
                        <p class="card-text"><strong>Laboratório:</strong> ${data.exame.laboratorio}</p>
                        <p class="card-text"><strong>Resultado:</strong> ${data.exame.resultado}</p>
                        <p class="card-text"><strong>Sigla:</strong> ${data.exame.sigla}</p>`);
                    var alelosHtml = '';
                    var alelos = data.exame.alelos;
                    alelos.forEach(function(alelo) {
                        alelosHtml +=
                            `<li class="list-group-item">Marcador: ${alelo.marcador}, Alelo1: ${alelo.alelo1}, Alelo2: ${alelo.alelo2}</li>`;
                    });
                    $('.alelos').html(alelosHtml);
                    $('.tecnico').html(`
                        <p class="card-text"><strong>Nome:</strong> ${data.tecnico.nome}</p>
                        <p class="card-text"><strong>CPF/CNPJ:</strong> ${data.tecnico.cpF_CNPJ}</p>
                        <p class="card-text"><strong>UF:</strong> ${data.tecnico.uf}</p>
                        <p class="card-text"><strong>CRMV Número:</strong> ${data.tecnico.crmvNumero}</p>`);
                    $('.animal').html(`
                        <p class="card-text"><strong>ID:</strong> ${data.animal.id}</p>
                        <p class="card-text"><strong>Nome:</strong> ${data.animal.nomeAnimal}</p>
                        <p class="card-text"><strong>ID Pai:</strong> ${data.animal.idPai}</p>
                        <p class="card-text"><strong>ID Mãe:</strong> ${data.animal.idMae}</p>
                        <p class="card-text"><strong>Data de Nascimento:</strong> ${data.animal.dataNascimento}</p>
                        <p class="card-text"><strong>Registro:</strong> ${data.animal.registro}</p>
                        <p class="card-text"><strong>Sexo:</strong> ${data.animal.sexo}</p> `);
                    if (data.genitor.animal != null) {
                        $('.genitor-animal').html(`
                        <h4 class="card-subtitle">Genitor</h4>
                        <p class="card-text"><strong>ID:</strong> ${data.genitor.animal.id}</p>
                        <p class="card-text"><strong>Nome do animal:</strong> ${data.genitor.animal.nomeAnimal}</p>
                        <p class="card-text"><strong>Registro:</strong> ${data.genitor.animal.registro}</p>
                        <p class="card-text"><strong>Sexo: </strong> ${data.genitor.animal.sexo}</p>
                      
                        `);
                    } else {
                        $('.genitor-animal').html(`
                        <h4 class="card-subtitle">Genitor</h4>
                        <p class="card-text">Genitor não encontrado</p>
                        `);
                    }
                    if (data.genitora.animal != null) {
                        $('.genitora-animal').html(`
                        <h4 class="card-subtitle">Genitor</h4>
                        <p class="card-text"><strong>ID:</strong> ${data.genitora.animal.id}</p>
                        <p class="card-text"><strong>Nome do animal:</strong> ${data.genitora.animal.nomeAnimal}</p>
                        <p class="card-text"><strong>Registro:</strong> ${data.genitora.animal.registro}</p>
                        <p class="card-text"><strong>Sexo: </strong> ${data.genitora.animal.sexo}</p>
                        `);
                    } else {
                        $('.genitora-animal').html(`
                        <h4 class="card-subtitle">Genitora</h4>
                        <p class="card-text">Genitora não encontrada</p>
                        `);
                    }
                    $('.cliente').html(`
                        <p class="card-text"><strong>Nome:</strong> ${data.cliente.nome}</p>
                        <p class="card-text"><strong>CPF/CNPJ:</strong> ${data.cliente.cpf_Cnpj}</p>
                        <p class="card-text"><strong>Email:</strong> ${data.cliente.email}</p>
            
                        `);


                },
                complete: function() {
                    $('#spiner').addClass('d-none');
                    // $('#importar').addClass('d-none');
                    $('#importar').removeClass('d-none');
                }


            });
        });
    </script>
@endsection
