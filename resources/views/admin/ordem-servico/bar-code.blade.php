<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://kit.fontawesome.com/0ab2bcde1c.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('adm/assets/css/codebar.min.css') }}">
    <title>Document</title>
</head>

<body>
    <div class="row text-center my-4">
        <div>
            <button class="btn btn-primary"><i class="fa-solid fa-print"></i></button>
        </div>
    </div>
    <page size="A4">
        <div class="print">
            <img src="data:image/png;base64,{{ $barcodex }}" alt="">
            <p>{{ $ordem->codlab }}</p>
            <p>{{ $ordem->tipo_exame }}</p>
            <p>{{ $ordem->order }}</p>
        </div>
    </page>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
