<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Relatório de ensaio de Homozigose Tobiana</title>

    <style>
        /* reset  */
        * {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-size: 10pt;
        }

        .container {
            max-width: 210mm;
            margin: 0 auto;
        }

        .text-center {
            text-align: center;
        }

        .mb-1 {
            margin-bottom: 10px;
        }

        .mb-2 {
            margin-bottom: 20px;
        }

        .mb-3 {
            margin-bottom: 30px;
        }

        .mb-4 {
            margin-bottom: 40px;
        }

        .mb-5 {
            margin-bottom: 50px;
        }

        /* Header  */


        .header {
            display: flex;
            margin-bottom: 30px;
        }

        .header .logo {
            text-align: center;
            width: 200px;
        }

        .header .logo img {
            width: 134px;
            height: 95px;
        }

        .header .address {
            text-align: center;
            padding: 10px;
        }

        .header h2 {
            text-align: center;
            font-size: 10pt;
        }

        .header p {
            font-size: 9pt;
            text-align: center;
            padding-top: 5pt;
        }

        /* Info  */
        .section-title {
            font-size: 11pt;
            font-weight: bold;
            display: inline;
            background-color: #BEBEBE;
        }

        .result-title {
            font-size: 11pt;
            font-weight: bold;
        }

        .refer-value {
            font-size: 11pt;
            font-weight: bold;
            color: rgb(128, 0, 0);
        }

        .conclusion-title {
            font-weight: bold;
        }

        .conclusion {
            font-size: 10pt;
            margin-bottom: 50px;
        }

        .footer {
            text-align: center;
            margin: 10px auto;
        }

        .footer a {
            text-decoration: none;
            color: #000;
        }

        .assinatura {
            font-size: 7pt;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <img
                    src="data:image/jpg;base64,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" />
            </div>
            <div class="address">
                <h2>LOCI GENÉTICA LABORATORIAL</h2>
                <p>
                    Rua Coronel Durães, 170 - Bela Vista - Lagoa Santa - MG - CEP 33239-206 <br>
                    CRBio nº 400-04/2014 CNES: 7429010 <br>
                    Responsável técnico: Renata Bottrel CRBio 37845/04-D
                </p>
            </div>
        </div>
        <div class="info mb-3">
            <p>Nome do Animal: </p>
            <p>Espécie: </p>
            <p>Raça:</p>
            <p>Sexo:</p>
            <p>Registro:</p>
            <p>Pelagem:</p>
            <p>Proprietário:</p>
            <p>Responsável pela Coleta:</p>
            <p>Data da Coleta:</p>
            <p>OS:</p>
        </div>
        <div class="mb-1">
            <p class="section-title">HOMOZIGOSE TOBIANA</p>
            <p>Setor: <span>Genética Animal</span></p>
            <p>Material: <span>Pelo</span></p>
            <p>Coleta de amostra biológica não realizada pela Loci Genética Laboratorial.</p>
            <p>Método: <span>PCR alelo específica para detecção de inversão cromossômica - (Metodologia in
                    house).</span></p>
        </div>
        <div class="mb-5">
            <p class="result-title">Resultado:</p>
        </div>
        <div class="mb-1 refer-value">
            <h1>Valor de Referência:</h1>
        </div>
        <div class="result mb-3">
            <p>Negativo: ausência da mutação para pelagem Tobiana.</p>
            <p>Positivo Heterozigoto: presença de um alelo mutado para pelagem Tobiana.</p>
            <p>Positivo Homozigoto: presença de dois alelos mutados para pelagem Tobiana.</p>
        </div>
        <div class="conclusion-title mb-1">
            <h2>CONCLUSÃO:</h2>
        </div>

        <div class="conclusion">
            <p class="mb-2">
                Ausência de pelagem Tobiana no animal e impossibilidade de transferência da característica
                para a prole. Também chamado Homozigoto selvagem;
            </p>
            <p class="mb-2">Presença da pelagem Tobiana no animal e probabilidade de 50% de transferência para a
                prole, pois o alelo mutado possui caráter dominante. Também chamado Heterozigoto.</p>
            <p class="mb-2">Presença da pelagem Tobiana no animal e probabilidade de 100% de transferência para a
                prole. Também chamado Homozigoto mutante.</p>
            <p>Data de emissão:</p>
        </div>

        <div class="assinatura text-center mb-3">
            <div>
            </div>
            <h3>Renata Bottrel CRBio 37845/04-D</h3>
            <h3>Responsável Técnica</h3>
        </div>

        <div class="footer">
            <a href="http://www.locilab.com.br/" target="_blank">E-mail: atendimento@locilab.com.br - Telefone:
                (31)36814331</a>
            <a href="http://www.locilab.com.br/" target="_blank">www.locilab.com.br</a>
        </div>

    </div>
</body>

</html>
