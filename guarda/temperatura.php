<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

// 1. verifico o período corrente e qual relatório gerar
$ano = date('Y');
$mes = date('m');
$relatorio = 'temp-sim'; // temp-sim, temp-rad, umid-sim, umid-rad 

if(isset($_GET['mes']) && is_numeric($_GET['mes'])) {
    $mes = str_pad($_GET['mes'], 2, '0', STR_PAD_LEFT);
}
if(isset($_GET['ano']) && is_numeric($_GET['ano'])) {
    $ano = str_pad($_GET['ano'], 2, '0', STR_PAD_LEFT);
}
if(isset($_GET['relatorio'])) {
    $relatorio = $_GET['relatorio'];
}

// 2. verifico o total de dias do mês
$numDias = diasNoMes($mes, $ano);

// 3. crio a array com o nome dos parametros
$params = array();

// 4. monto a query para a consulta sql
$query = '';
$mod = '';
$titulo = '';
if($relatorio == 'temp-sim') {
    $params = ['temp1', 'temp2', 'temp3'];
    $mod = 'ab';
    $titulo = 'Temperatura Simulador';
} else if($relatorio == 'temp-rad') {
    $params = ['temp5', 'temp6', 'temp7'];
    $mod = 'ab';
    $titulo = 'Temperatura Radar';
} else if($relatorio == 'umid-sim') {
    $params = ['umid1', 'umid2', 'umid3'];
    $mod = 'cd';
    $titulo = 'Umidade Simulador';
} else if($relatorio == 'umid-rad') {
    $params = ['umid5', 'umid6', 'umid7'];
    $mod = 'cd';
    $titulo = 'Umidade Radar';
} else {
    die('Erro inesperado');
}

// 5. Obter as medidas de temperatura
$pdo = conectar('guarda');
$query = 'SELECT liv_partes_ofdia.id, liv_partes_ofdia.data, liv_partes_ofdia.idleituras, 
    liv_partes_ofdia_leituras.'.$params[0].', liv_partes_ofdia_leituras.'.$params[1].', liv_partes_ofdia_leituras.'.$params[2].'
    FROM `liv_partes_ofdia` INNER JOIN liv_partes_ofdia_leituras ON idleituras = liv_partes_ofdia_leituras.id_leituras 
    WHERE liv_partes_ofdia.data LIKE "'.$ano.'-'.$mes.'%";';
$stmt = $pdo->prepare($query);
$stmt->execute();
$tmpt = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 6. Calculo a média diária
foreach($tmpt as $dia) {
    // print_r(substr($dia['data'], 8, 2));
    $temperaturas[substr($dia['data'], 8, 2)] = ($dia[$params[0]] + $dia[$params[1]] + $dia[$params[2]]) / 3;
}
// 7. Defino funções necessárias
function render_cels($temp, $mod, $array_temp, $numDias) {

    for($i=1; $i<=$numDias; $i++) {
        if($mod == 'a' || $mod == 'b') {
            if (floor($array_temp[str_pad($i , 2 , '0' , STR_PAD_LEFT)] ?? 0) == $temp) {
                if (round($array_temp[str_pad($i , 2 , '0' , STR_PAD_LEFT)] ?? 0) == $temp + 1 && $mod == 'a') {
                    echo '<td class="x">X</td>';
                } else if (round($array_temp[str_pad($i , 2 , '0' , STR_PAD_LEFT)] ?? 0) == $temp && $mod == 'b') {
                    echo '<td class="x">X</td>';
                } else {
                    echo '<td class="x"></td>';
                }
            } else {
                echo '<td class="x"></td>';
                
            } 
        } else if($mod == 'c' || $mod == 'd') { // crachando nessa parte: não exibe corretamente umidades
            if (round($array_temp[str_pad($i , 2 , '0' , STR_PAD_LEFT)] ?? 0) == $temp || round($array_temp[str_pad($i , 2 , '0' , STR_PAD_LEFT)] ?? 0) == $temp -1) {
                if (round($array_temp[str_pad($i , 2 , '0' , STR_PAD_LEFT)] ?? 0) == $temp && $mod == 'c') {
                    echo '<td class="x">x</td>';
                } else if ((round($array_temp[str_pad($i , 2 , '0' , STR_PAD_LEFT)] ?? 0) == ($temp-1)) && $mod == 'd') {
                    echo '<td class="x">x</td>';
                } else {
                    echo '<td class="x"></td>';
                }
            } else {
                echo '<td class="x"></td>';
                
            } 
        }
    }
}
function render_row_b($temp, $array_temp, $numDias, $mod){
    echo '<tr><td rowspan="2"></td>';
    render_cels($temp, $mod, $array_temp, $numDias);
    echo '</tr>';
    
}
function render_row_a($temp, $array_temp, $numDias, $mod){
    echo '<tr><td rowspan="2">'.$temp. ($mod == 'a' ? '°C' : '') .'</td>';
    render_cels($temp, $mod, $array_temp, $numDias);
    echo '</tr>';
}
function render_rows($t, $array_temp, $numDias, $mod) {
    render_row_a($t, $array_temp, $numDias, $mod[0]);
    render_row_b($t, $array_temp, $numDias, $mod[1]);
}
?>


