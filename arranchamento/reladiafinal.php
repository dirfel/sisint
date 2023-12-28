<?php

require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
$pdo = conectar("membros");
$pdo2 = conectar("arranchamento");
//limpa os arranchamentos vazios
$sqldeleta = "DELETE FROM arranchado WHERE cafe =  '' AND almoco = '' AND jantar = ''";
$sqlapaga = $pdo2->prepare($sqldeleta);
$sqlapaga->execute();

$datarancho = filter_input(INPUT_POST, "datarancho");
$subunidade = filter_input(INPUT_POST, "subunidade");
$postograd = filter_input(INPUT_POST, "postograd");
$meuidsu = $_SESSION['auth_data']['idsubunidade'];

if ($subunidade > 0) {
    $SUnidade = listar_subunidades($subunidade)[0];
}
$diasemana = array('DOMINGO', 'SEGUNDA-FEIRA', 'TERÇA-FEIRA', 'QUARTA-FEIRA', 'QUINTA-FEIRA', 'SEXTA-FEIRA', 'SÁBADO');
$convdata = date_converter($datarancho);
$diasemana_numero = date('w', strtotime($convdata));
echo "<table border=1 width=100% cellpadding=3 cellspacing=0>\n";
echo "<tr><th align='center' valign='middle' width=100%><font size=0.5> SISTEMA DE ARRANCHAMENTO - ". strtoupper(NOME_OM) ." </font></th></tr>";
echo "</table>";
echo "<p>";
echo "<table border=1 width=100% cellpadding=3 cellspacing=0>\n";
echo "<tr>";
echo "<th align='center' valign='middle' width=100%>";
$subtitulo;
if ($subunidade > 0) { // FOI ESCOLHIDO UMA SUBUNIDADE
    if ($postograd == 0) { // ESCOLHIDO TODOS POSTO/GRADUAÇÃO
        $crancho = $pdo2->prepare("SELECT * FROM arranchado WHERE data = :data AND idsu = :idsu ORDER BY idpgrad, nomeguerra ASC");
    }
    if ($postograd == 1) { // ESCOLHIDO OFICIAIS
        $crancho = $pdo2->prepare("SELECT * FROM arranchado WHERE data = :data AND idsu = :idsu AND idpgrad > 0 && idpgrad <= 8 ORDER BY idpgrad, nomeguerra ASC");
    }
    if ($postograd == 2) { // ST/SGT
        $crancho = $pdo2->prepare("SELECT * FROM arranchado WHERE data = :data AND idsu = :idsu AND idpgrad > 8 && idpgrad < 13 ORDER BY idpgrad, nomeguerra ASC");
    }
    if ($postograd == 3) { // ESCOLHIDO CB/SD
        $crancho = $pdo2->prepare("SELECT * FROM arranchado WHERE data = :data AND idsu = :idsu AND idpgrad >= 13 ORDER BY idpgrad, nomeguerra ASC");
    }
    $crancho->bindParam(":data", $datarancho, PDO::PARAM_STR);
    $crancho->bindParam(":idsu", $subunidade, PDO::PARAM_INT);
    $crancho->execute();
    $subtitulo = "RELAÇÃO DE ARRANCHAMENTO PARA O DIA " . $datarancho . " (" . $diasemana[$diasemana_numero] . ") DA " . $SUnidade['descricao'] . "";
} else { // FOI ESCOLHIDO TODAS AS SU
    if ($postograd == 0) { // ESCOLHIDO TODOS POSTO/GRADUAÇÃO
        $crancho = $pdo2->prepare("SELECT * FROM arranchado WHERE data = :data ORDER BY idpgrad, nomeguerra ASC");
    }
    if ($postograd == 1) { // ESCOLHIDO OF
        $crancho = $pdo2->prepare("SELECT * FROM arranchado WHERE data = :data AND idpgrad > 0 && idpgrad <= 8  ORDER BY idpgrad, nomeguerra ASC");
    }
    if ($postograd == 2) { // ESCOLHIDO ST/SGT
        $crancho = $pdo2->prepare("SELECT * FROM arranchado WHERE data = :data AND idpgrad > 8 && idpgrad < 13 ORDER BY idpgrad, nomeguerra ASC");
    }
    if ($postograd == 3) { // ESCOLHIDO CB/SD
        $crancho = $pdo2->prepare("SELECT * FROM arranchado WHERE data = :data AND idpgrad >= 13 ORDER BY idpgrad, nomeguerra ASC");
    }
    $crancho->bindParam(":data", $datarancho, PDO::PARAM_STR);
    $crancho->execute();
    $subtitulo = "RELAÇÃO DE ARRANCHAMENTO PARA O DIA " . $datarancho . " (" . $diasemana[$diasemana_numero] . ")";
}
echo("<font size=0.5>". $subtitulo . "</font>");

