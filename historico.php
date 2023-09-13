<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://kit.fontawesome.com/1a3d298cfc.js" crossorigin="anonymous"></script>
    <title>Histórico de Simulações</title>
</head>

<body>
    <header>
        <h2>DevWeb2023</h2>
    </header>

    <main>
        <h1>Histórico de Simulações</h1>

        <div id="rec">
            <form method="post">
                <fieldset>
                    <label for="idSim">ID da Simulação</label>
                    <input type="number" name="idSim" id="idSim" step="1" value="<?php echo isset($_POST['idSim']) ? $_POST['idSim'] : '' ?>" required>
                    <br>

                    <input type="submit" value="Recuperar" name="rec" id="rec">
                </fieldset>
            </form>
        </div>

        <?php
        require_once 'classes/autoloader.class.php';

        R::setup(
            'mysql:host=localhost;dbname=fintech',
            'root',
            ''
        );

        if (isset($_POST['rec'])) {
            $aux = R::load('investimento', $_POST['idSim']);
        }
        ?>

        <div id="centro">
            <p>ID da Simulação: <?php echo isset($aux) ? $aux->id : '' ?></p>
            <p>Cliente: <?php echo isset($aux) ? $aux->nome : '' ?></p>
            <p>Aporte Inicial (R$): <?php echo isset($aux) ? $aux->inicial : '' ?></p>
            <p>Aporte Mensal (R$): <?php echo isset($aux) ? $aux->mensal : '' ?></p>
            <p>Rendimento (%): <?php echo isset($aux) ? $aux->taxa_rendimento : '' ?></p>
        </div>

        <?php
        if (isset($_POST['rec'])) {
            function calcularRendimento($inicial, $mensal, $taxa_rendimento)
            {
                $rendimento = ($inicial + $mensal) * ($taxa_rendimento / 100);
                $total = $inicial + $mensal + $rendimento;
                return array($rendimento, $total);
            }

            $aporte_inicial = $aux->inicial;
            $periodo = $aux->periodo;
            $rendimento_mensal = $aux->taxa_rendimento;
            $aporte_mensal = $aux->mensal;

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
        }
        ?>

        <div class="home-link">
            <p><a href="index.html"><i class="fa-solid fa-house"></i></a></p>
        </div>
    </main>

    <footer>
        <p>&copy;2023 - Matheus Vieira e Cézar Passos</p>
    </footer>
</body>

</html>