<!DOCTYPE HTML>
<html>
<head>
<meta charset="UTF-8">
<title><?=$titulo?></title>
<style>
    td {
        border: solid black 1px;
        height: 14px;
        width: 14px;
    }
    table {
        width: 23cm;
        text-align: center;
        border: solid black 1px;
    }
    .sem_borda {
        border: none;
    }
    .align_start {
        text-align: start;
    }
    .title {
        font-size: 1.5em;
    }
    .bottom_align {

        vertical-align: bottom;
    }
    td.de_lado {
        height: 0px;
        width: 5px;
        line-height: 14px;
        padding-bottom: 0px;
    }
    div.de_lado {
        position: relative;
        width: 210px;
        margin-left: -90px;
        margin-right: -90px;
        transform: rotate(-90deg);
        -webkit-transform: rotate(-90deg); /* Safari/Chrome */
        -moz-transform: rotate(-90deg); /* Firefox */
        -o-transform: rotate(-90deg); /* Opera */
        -ms-transform: rotate(-90deg); /* IE 9 */
    }
    .x {
        font-size: 12px;
        font-weight: bold;
    }
</style>
</head>
<body>
    <?php render_cabecalho_documento(); ?>
    <table>
        <tr>
            <td class="sem_borda align_start">OM: 3ª BIA AAAe</td>
            <td class="sem_borda"></td>
            <td class="sem_borda">VISTO</td>
        </tr>
        <tr>
            <td class="sem_borda align_start"></td>
            <td class="sem_borda title">Controle de <?= $mod == 'ab' ? 'Temperatura' : 'Umidade' ?></td>
            <td class="sem_borda bottom_align">_____________________</td>
        </tr>
        <tr>
            <td class="sem_borda align_start"><?= strtoupper(mesPorExtenso($mes) . ' de ' . $ano)?></td>
            <td class="sem_borda"></td>
            <td class="sem_borda">Ch Seç Mnt AAe</td>
        </tr>
    </table>
    <table>
        <tbody>
            <tr>
                <td class="sem_borda de_lado" rowspan="46"><div class="de_lado"><?= $mod == 'ab' ? 'TEMPERATURA RELATIVA<br>DO AR (GRAU CELCIUS)' : 'UMIDADE RELATIVA<br>DO AR (%)' ?></div></td>
                <td rowspan="2"><?= $mod == 'ab' ? '37°C' : '76' ?></td>
                <td colspan="44"></td>
            </tr>
            <?php render_row_b($mod == 'ab' ? '37°C' : '76', $temperaturas, $numDias, 'a'); ?>
            <?php 
            if($mod == 'ab') {
                for ($t= 36; $t>=16; $t--) {
                    render_rows($t, $temperaturas, $numDias, 'ab');
                }
            } else {   
                for ($t= 74; $t>=34; $t-=2) {
                    render_rows($t, $temperaturas, $numDias, 'cd');
                } 
            }
                ?>
            <tr>
                <?php for($i=1; $i<=$numDias + 1; $i++) { ?>
                <td></td>
                <?php } ?>
            </tr>
            <tr>
                <td></td><td></td>
                <?php for($i=1; $i<=$numDias; $i++) { ?>
                <td><?= str_pad($i , 2 , '0' , STR_PAD_LEFT)?></td>
                <?php } ?>
            </tr>
        </tbody>
    </table>
</body>
</html>