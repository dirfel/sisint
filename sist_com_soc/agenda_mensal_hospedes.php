<?php
include "../recursos/models/conexao.php";
require "../recursos/models/versession.php";
if ($_SESSION['nivel_com_soc'] == "Sem Acesso") {
    $msgerro = base64_encode('Usuário não possui acesso!');
    header('Location: ../sistemas/index.php?token=' . $msgerro);
    exit();
}

$pdo1 = conectar("membros");
$pdo2 = conectar("guarda");
$pdo3 = conectar("sistcomsoc");

$mes;
$ano;

if(isset($_GET['ano']) && isset($_GET['mes'])) {
    $mes = $_GET['mes'];
    $ano = $_GET['ano'];
    $meseano = date('m/Y', strtotime($ano.'-'.$mes));
} else {
    $mes = date('m');
    $ano = date('Y');
}
$meseano = date('m/Y', strtotime($ano.'-'.$mes));
$meseanoseg = date('m/Y', strtotime('+1 month', strtotime($ano.'-'.$mes.'-01')));
$meseanoant = date('m/Y', strtotime('-1 month', strtotime($ano.'-'.$mes.'-01')));
$dia1doMes = getDate(strtotime($ano."/".$mes."/01"));
$diaDaSemanaDoDia1 = getDate(strtotime($ano."/".$mes."/01"))['wday'];

$indiceDia = 0;
$ultimoCadastrado = 0;
$nrDiasMes = cal_days_in_month(CAL_GREGORIAN,$mes,$ano);

$primeiraCelulaIndex = $diaDaSemanaDoDia1;
$ultimaCelulaIndex = $diaDaSemanaDoDia1 + $nrDiasMes;
$conteudoCelulas = [];

$sql = 'SELECT id, id_hospede, data_checkin, data_checkout, acompanhantes, quarto 
        FROM reservas';
$stmt = $pdo3->prepare($sql);

$stmt->execute();

$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
$reservasNew = array();
foreach ($reservas as $reserva) {
    $reserva['data_checkin'] = strtotime($reserva['data_checkin'][6].$reserva['data_checkin'][7].$reserva['data_checkin'][8].$reserva['data_checkin'][9].'-'.$reserva['data_checkin'][3].$reserva['data_checkin'][4].'-'.$reserva['data_checkin'][0].$reserva['data_checkin'][1]);
    $reserva['data_checkout'] = strtotime($reserva['data_checkout'][6].$reserva['data_checkout'][7].$reserva['data_checkout'][8].$reserva['data_checkout'][9].'-'.$reserva['data_checkout'][3].$reserva['data_checkout'][4].'-'.$reserva['data_checkout'][0].$reserva['data_checkout'][1]);
    array_push($reservasNew, $reserva);
}

for($indiceDia = 0; $indiceDia<=36; $indiceDia++) {
    if($indiceDia <= $primeiraCelulaIndex || $indiceDia > $ultimaCelulaIndex) {
        $conteudoCelulas[$indiceDia] = '';
    } else {
        $ultimoCadastrado++;
        $datedia = strtotime($ano . '-' . $mes . '-' . $ultimoCadastrado);
        $ht1 = '';
        $ht2 = '';
        $ht3 = '';
        foreach ($reservasNew as $resNew) {
            if($resNew['data_checkin'] <= $datedia && $resNew['data_checkout'] > $datedia) {
                $sql;
                if($resNew['id_hospede'] < 0) {
                    $sql = 'SELECT nomecompleto FROM visitante WHERE id = ' . ($resNew['id_hospede'] * -1);
                    $stmt = $pdo2->prepare($sql);
                    $stmt->execute();
                    $us = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]['nomecompleto'];
                } else {
                    $sql = 'SELECT idpgrad, nomecompleto FROM usuarios WHERE id = ' . $resNew['id_hospede'];
                    $stmt = $pdo1->prepare($sql);
                    $stmt->execute();
                    $u1 = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
                    $us = getPGrad($u1['idpgrad']) . ' ' .$u1['nomecompleto'];
                }

                if($resNew['quarto'] == 'HT1') { $ht1 = $us;
                } else if($resNew['quarto'] == 'HT2') { $ht2 = $us;
                } else if($resNew['quarto'] == 'HT3') { $ht3 = $us;
                }
            }
        }
        $conteudoCelulas[$indiceDia] = '<strong>'.$ultimoCadastrado.'</strong><br>HT1: '.$ht1.'<br>HT2: '.$ht2.'<br>HT3: '.$ht3;
    }
} ?>

