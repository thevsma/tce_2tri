<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://kit.fontawesome.com/1a3d298cfc.js" crossorigin="anonymous"></script>
    <title>Resultados da Simulação</title>
</head>

<body>
    <header>
        <h2>DevWeb2023</h2>
    </header>

    <main>
        <h1>Resultados</h1>

        <?php
        require_once 'classes/autoloader.class.php';

        R::setup(
            'mysql:host=localhost;dbname=fintech',
            'root',
            ''
        );

        $i = R::dispense('investimento');
        $i->nome = $_POST['nome'];
        $i->inicial = $_POST['inicial'];
        $i->mensal = $_POST['mensal'];
        $i->taxa_rendimento = $_POST['rendimento'];
        $i->periodo = $_POST['periodo'];

        R::store($i);

        function calcularRendimento($valor_inicial, $aporte_mensal, $taxa_rendimento)
        {
            $rendimento = ($valor_inicial + $aporte_mensal) * ($taxa_rendimento / 100);
            $total = $valor_inicial + $aporte_mensal + $rendimento;
            return array($rendimento, $total);
        }

        if (isset($_POST['submit'])) {
            $cliente = $_POST['cliente'];
            $aporte_inicial = floatval($_POST['aporte_inicial']);
            $periodo = intval($_POST['periodo']);
            $rendimento_mensal = floatval($_POST['rendimento']) / 100;
            $aporte_mensal = floatval($_POST['aporte_mensal']);

            $valor_inicial = $aporte_inicial;

            echo '<table border="1">';
            echo '<tr><th>Mês</th><th>Valor Inicial (R$)</th><th>Aporte (R$)</th><th>Rendimento (R$)</th><th>Total (R$)</th></tr>';

            for ($mes = 1; $mes <= $periodo; $mes++) {
                list($rendimento, $total) = calcularRendimento($valor_inicial, $aporte_mensal, $rendimento_mensal);
                echo '<tr>';
                echo '<td>' . $mes . '</td>';
                echo '<td>' . number_format($valor_inicial, 2, ',', '.') . '</td>';
                echo '<td>' . number_format($aporte_mensal, 2, ',', '.') . '</td>';
                echo '<td>' . number_format($rendimento, 2, ',', '.') . '</td>';
                echo '<td>' . number_format($total, 2, ',', '.') . '</td>';
                echo '</tr>';
                $valor_inicial = $total;
            }
            echo '</table>';
        }
        ?><br>

        <div class="entrada-link">
            <a href="entrada.html"><i class="fa-solid fa-arrow-left"></i></a>
        </div>
    </main>

    <br>
    <footer>
        <p>&copy;2023 - Matheus Vieira & Cézar Passos</p>
    </footer>