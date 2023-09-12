<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Resultados da Simulação</title>
</head>

<body>
    <header>
        <h2>DevWeb2023</h2>
    </header>

    <main>
        <h1>Resultado da Simulação</h1>

        <h3>Dados:</h3>

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
        $i->taxa_rendimento = $_POST['taxa_rendimento'];
        $i->periodo = $_POST['periodo'];

        R::store($i);
        $aux = R::load('investimento', $i);

        ?>

        <p>ID da Simulação: <?php echo isset($aux) ? $aux->id : '' ?></p>
        <p>Cliente: <?php echo isset($aux) ? $aux->nome : '' ?></p>
        <p>Aporte Inicial (R$): <?php echo isset($aux) ? $aux->inicial : '' ?></p>
        <p>Aporte Mensal (R$): <?php echo isset($aux) ? $aux->mensal : '' ?></p>
        <p>Rendimento (%): <?php echo isset($aux) ? $aux->taxa_rendimento : '' ?></p>

        <?php
        function calcularRendimento($inicial, $mensal, $taxa_rendimento)
        {
            $rendimento = ($inicial + $mensal) * ($taxa_rendimento / 100);
            $total = $inicial + $mensal + $rendimento;
            return array($rendimento, $total);
        }

        $aporte_inicial = $i->inicial;
        $periodo = $i->periodo;
        $rendimento_mensal = $i->taxa_rendimento;
        $aporte_mensal = $i->mensal;

        $valor_inicial = $aporte_inicial;

        echo '<table border="1">';
        echo '<tr><th>Mês</th><th>Valor Inicial (R$)</th><th>Aporte (R$)</th><th>Rendimento (R$)</th><th>Total (R$)</th></tr>';

        for ($mes = 1; $mes <= $periodo; $mes++) {
            if ($mes == 1) {
                list($rendimento, $total) = calcularRendimento($valor_inicial, 0, $rendimento_mensal);
                echo '<tr>';
                echo '<td>' . $mes . '</td>';
                echo '<td>' . number_format($valor_inicial, 2, ',', '.') . '</td>';
                echo '<td>' . number_format(0, 2, ',', '.') . '</td>';
                echo '<td>' . number_format($rendimento, 2, ',', '.') . '</td>';
                echo '<td>' . number_format($total, 2, ',', '.') . '</td>';
                echo '</tr>';
                $valor_inicial = $total;
            } else {
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
        }
        echo '</table>';
        ?>
    </main>

    <footer>
        <p><a href="entrada.html">Voltar</a></p>
        <p>&copy;2023 - Matheus Vieira e Cézar Passos</p>
    </footer>
</body>

</html>