<!DOCTYPE html>
<html>
    <head>
        <title>Gerado por: <?= ImprimeConsultaMilitar2($_SESSION['auth_data']) ?></title>
        <style>
            table { width: 100%; table-layout: fixed; }
            .visto { column-width: 100px; }
            .cell-conf { border: solid black 1px; }
        </style>
    </head>
    <body>
        <!-- visto Scmt e Fisc Adm -->
        <table>
            <thead>
                <tr>
                    <th class="visto"></th>
                    <th><?=strtoupper(NOME_OM)?></th>
                    <th class="visto"></th>
                </tr>
            </thead>
            <tr>
                <td><br></td>
                <td style="text-align: center;">HOTEL DE TRÂNSITO RECANTO DAS ARARAS</td>
                <td><br></td>
            </tr>
        </table>
        <br>
       
        <!-- titulo ht -->
        <h3 style="text-align: center;">( <a href="agenda_mensal_hospedes.php?mes=<?=substr($meseanoant, 0, 2)?>&ano=<?=substr($meseanoant, 3)?>"><-anterior</a> ) CALENDÁRIO DO HOTEL DE TRÂNSITO DO MÊS <?= $meseano?> ( <a href="agenda_mensal_hospedes.php?mes=<?=substr($meseanoseg, 0, 2)?>&ano=<?=substr($meseanoseg, 3)?>">próximo-></a> )</h3>
        <!-- tabela ht -->
        <table class="tbl-conf" style="font-size: 0.75em;">
            <thead>
                <tr>
                    <th class="cell-conf">Domingo</th>
                    <th class="cell-conf">Segunda</th>
                    <th class="cell-conf">Terça</th>
                    <th class="cell-conf">Quarta</th>
                    <th class="cell-conf">Quinta</th>
                    <th class="cell-conf">Sexta</th>
                    <th class="cell-conf">Sábado</th>
                </tr>
            </thead>
            <tr>
                <td class="cell-conf"><?=$conteudoCelulas[1]?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[2]?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[3]?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[4]?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[5]?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[6]?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[7]?><?=''?></td>
            </tr>
            <tr>
                <td class="cell-conf"><?=$conteudoCelulas[8]?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[9]?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[10]?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[11]?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[12]?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[13]?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[14]?><?=''?></td>
            </tr>
            <tr>
                <td class="cell-conf"><?=$conteudoCelulas[15]?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[16]?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[17]?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[18]?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[19]?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[20]?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[21]?><?=''?></td>
            </tr>
            <tr>
                <td class="cell-conf"><?=$conteudoCelulas[22]?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[23]?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[24]?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[25]?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[26]?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[27]?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[28]?><?=''?></td>
            </tr>
            <tr>
                <td class="cell-conf"><?=$conteudoCelulas[29]?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[30]?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[31]?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[32]?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[33]?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[34]?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[35]?><?=''?></td>
            </tr>
            <tr>
                <td class="cell-conf"><?=$conteudoCelulas[36] ?? ''?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[37] ?? ''?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[38] ?? ''?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[39] ?? ''?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[40] ?? ''?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[41] ?? ''?><?=''?></td>
                <td class="cell-conf"><?=$conteudoCelulas[42] ?? ''?><?=''?></td>
            </tr>
        </table>
    </body>
</html>