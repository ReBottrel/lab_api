@extends('layouts.admin')

@section('content')
@endsection


@section('js')
    <script>
        var valorEmprestimo = 12000;
        var taxaJuros = 3.99 / 100; // Convertendo a taxa de porcentagem para decimal
        var numParcelas = 10;
        var taxaIOF = 0.38 / 100; // Taxa de IOF de 0.38%

        var valorParcela = valorEmprestimo * (taxaJuros * Math.pow(1 + taxaJuros, numParcelas)) / (Math.pow(1 + taxaJuros,
            numParcelas) - 1);
        var valorIOF = valorEmprestimo * taxaIOF;

        var valorTotal = valorParcela + valorIOF;

        console.log("O valor de cada parcela é: R$" + valorParcela.toFixed(2));
        console.log("O valor do IOF é: R$" + valorIOF.toFixed(2));
        console.log("O valor total a ser pago é: R$" + valorTotal.toFixed(2));
    </script>
@endsection
