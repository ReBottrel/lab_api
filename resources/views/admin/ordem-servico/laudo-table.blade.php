<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tabela de laudos</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center align-items-center my-5">
            <div class="col-10">
                <h3 class="text-center">Tabela de laudos</h3>
            </div>
            <div class="col-10">
                <table class="table table-sm table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nome do Animal</th>
                            <th>Nº da ordem</th>
                            <th>Data de conclusão</th>
                            <th>Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Animal_1</td>
                            <td>123</td>
                            <td>07/06/2023</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="" class="btn btn-primary">Editar</a>
                                    <a href="" class="btn btn-primary">Visualizar</a>
                                    <form>
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Excluir</button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
