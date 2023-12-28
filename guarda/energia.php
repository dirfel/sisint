<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if (!($_SESSION['nivel_guarda'] == "Cabo Gda" || $_SESSION['nivel_guarda'] == "Oficial e Sargento" || $_SESSION['nivel_guarda'] == "Supervisor" || $_SESSION['nivel_guarda'] == "Administrador")) {
    $msgerro = base64_encode('Usuário não possui acesso! Somente usuário: Cabo Gda, Oficial e Sargento!');
    header('Location: index.php?token=' . $msgerro);
    exit();
  }

// Obtém os dados do mês e ano do relatório
$ano = date('Y');
$mes = date('m');
if(isset($_GET['mes']) && is_numeric($_GET['mes'])) {
  $mes = str_pad($_GET['mes'], 2, '0', STR_PAD_LEFT);
}
if(isset($_GET['ano']) && is_numeric($_GET['ano'])) {
  $ano = str_pad($_GET['ano'], 2, '0', STR_PAD_LEFT);
}

$pdo = conectar('guarda');
            $query = 'SELECT liv_partes_ofdia.id, liv_partes_ofdia.data, liv_partes_ofdia.idleituras, 
            liv_partes_ofdia_leituras.energia_anterior, liv_partes_ofdia_leituras.energia_atual, 
            liv_partes_ofdia_leituras.agua_int_anterior, liv_partes_ofdia_leituras.agua_int_atual, 
            liv_partes_ofdia_leituras.agua_ext_anterior, liv_partes_ofdia_leituras.agua_ext_atual
            FROM `liv_partes_ofdia` INNER JOIN liv_partes_ofdia_leituras ON idleituras = liv_partes_ofdia_leituras.id_leituras 
            WHERE liv_partes_ofdia.data LIKE "'.$ano.'-'.$mes.'%";';
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $linhas = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Executa o query SQL
// $resultado = mysqli_query($conexao, $query);

// Cria um array com os dados do relatório
$dados = array();
foreach($linhas as $linha) {
    $dados[] = array(
        'data' => $linha['data'],
        'consumo' => $linha['energia_atual'] - $linha['energia_anterior']
    );
}

// Calcula o consumo total
$consumo_total = $linhas[count($linhas) - 1]['energia_atual'] - $linhas[0]['energia_anterior'];

// Cria a tabela com o consumo diário
?>
<table>
<tr>
    <td colspan="2" align="center"><h2>CONSUMO DE ENERGIA ELÉTRICA NO MÊS <?=$mes . '/' . $ano ?>,<br>CONFORME LIVROS DO OFICIAL-DE-DIA - <?=ABR_OM?></h2></td>
</tr>
<tr><th>
<table border="1" >
    <thead>
        <tr>
            <th>Data</th>
            <th>Consumo (kWh)</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($dados as $linha) { ?>
            <tr>
                <td align="center"> <?= date_converter2($linha['data']) ?> </td>
                <td align="center"><?= $linha['consumo'] ?></td>
            </tr>
        <?php } ?>
        <tr>
            <td align="center">Total</td>
            <td align="center"><?= $consumo_total ?></td>
        </tr>
    </tbody>
</table></th>
<?php

// Cria o gráfico de barras com os valores
?>
<th>
<!-- <div style=""> -->
    <script src="../recursos/vendor/chart-js/chart.js"></script>
    <canvas id="grafico" width="500px"></canvas>
    <script>
        const ctx = document.getElementById('grafico').getContext('2d');
        const data = {
            labels: [
                <?php foreach ($dados as $linha) { ?>
                    '<?= $linha['data'][8].$linha['data'][9] ?>',
                <?php } ?>
            ],
            datasets: [
                {
                    label: 'Consumo (kWh)',
                    data: [
                        <?php foreach ($dados as $linha) { ?>
                            <?= $linha['consumo'] ?>,
                        <?php } ?>
                    ],
                    backgroundColor: [
                        'rgba(0, 0, 0, 0.6)',
                        'rgba(0, 0, 0, 0.6)',
                        'rgba(0, 0, 0, 0.6)',
                        'rgba(0, 0, 0, 0.6)',
                        'rgba(0, 0, 0, 0.6)',
                        'rgba(0, 0, 0, 0.6)',
                        'rgba(0, 0, 0, 0.6)',
                        'rgba(0, 0, 0, 0.6)',
                        'rgba(0, 0, 0, 0.6)',
                        'rgba(0, 0, 0, 0.6)',
                    ],
                }
            ]
        };
        const chart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                scales: {
                    y: {
                        ticks: {
                            min: 0,
                           
                        },
                    },
                },
            }
        });
    </script>
<!-- </div> -->
    </th></tr>
    <tr><td><br></td></tr>
<tr style="outline: thin solid" align="center"><td colspan="2">RELATÓRIO GERADO NOS SISTEMAS INTEGRADOS POR:<br><?= getPGrad($_SESSION['auth_data']['idpgrad']) . " " . $_SESSION['auth_data']['nomecompleto'] . " (" . $_SESSION['auth_data']['nomeguerra'] . ")" ?></td></tr>
</table>
<?php

// Finaliza o arquivo
?>