echo "</th>";
echo("</tr>");
echo("</table>");
echo("<p>");
$totrancho = $crancho->fetchAll(PDO::FETCH_ASSOC);
$totalregistro = count($totrancho);
$resto = $totalregistro % 3;
// caso a quantidade de registros seja menor que 3
if ($totalregistro < 2) {
    $coluna1 = 1;
    $coluna2 = 0;
    $coluna3 = 0;
}
if ($totalregistro > 1 && $totalregistro < 3) {
    $coluna1 = 1;
    $coluna2 = 1;
    $coluna3 = 0;
}
if ($totalregistro >= 3) {
    if ($resto == 1) {
        $coluna1 = (int) ($totalregistro / 3) + 1;
        $coluna2 = (int) ($totalregistro / 3);
        $coluna3 = (int) ($totalregistro / 3);
    }
    if ($resto == 2) {
        $coluna1 = (int) ($totalregistro / 3) + 1;
        $coluna2 = (int) ($totalregistro / 3) + 1;
        $coluna3 = (int) ($totalregistro / 3);
    }
    if ($resto == 0) {
        $coluna1 = (int) ($totalregistro / 3);
        $coluna2 = (int) ($totalregistro / 3);
        $coluna3 = (int) ($totalregistro / 3);
    }
    $coluna2 = ($coluna1 + $coluna2);    
} ?>
<html lang="pt-BR" class="fixed">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>SISTEMA DE ARRANCHAMENTO</title>
        <link rel="apple-touch-icon" sizes="120x120" href="favicon/geleia.png">
        <link rel="icon" type="image/png" sizes="192x192" href="favicon/geleia.png">
        <link rel="icon" type="image/png" sizes="32x32" href="favicon/geleia.png">
        <link rel="icon" type="image/png" sizes="16x16" href="favicon/geleia.png">
        <style>
            @page {
                background-color: white;
            }
            table {
                font-weight: bold;
            }
            tr:nth-child(even){
                background-color: lightgray;
            }
            @media print {
                tr:nth-child(even){
                    background-color: lightgray;
                }
            }
            tr:nth-child(odd){
                background-color: white;
            }
        </style>
    </head>
    <?php if ($totalregistro > 0) {
// Abre tabela HTML        
        echo "<table border=1 width=100% cellpadding=3 cellspacing=0>\n";
        echo "<tr>";
        echo "<th align='center' valign='middle' width=14%><font size=0.5> MILITAR </font></th>";
        echo "<th align='center' valign='middle' width=10%><font size=0.5> SU </font></th>";
        echo "<th align='center' valign='middle' width=3%><font size=0.5>C</font></th>";
        echo "<th align='center' valign='middle' width=3%><font size=0.5>A</font></th>";
        echo "<th align='center' valign='middle' width=3%><font size=0.5>J</font></th>";
        echo "<th align='center' valign='middle' width=0.5%><font size=0.5>&nbsp;</font></th>";
        echo "<th align='center' valign='middle' width=14%><font size=0.5>MILITAR</font></th>";
        echo "<th align='center' valign='middle' width=10%><font size=0.5> SU </font></th>";
        echo "<th align='center' valign='middle' width=3%><font size=0.5>C</font></th>";
        echo "<th align='center' valign='middle' width=3%><font size=0.5>A</font></th>";
        echo "<th align='center' valign='middle' width=3%><font size=0.5>J</font></th>";
        echo "<th align='center' valign='middle' width=0.5%><font size=0.5>&nbsp;</font></th>";
        echo "<th align='center' valign='middle' width=14%><font size=0.5>MILITAR</font></th>";
        echo "<th align='center' valign='middle' width=10%><font size=0.5> SU </font></th>";
        echo "<th align='center' valign='middle' width=3%><font size=0.5>C</font></th>";
        echo "<th align='center' valign='middle' width=3%><font size=0.5>A</font></th>";
        echo "<th align='center' valign='middle' width=3%><font size=0.5>J</font></th>";
        echo "</tr>\n";
        for ($i = 0; $i < $coluna1; $i++) {
            $reg = $totrancho[$i];
            
            echo "<tr>";
            echo "<td align='center' valign='middle'><font size=0.5>" . ImprimeConsultaMilitar2($reg) . "</font></td>";
            echo "<td align='center' valign='middle'>";
            $SUnidade4 = listar_subunidades($reg['idsu'])[0];
            echo "<font size=0.5>" . $SUnidade4['descricao'] . "</font>";
            echo "</td>";
            echo "<td align='center' valign='middle'><font size=0.5>" . (($reg['cafe'] == "SIM") ? "X" : "") . "</font></td>";
            echo "<td align='center' valign='middle'><font size=0.5>" . (($reg['almoco'] == "SIM") ? "X" : "") . "</font></td>";
            echo "<td align='center' valign='middle'><font size=0.5>" . (($reg['jantar'] == "SIM") ? "X" : "") . "</font></td>";
            echo "<th align='center' valign='middle'><font size=0.5>&nbsp;</font></th>";
            if ($i < $coluna2) {
                $reg = $totrancho[($coluna1 + $i)];
                
                echo "<td align='center' valign='middle'><font size=0.5>" . ImprimeConsultaMilitar2($reg) . "</font></td>";
                echo "<td align='center' valign='middle'>";
                $SUnidade4 = listar_subunidades($reg['idsu'])[0];
                echo "<font size=0.5>" . $SUnidade4['descricao'] . "</font></td>";
                echo "<td align='center' valign='middle'><font size=0.5>" . (($reg['cafe'] == "SIM") ? "X" : "") . "</font></td>";
                echo "<td align='center' valign='middle'><font size=0.5>" . (($reg['almoco'] == "SIM") ? "X" : "") . "</font></td>";
                echo "<td align='center' valign='middle'><font size=0.5>" . (($reg['jantar'] == "SIM") ? "X" : "") . "</font></td>";
                echo "<th align='center' valign='middle'><font size=0.5>&nbsp;</font></th>";
            } else {
                echo "<td align='center' valign='middle'><font size=0.5>&nbsp;</font></td>";
                echo "<td align='center' valign='middle'><font size=0.5>&nbsp;</font></td>";
                echo "<td align='center' valign='middle'><font size=0.5>&nbsp;</font></td>";
                echo "<td align='center' valign='middle'><font size=0.5>&nbsp;</font></td>";
                echo "<td align='center' valign='middle'><font size=0.5>&nbsp;</font></td>";
                echo "<th align='center' valign='middle'><font size=0.5>&nbsp;</font></th>";
            }
            if ($i < $coluna3) {
                $reg = $totrancho[($coluna2 + $i)];
                
                echo "<td align='center' valign='middle'><font size=0.5>" . ImprimeConsultaMilitar2($reg) . "</font></td>";
                echo "<td align='center' valign='middle'>";
                $SUnidade4 = listar_subunidades($reg['idsu'])[0];
                echo "<font size=0.5>" . $SUnidade4['descricao'] . "</font></td>";
                echo "<td align='center' valign='middle'><font size=0.5>" . (($reg['cafe'] == "SIM") ? "X" : "") . "</font></td>";
                echo "<td align='center' valign='middle'><font size=0.5>" . (($reg['almoco'] == "SIM") ? "X" : "") . "</font></td>";
                echo "<td align='center' valign='middle'><font size=0.5>" . (($reg['jantar'] == "SIM") ? "X" : "") . "</font></td>";
            } else {
                echo "<td align='center' valign='middle'><font size=0.5>&nbsp;</font></td>";
                echo "<td align='center' valign='middle'><font size=0.5>&nbsp;</font></td>";
                echo "<td align='center' valign='middle'><font size=0.5>&nbsp;</font></td>";
                echo "<td align='center' valign='middle'><font size=0.5>&nbsp;</font></td>";
                echo "<td align='center' valign='middle'><font size=0.5>&nbsp;</font></td>";
            }
        }
        echo "</tr>";
        echo "</table>\n";
        // Fecha tabela
        //EXECUTA CONSULTA DE QUANTITATIVOS
        $qtdoficiais = $qtdstsgt = $qtdcbsd = 0;
        $cafeof = $cafestsgt = $cafecbsd = $almof = $almstsgt = $almcbsd = $jantof = $jantstsgt = $jantcbsd = 0;
        for ($i = 0; $i < count($totrancho); $i++) {
            $reg = $totrancho[$i];
            if ($reg['idpgrad'] <= 8) { $qtdoficiais++;
                if ($reg['cafe'] == "SIM")   {  $cafeof++; }
                if ($reg['almoco'] == "SIM") {  $almof++;  }
                if ($reg['jantar'] == "SIM") {  $jantof++; }
            }
            if ($reg['idpgrad'] > 8 && $reg['idpgrad'] < 13) { $qtdstsgt++;
                if ($reg['cafe'] == "SIM")   { $cafestsgt++; }
                if ($reg['almoco'] == "SIM") { $almstsgt++;  }
                if ($reg['jantar'] == "SIM") { $jantstsgt++; }
            }
            if ($reg['idpgrad'] >= 13) { $qtdcbsd++;
                if ($reg['cafe'] == "SIM")   { $cafecbsd++; }
                if ($reg['almoco'] == "SIM") { $almcbsd++;  }
                if ($reg['jantar'] == "SIM") { $jantcbsd++; }
            }
        }
        echo("<p>");
        echo "<table border=1 width=100% cellpadding=3 cellspacing=0>\n";
        echo "<tr>";
        echo "<th align='center' valign='middle' width=20%><font size=0.5> QUANTIDADE </font></th>";
        echo "<th align='center' valign='middle' width=20%><font size=0.5> CAFE </font></th>";
        echo "<th align='center' valign='middle' width=20%><font size=0.5> ALMOÇO </font></th>";
        echo "<th align='center' valign='middle' width=20%><font size=0.5> JANTAR </font></th>";
        echo "<th align='center' valign='middle' width=20%><font size=0.5> LISTADO </font></th>";
        echo("</tr>");

        echo "<tr>";
        echo "<th align='center' valign='middle' width=20%><font size=0.5> OFICIAIS </font></th>";
        echo "<th align='center' valign='middle' width=20%><font size=0.5> " . $cafeof . " </font></th>";
        echo "<th align='center' valign='middle' width=20%><font size=0.5> " . $almof . " </font></th>";
        echo "<th align='center' valign='middle' width=20%><font size=0.5> " . $jantof . " </font></th>";
        echo "<th align='center' valign='middle' width=20%><font size=0.5> " . $qtdoficiais . " </font></th>";
        echo("</tr>");

        echo "<tr>";
        echo "<th align='center' valign='middle' width=20%><font size=0.5> ST/SGT </font></th>";
        echo "<th align='center' valign='middle' width=20%><font size=0.5> " . $cafestsgt . " </font></th>";
        echo "<th align='center' valign='middle' width=20%><font size=0.5> " . $almstsgt . " </font></th>";
        echo "<th align='center' valign='middle' width=20%><font size=0.5> " . $jantstsgt . " </font></th>";
        echo "<th align='center' valign='middle' width=20%><font size=0.5> " . $qtdstsgt . " </font></th>";
        echo("</tr>");

        echo "<tr>";
        echo "<th align='center' valign='middle' width=20%><font size=0.5> CB/SD </font></th>";
        echo "<th align='center' valign='middle' width=20%><font size=0.5> " . $cafecbsd . " </font></th>";
        echo "<th align='center' valign='middle' width=20%><font size=0.5> " . $almcbsd . " </font></th>";
        echo "<th align='center' valign='middle' width=20%><font size=0.5> " . $jantcbsd . " </font></th>";
        echo "<th align='center' valign='middle' width=20%><font size=0.5> " . $qtdcbsd . " </font></th>";
        echo("</tr>");
        echo("</table>");
        $datahoje = date("d/m/Y");
        $horagora = date("H:i:s");
    } else { echo "</br><B>NÃO EXISTE LANÇAMENTOS PARA ESTA DATA.</B>\n"; }
    try { ?>
        <table border='1' cellpadding='2' cellspacing='0' style='width: 100%'>
            <tbody>
                <tr>
                    <td align="center">                        
                        <?php if (gerar_log_usuario('SISTEMA DE ARRANCHAMENTO', $subtitulo)) { ?>
                            <font face = 'font-family:trebuchet ms,helvetica,sans-serif;' size=2>Relatório gerado pelo <?= ImprimeConsultaMilitar2($_SESSION['auth_data']) ?> em <?= $datahoje ?> às <?= $horagora ?></font>
                            <?php } else { ?>
                            <font face = 'font-family:trebuchet ms,helvetica,sans-serif;' size=2>Erro ao gravar os dados.<br>Relatório gerado pelo <?= ImprimeConsultaMilitar2($_SESSION['auth_data']) ?> em <?= $datahoje ?> às <?= $horagora ?></font>
                            <?php }
                    } catch (PDOException $e) { } ?>    
                </td>
            </tr>
        </tbody>
    </table>
