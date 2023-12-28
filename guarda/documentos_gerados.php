<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
setlocale(LC_TIME, 'portuguese'); 
date_default_timezone_set('America/Sao_Paulo');


if (!isset($_POST['action'])) {
  header('Location: documentos.php');
  exit();
}

$relatorio = filter_input(INPUT_POST, "relatorio", FILTER_SANITIZE_STRING);
$hora_inicial = filter_input(INPUT_POST, "hora_inicial", FILTER_SANITIZE_STRING);
$data_inicial = filter_input(INPUT_POST, "data_inicial", FILTER_SANITIZE_STRING);
$data_inicial2 = date_converter($data_inicial);
$hora_final = filter_input(INPUT_POST, "hora_final", FILTER_SANITIZE_STRING);
$data_final = date('d/m/Y', strtotime("+1 days", strtotime($data_inicial2)));
$data_final2 = date_converter($data_final);

if (!$relatorio) {
  $relatorio = base64_decode(filter_input(INPUT_GET, "token"));
}

$pdo1 = conectar("guarda");
$pdo2 = conectar("membros");

$dataatual = date("d/m/Y");
$horaatual = date("H:i:s");
$quemgrava = consultaMilitar3($_SESSION['auth_data']['id']);
$sistema = base64_encode('GUARDA');
$obs = "Gerou Documento: " . $relatorio;
$situacao_entrada_viaturas = '';
if ($relatorio == 'Pronto de Viaturas') {
  $consulta_rel = $pdo1->prepare("SELECT * FROM viatura WHERE situacao = '1' ORDER BY tipo, marca, modelo, placa ASC");
  $consulta_rel->execute();
  $consulta_total = $consulta_rel->fetchAll(PDO::FETCH_ASSOC);
  $consulta_total_registro = count($consulta_total);
} else if ($relatorio == 'Entrada e Saída de Militares Durante o Expediente') {
  $situacao_entrada = 'Entrou DURANTE o expediente';
  $situacao_saida = 'Saiu DURANTE o expediente';
  $tabela_rel = 'rel_militares';
  $consulta_rel = $pdo1->prepare("SELECT * FROM $tabela_rel 
        WHERE (situacao = :situacao1 OR situacao = :situacao2) AND (data = :datainicial OR data = :datafinal) 
        ORDER BY data, hora ASC");
  $consulta_rel->bindParam(':situacao1', $situacao_entrada, PDO::PARAM_STR);
  $consulta_rel->bindParam(':situacao2', $situacao_saida, PDO::PARAM_STR);
  $consulta_rel->bindParam(':datainicial', $data_inicial, PDO::PARAM_STR);
  $consulta_rel->bindParam(':datafinal', $data_final, PDO::PARAM_STR);
  $consulta_rel->execute();
  $consulta_rel_total = $consulta_rel->fetchAll(PDO::FETCH_ASSOC);
  $consulta_total_registro = count($consulta_rel_total);
} else if ($relatorio == 'Entrada e Saída de Militares Após o Expediente') {
  $situacao_entrada = 'Entrou APÓS o expediente';
  $situacao_saida = 'Saiu APÓS o expediente';
  $tabela_rel = 'rel_militares';
  $consulta_rel = $pdo1->prepare("SELECT * FROM $tabela_rel
        WHERE (situacao = :situacao1 OR situacao = :situacao2) AND (data = :datainicial OR data = :datafinal)
        ORDER BY data, hora ASC");
  $consulta_rel->bindParam(':situacao1', $situacao_entrada, PDO::PARAM_STR);
  $consulta_rel->bindParam(':situacao2', $situacao_saida, PDO::PARAM_STR);
  $consulta_rel->bindParam(':datainicial', $data_inicial, PDO::PARAM_STR);
  $consulta_rel->bindParam(':datafinal', $data_final, PDO::PARAM_STR);
  $consulta_rel->execute();
  $consulta_rel_total = $consulta_rel->fetchAll(PDO::FETCH_ASSOC);
  $consulta_total_registro = count($consulta_rel_total);
} else if ($relatorio == 'Entrada e Saída de Visitantes e Veículos') {
  $situacao_entrada = 'Entrou no Aquartelamento';
  $situacao_saida = 'Saiu do Aquartelamento';
  $tabela_rel = 'rel_visitantes';
  $tabela_veiculo = 'veiculo';
  $tabela_visit = 'visitante';
  $consulta_rel = $pdo1->prepare("SELECT $tabela_rel.idvisitante, $tabela_rel.destino, $tabela_rel.data, $tabela_rel.hora, $tabela_rel.situacao, $tabela_rel.idveiculo, $tabela_visit.nomecompleto, 
        $tabela_visit.tipo AS tipo_visit, $tabela_veiculo.placa, $tabela_veiculo.modelo, $tabela_veiculo.marca, $tabela_veiculo.tipo AS tipo_veiculo FROM $tabela_rel 
        LEFT JOIN $tabela_visit ON ($tabela_rel.idvisitante = $tabela_visit.id) LEFT JOIN $tabela_veiculo ON ($tabela_rel.idveiculo = $tabela_veiculo.id)
        WHERE (data = :datainicial OR data = :datafinal)
        ORDER BY data, hora ASC");
  $consulta_rel->bindParam(':datainicial', $data_inicial, PDO::PARAM_STR);
  $consulta_rel->bindParam(':datafinal', $data_final, PDO::PARAM_STR);
  $consulta_rel->execute();
  $consulta_rel_total = $consulta_rel->fetchAll(PDO::FETCH_ASSOC);
  $consulta_total_registro = count($consulta_rel_total);
} else if ($relatorio == 'Entrada e Saída de Viaturas Militares') {
  $rel_viaturas = 'rel_viaturas';
  $cad_viatura = 'viatura';
  $situacao_entrada_viaturas = 'Entrou no Aquartelamento';
  $situacao_saida_viaturas = 'Saiu do Aquartelamento';
  $consulta_rel = $pdo1->prepare("SELECT $rel_viaturas.id, $rel_viaturas.idusuario, $rel_viaturas.idvtr, $rel_viaturas.data, $rel_viaturas.hora, $rel_viaturas.situacao, $rel_viaturas.ficha, $rel_viaturas.idchvtr, $rel_viaturas.idmtr, 
                          $rel_viaturas.odometro, $rel_viaturas.destino, $rel_viaturas.idsaida, $cad_viatura.placa, $cad_viatura.modelo, $cad_viatura.marca, $cad_viatura.tipo AS tipo_veiculo, $cad_viatura.combustivel, $cad_viatura.consumo 
                          FROM $rel_viaturas LEFT JOIN $cad_viatura ON ($rel_viaturas.idvtr = $cad_viatura.id) 
                          WHERE ((data = :datainicial OR data = :datafinal) AND $rel_viaturas.idsaida is NOT NULL) ORDER BY $rel_viaturas.data, $rel_viaturas.hora ASC");
  $consulta_rel->bindParam(':datainicial', $data_inicial, PDO::PARAM_STR);
  $consulta_rel->bindParam(':datafinal', $data_final, PDO::PARAM_STR);
  $consulta_rel->execute();
  $consulta_rel_total = $consulta_rel->fetchAll(PDO::FETCH_ASSOC);
  $consulta_total_registro = count($consulta_rel_total);
} else if ($relatorio == 'Entrada e Saída no Alojamento de Cabo e Soldado') {
  $situacao_entrada = 'Entrou no Aloj Cb/Sd';
  $situacao_saida = 'Saiu do Aloj Cb/Sd';
  $tabela_rel = 'rel_alojamento';
  $consulta_rel = $pdo1->prepare("SELECT * FROM $tabela_rel WHERE (data = :datainicial OR data = :datafinal) 
        ORDER BY data, hora ASC");
  $consulta_rel->bindParam(':datainicial', $data_inicial, PDO::PARAM_STR);
  $consulta_rel->bindParam(':datafinal', $data_final, PDO::PARAM_STR);
  $consulta_rel->execute();
  $consulta_rel_total = $consulta_rel->fetchAll(PDO::FETCH_ASSOC);
  $consulta_total_registro = count($consulta_rel_total);
} else if ($relatorio == 'Militares e Visitantes que Pernoitaram na OM') {
  $tabela_rel = 'rel_pernoite';
  $consulta_rel = $pdo1->prepare("SELECT * FROM $tabela_rel
        WHERE data = :datainicial
        ORDER BY data ASC");
  $consulta_rel->bindParam(':datainicial', $data_inicial, PDO::PARAM_STR);
  $consulta_rel->execute();
  $consulta_rel_total = $consulta_rel->fetchAll(PDO::FETCH_ASSOC);
  $consulta_total_registro = count($consulta_rel_total);
} else if ($relatorio == 'Roteiro da Guarda e dos Postos') {
  $tabela_rel = 'rel_rot_guarda';
  $tabela_funcao = 'rot_guarda_funcao';
  $tabela_quarto = 'rot_guarda_quarto';
  $tabela_rel_postos = 'rel_rot_postos';
  $tabela_quartohora = 'rot_postos_quartohora';
  $consulta_rel = $pdo1->prepare("SELECT $tabela_rel.idmembro, $tabela_rel.armamento1, $tabela_rel.armamento2, $tabela_rel.num_armamento1, $tabela_rel.num_armamento2, 
        $tabela_rel.idquarto, $tabela_funcao.nomefuncao, $tabela_quarto.nomequarto FROM $tabela_rel LEFT JOIN $tabela_funcao ON ($tabela_rel.idfuncao = $tabela_funcao.idfuncao) 
        LEFT JOIN $tabela_quarto ON ($tabela_rel.idquarto = $tabela_quarto.idquarto)
        WHERE ($tabela_rel.data = :datainicial)
        ORDER BY $tabela_rel.idfuncao, $tabela_rel.idquarto ASC");
  $consulta_rel->bindParam(':datainicial', $data_inicial, PDO::PARAM_STR);
  $consulta_rel->execute();
  $consulta_rel_total = $consulta_rel->fetchAll(PDO::FETCH_ASSOC);
  $consulta_total_registro = count($consulta_rel_total);

  $consulta_postos = $pdo1->prepare("SELECT rel_rot_postos.id, rel_rot_postos.idusuario, rel_rot_postos.idquartohora, rel_rot_postos.data, rel_rot_postos.p1, rel_rot_postos.p2, 
    rel_rot_postos.p3, rel_rot_postos.p4, rel_rot_postos.p5, rel_rot_postos.p6, rel_rot_postos.aloj1, rel_rot_postos.aloj2, rot_postos_quartohora.quartohora 
    FROM rel_rot_postos LEFT JOIN rot_postos_quartohora ON (rel_rot_postos.idquartohora = rot_postos_quartohora.id) 
    WHERE (rel_rot_postos.data = :datainicial) ORDER BY rel_rot_postos.idquartohora ASC");
  $consulta_postos->bindParam(':datainicial', $data_inicial, PDO::PARAM_STR);
  $consulta_postos->execute();
  $consulta_postos_total = $consulta_postos->fetchAll(PDO::FETCH_ASSOC);
  $consulta_postos_count = count($consulta_postos_total);

} else if ($relatorio == 'Roteiro de Ronda e Permanência') {

 $tabela_rel = 'rel_rot_ronda';
  $tabela_funcao = 'rot_guarda_funcao';
  $consulta_rel = $pdo1->prepare(
      "SELECT * FROM $tabela_rel LEFT JOIN $tabela_funcao 
        ON ($tabela_rel.idfuncao = $tabela_funcao.idfuncao) 
      WHERE ((tipo != 'ronda' AND ((data_p = :datainicial AND hora_p >= '18:00') OR (data_p = :datafinal AND hora_p <= '06:00')))
          OR (tipo != 'perma' AND ((data_r = :datainicial AND hora_r >= '18:00') OR (data_r = :datafinal AND hora_r <= '06:00')))
   )   ORDER BY $tabela_rel.idfuncao ASC");
  $consulta_rel->bindParam(':datainicial', $data_inicial, PDO::PARAM_STR);
  $consulta_rel->bindParam(':datafinal', $data_final, PDO::PARAM_STR);
  $consulta_rel->execute();
  $consulta_rel_total = $consulta_rel->fetchAll(PDO::FETCH_ASSOC);
  $consulta_total_registro = count($consulta_rel_total);

} else if ($relatorio == 'Livro de Partes do Oficial de Dia') {
  $consulta_livroOfDia = $pdo1->prepare("SELECT * FROM liv_partes_ofdia 
    LEFT JOIN liv_partes_ofdia_leituras ON (liv_partes_ofdia.idleituras = liv_partes_ofdia_leituras.id_leituras)  
    LEFT JOIN liv_partes_ofdia_sobrasresiduos ON (liv_partes_ofdia.idsobrasresiduos = liv_partes_ofdia_sobrasresiduos.id_sobrasresiduos) 
    WHERE liv_partes_ofdia.data = :data");
  $consulta_livroOfDia->bindParam(':data', $data_inicial2, PDO::PARAM_STR);
  $consulta_livroOfDia->execute();
  $consulta_livroOfDia_reg = $consulta_livroOfDia->fetch(PDO::FETCH_ASSOC);

  if ($consulta_livroOfDia_reg['editar'] == 1) {
    $msgerro = base64_encode('Livro de Partes não foi FINALIZADO PARA IMPRESSÃO!');
    header('Location: documentos.php?token=' . $msgerro);
    exit();
  }

  $consulta_livroOfDiaPunidos = $pdo1->prepare("SELECT * FROM liv_partes_ofdia_punidos 
    WHERE (data_inicio <= :data AND data_termino > :data)");
  $consulta_livroOfDiaPunidos->bindParam(":data", $data_inicial2, PDO::PARAM_STR);
  $consulta_livroOfDiaPunidos->execute();
  $consulta_livroOfDiaPunidos_reg = $consulta_livroOfDiaPunidos->fetchAll(PDO::FETCH_ASSOC);
} else {
  header('Location: documentos.php');
  exit();
}

?>
<!doctype html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="description" content="Sistema Guarda <?=ABR_OM?>">
  <meta name="author" content="Cap Stockey">

  <?php if ($relatorio == 'Pronto de Viaturas') { ?>
    <title><?php echo $relatorio ?></title>
  <?php } else { ?>
    <title><?php echo ($relatorio . " de " . $data_inicial . " para " . $data_final) ?></title>
  <?php } ?>

  <link rel="apple-touch-icon" sizes="120x120" href="favicon/favicon_guarda.png">
  <link rel="icon" type="image/png" sizes="192x192" href="favicon/favicon_guarda.png">
  <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon_guarda.png">
  <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon_guarda.png">

  <?php if ($relatorio == 'Livro de Partes do Oficial de Dia') { ?>
    <style type="text/css" media="print">
      @media print {
        @page {
          size: portrait
        }
      }
    </style>
  <?php } else { ?>
    <style type="text/css" media="print">
      @media print {
        @page {
          size: landscape
        }
      }
    </style>
  <?php } ?>
</head>

<body>
  <table border=1 width=100% cellpadding=3 cellspacing=0>
    <tr>
      <th align='center' valign='middle' width=10%>
        <font size=1 align='left'>Visto:</font><br /><br />
        <font size=1>_______________</font><br />
        <font size=1>S Cmt</font><br />
      </th>
      <th align='center' valign='middle' width=80%>
        <font size=4> <?=strtoupper(NOME_OM)?> </font><br /><br />
        <?php echo "<font size=3> " . $relatorio . " </font><br />";
        if ($relatorio == 'Pronto de Viaturas') {
          echo "<font size=2> Serviço de " . $dataatual . " </font>";
        } else if ($relatorio == 'Livro de Partes do Oficial de Dia') {
          echo "<font size=2> Parte do Oficial de Dia ".PREPOSICAO." ".ABR_OM.", referente ao serviço de " . $data_inicial . " para " . $data_final . ", ao Sr Subcomandante. </font>";
        } else {
          echo "<font size=2> Relatório do Serviço de " . $data_inicial . " para " . $data_final . " </font>";
        } ?>
      </th>
      <th valign='middle' width=10%>
        <font size=1 align='left'>Visto:</font><br /><br />
        <font size=1 align='center'>_______________</font><br />
        <?php if ($relatorio == 'Pronto de Viaturas') { ?>
          <font size=1 align='center'>Ch Sec Mnt Trnp</font><br />
        <?php } else if ($relatorio == 'Livro de Partes do Oficial de Dia') { ?>
          <font size=1 align='center'>Fisc Adm</font><br />
        <?php } else { ?>
          <font size=1 align='center'>Of Dia</font><br />
        <?php } ?>
      </th>
    </tr>
  </table>
  <p>

    <?php if ($relatorio == 'Pronto de Viaturas') { ?>
      <table border=1 width=100% cellpadding=3 cellspacing=0>
        <tr>
          <th align='center' valign='middle' width=20%><font size=1.0> VIATURA </font></th>
          <th align='center' valign='middle' width=20%><font size=1.0> CHEFE DE VIATURA </font></th>
          <th align='center' valign='middle' width=20%><font size=1.0> MOTORISTA DA VIATURA </font></th>
          <th align='center' valign='middle' width=5%><font size=1.0> FICHA </font></th>
          <th align='center' valign='middle' width=10%><font size=1.0> DESTINO </font></th>
          <th align='center' valign='middle' width=10%><font size=1.0> ODÔMETRO </font></th>
          <th align='center' valign='middle' width=15%><font size=1.0> SAÍDA </font></th>
        </tr>
        <?php for ($i = 0; $i < $consulta_total_registro; $i++) {
          $reg_viaturas = $consulta_total[$i];
          
          $consulta4 = $pdo1->prepare("SELECT * FROM rel_viaturas WHERE idvtr = :id ORDER BY id DESC LIMIT 1");
          $consulta4->bindParam(":id", $reg_viaturas['id'], PDO::PARAM_INT);
          $consulta4->execute();
          while ($reg = $consulta4->fetch(PDO::FETCH_ASSOC)) {
            $data2 = $reg['data'];
            $hora = $reg['hora'];
            $ficha = $reg['ficha'];
            $odometro = $reg['odometro'];
            $destino = $reg['destino'];
          } ?>
          <tr>
            <th align='center' valign='middle' width=20%><font size=1.0> <?= ($reg_viaturas['placa'] . " - " . $reg_viaturas['modelo'] . " - " . $reg_viaturas['marca'] . " - (" . $reg_viaturas['tipo']) ?>)</font></th>
            <th align='center' valign='middle' width=20%><font size=1.0> <?= consultaMilitar3($reg_viaturas['idchvtr']) ?> </font></th>
            <th align='center' valign='middle' width=20%><font size=1.0> <?= consultaMilitar3($reg_viaturas['idmtr']) ?> </font></th>
            <th align='center' valign='middle' width=5%><font size=1.0> <?= ($ficha) ?> </font></th>
            <th align='center' valign='middle' width=10%><font size=1.0> <?= ($destino) ?> </font></th>
            <th align='center' valign='middle' width=10%><font size=1.0> <?= ($odometro) ?> Km </font></th>
            <th align='center' valign='middle' width=15%><?= ($reg_viaturas['situacao'] == $situacao_entrada_viaturas) ? "<font size=1.0> --- </font>" : "<font size=1.0> " . $data2 . " " . $hora . " </font>"; ?></th>
          </tr>
        <?php } ?>
      </table>
      <br>
      <table border=1 width=100% cellpadding=3 cellspacing=0>
        <tr>
          <th align='center' valign='middle'><font size=1.0> LACRE COMBUSTÍVEL 01 </font></th>
          <th align='center' valign='middle'><font size=1.0> LACRE COMBUSTÍVEL 02 </font></th>
        </tr>
        <tr>
          <th align='center' valign='middle'><br></th>
          <th align='center' valign='middle'><br></th>
        </tr>
      </table>
    <?php } else if ($relatorio == 'Entrada e Saída de Militares Durante o Expediente') {
      echo "<table border=1 width=100% cellpadding=3 cellspacing=0>\n";
      echo "<tr>";
      echo "<th align='center' valign='middle' width=37%><font size=1.0> MILITAR </font></th>";
      echo "<th align='center' valign='middle' width=37%><font size=1.0> SITUAÇÃO </font></th>";
      echo "<th align='center' valign='middle' width=10%><font size=1.0> ENTRADA </font></th>";
      echo "<th align='center' valign='middle' width=10%><font size=1.0> SAÍDA </font></th>";
      echo "</tr>\n";
      for ($i = 0; $i < $consulta_total_registro; $i++) {
        $reg = $consulta_rel_total[$i];
        echo "<tr>";
        echo "<th align='center' valign='middle' width=37%><font size=1.0> " . consultaMilitar3($reg['idmembro']) . " </font></th>";
        echo "<th align='center' valign='middle' width=37%><font size=1.0> " . $reg['situacao'] . " </font></th>";
        echo "<th align='center' valign='middle' width=10%><font size=1.0>" .($reg['situacao'] == $situacao_saida ? '---' : $reg['data'] . " " . $reg['hora'] ). "</font></th>";
        echo "<th align='center' valign='middle' width=10%><font size=1.0>" .($reg['situacao'] == $situacao_entrada ? '---' : $reg['data'] . " " . $reg['hora'] ). "</font></th>";
        echo "</tr>\n";
      }
      echo "</table>\n";
    } else if ($relatorio == 'Entrada e Saída de Militares Após o Expediente') {
      echo "<table border=1 width=100% cellpadding=3 cellspacing=0>\n";
      echo "<tr>";
      echo "<th align='center' valign='middle' width=37%><font size=1.0> MILITAR </font></th>";
      echo "<th align='center' valign='middle' width=37%><font size=1.0> SITUAÇÃO </font></th>";
      echo "<th align='center' valign='middle' width=10%><font size=1.0> ENTRADA </font></th>";
      echo "<th align='center' valign='middle' width=10%><font size=1.0> SAÍDA </font></th>";
      echo "</tr>\n";
      for ($i = 0; $i < $consulta_total_registro; $i++) {
        $reg = $consulta_rel_total[$i];
        echo "<tr>";
        echo "<th align='center' valign='middle' width=37%><font size=1.0> " . consultaMilitar3($reg['idmembro']) . " </font></th>";
        echo "<th align='center' valign='middle' width=37%><font size=1.0> " . $reg['situacao'] . " </font></th>";
        echo "<th align='center' valign='middle' width=10%>" .($reg['situacao'] == $situacao_saida ? '---' : $reg['data'] . " " . $reg['hora'] ). "</font></th>";
        echo "<th align='center' valign='middle' width=10%>" .($reg['situacao'] == $situacao_entrada ? '---' : $reg['data'] . " " . $reg['hora'] ). "</font></th>";
        echo "</tr>\n";
      }
      echo "</table>\n";
    } else if ($relatorio == 'Entrada e Saída de Visitantes e Veículos') {
      echo "<table border=1 width=100% cellpadding=3 cellspacing=0>\n";
      echo "<tr>";
      echo "<th align='center' valign='middle' width=25%><font size=1.0> VISITANTE </font></th>";
      echo "<th align='center' valign='middle' width=25%><font size=1.0> VEÍCULO </font></th>";
      echo "<th align='center' valign='middle' width=10%><font size=1.0> SITUAÇÃO </font></th>";
      echo "<th align='center' valign='middle' width=10%><font size=1.0> ENTRADA </font></th>";
      echo "<th align='center' valign='middle' width=10%><font size=1.0> SAÍDA </font></th>";
      echo "<th align='center' valign='middle' width=15%><font size=1.0> DESTINO </font></th>";
      echo "</tr>\n";
      for ($i = 0; $i < $consulta_total_registro; $i++) {
        $reg = $consulta_rel_total[$i];
        echo "<tr>";
        echo "<th align='center' valign='middle' width=25%><font size=1.0>" . $reg['nomecompleto'] . " (" . $reg['tipo_visit'] . ") </font></th>";
        echo "<th align='center' valign='middle' width=25%><font size=1.0>" . ($reg['idveiculo'] == 0 ? 'Nenhum Veículo' : ($reg['placa'] . " - " . $reg['modelo'] . " - " . $reg['marca'] . " - (" . $reg['tipo_veiculo'] . ")")) ."</font></th>";
        echo "<th align='center' valign='middle' width=25%><font size=1.0>" . $reg['situacao'] . " </font></th>";
        echo "<th align='center' valign='middle' width=10%><font size=1.0>". ($reg['situacao'] == $situacao_saida ? '---' : ($reg['data'] . " " . $reg['hora']) ) ."</font></th>";
        echo "<th align='center' valign='middle' width=10%><font size=1.0>". ($reg['situacao'] == $situacao_entrada ? '---' : ($reg['data'] . " " . $reg['hora']) ) ."</font></th>";
        echo "<th align='center' valign='middle' width=15%><font size=1.0> " . $reg['destino'] . " </font></th>";
        echo "</tr>\n";
      }
      echo "</table>\n";
    } else if ($relatorio == 'Entrada e Saída de Viaturas Militares') {
      echo "<table border=1 width=100% cellpadding=3 cellspacing=0>\n";
      echo "<tr>";
      echo "<th align='center' valign='middle' width=5%><font size=1.0> FICHA </font></th>";
      echo "<th align='center' valign='middle' width=15%><font size=1.0> VIATURA </font></th>";
      echo "<th align='center' valign='middle' width=15%><font size=1.0> CHEFE DE VIATURA </font></th>";
      echo "<th align='center' valign='middle' width=15%><font size=1.0> MOTORISTA DA VIATURA </font></th>";
      echo "<th align='center' valign='middle' width=10%><font size=1.0> DESTINO </font></th>";
      echo "<th align='center' valign='middle' width=5%><font size=1.0> DISTÂNCIA </font></th>";
      echo "<th align='center' valign='middle' width=5%><font size=1.0> CONSUMO </font></th>";
      echo "<th align='center' valign='middle' width=10%><font size=1.0> SAÍDA </font></th>";
      echo "<th align='center' valign='middle' width=10%><font size=1.0> ENTRADA </font></th>";
      echo "</tr>\n";
      for ($i = 0; $i < $consulta_total_registro; $i++) {
        $reg = $consulta_rel_total[$i];
        $consulta_rel_viaturas2 = $pdo1->prepare("SELECT data, hora, odometro FROM $rel_viaturas WHERE id = :id");
        $consulta_rel_viaturas2->bindParam(":id", $reg['idsaida'], PDO::PARAM_INT);
        $consulta_rel_viaturas2->execute();
        while ($reg2 = $consulta_rel_viaturas2->fetch(PDO::FETCH_ASSOC)) {
          $reg_viaturas2_data = $reg2['data'];
          $reg_viaturas2_hora = $reg2['hora'];
          $reg_viaturas2_odometro = $reg2['odometro'];
        }
        $ditancia = ($reg['odometro'] - $reg_viaturas2_odometro);
        $consumo = ($ditancia / $reg['consumo']);
        $consumo_total_d = 0;
        $consumo_total_g = 0;
        $distancia_total_d = 0;
        $distancia_total_g = 0;
        if ($reg['combustivel'] == 'G') {
          $consumo_total_g = $consumo_total_g + $consumo;
          $distancia_total_g = $distancia_total_g + $ditancia;
        } else {
          $consumo_total_d = $consumo_total_d + $consumo;
          $distancia_total_d = $distancia_total_d + $ditancia;
        }
        echo "<tr>";
        echo "<th align='center' valign='middle' width=5%><font size=1.0> " . $reg['ficha'] . " </font></th>";
        echo "<th align='center' valign='middle' width=15%><font size=1.0> " . $reg['placa'] . " - " . $reg['modelo'] . " - (" . $reg['tipo_veiculo'] . ") </font></th>";
        echo "<th align='center' valign='middle' width=15%><font size=1.0> " . consultaMilitar3($reg['idchvtr']) . " </font></th>";
        echo "<th align='center' valign='middle' width=15%><font size=1.0> " . consultaMilitar3($reg['idmtr']) . " </font></th>";
        echo "<th align='center' valign='middle' width=10%><font size=1.0> " . $reg['destino'] . " </font></th>";
        echo "<th align='center' valign='middle' width=5%><font size=1.0> " . $ditancia . " Km </font></th>";
        echo "<th align='center' valign='middle' width=5%><font size=1.0> " . number_format($consumo, 2, ',', '.') . "L " . $reg['combustivel'] . "</font></th>";
        echo "<th align='center' valign='middle' width=10%><font size=1.0> " . $reg_viaturas2_data . " " . $reg_viaturas2_hora . " (" . $reg_viaturas2_odometro . " Km) </font></th>";
        echo "<th align='center' valign='middle' width=10%><font size=1.0> " . $reg['data'] . " " . $reg['hora'] . " (" . $reg['odometro'] . " Km) </font></th>";
        echo "</tr>\n";
      }
      echo "</table>\n";
      echo "<br>";
      $distancia_total = $distancia_total_d + $distancia_total_g;
      echo "<table border=1 width=100% cellpadding=3 cellspacing=0>\n";
      echo "<tr>";
      echo "<th align='center' valign='middle'><font size=1.0> CONSUMO TOTAL: DIESEL </font></th>";
      echo "<th align='center' valign='middle'><font size=1.0> CONSUMO TOTAL: GASOLINA </font></th>";
      echo "<th align='center' valign='middle'><font size=1.0> DISTÂNCIA TOTAL </font></th>";
      echo "</tr>";
      echo "<tr>";
      echo "<th align='center' valign='middle'><font size=1.0> " .  number_format($consumo_total_d, 2, ',', '.') . "L Diesel </font></th>";
      echo "<th align='center' valign='middle'><font size=1.0> " . number_format($consumo_total_g, 2, ',', '.') . "L Gasolina </font></th>";
      echo "<th align='center' valign='middle'><font size=1.0> " . number_format($distancia_total, 2, ',', '.') . " Km </font></th>";
      echo "</tr>";
      echo "</table>\n";
    } else if ($relatorio == 'Entrada e Saída no Alojamento de Cabo e Soldado') {
      echo "<table border=1 width=100% cellpadding=3 cellspacing=0>\n";
      echo "<tr>";
      echo "<th align='center' valign='middle' width=37%><font size=1.0> MILITAR </font></th>";
      echo "<th align='center' valign='middle' width=37%><font size=1.0> SITUAÇÃO </font></th>";
      echo "<th align='center' valign='middle' width=10%><font size=1.0> ENTRADA </font></th>";
      echo "<th align='center' valign='middle' width=10%><font size=1.0> SAÍDA </font></th>";
      echo "</tr>\n";
      for ($i = 0; $i < $consulta_total_registro; $i++) {
        $reg = $consulta_rel_total[$i];
        echo "<tr>";
        echo "<th align='center' valign='middle' width=37%><font size=1.0> " . consultaMilitar3($reg['idmembro']) . " </font></th>";
        echo "<th align='center' valign='middle' width=37%><font size=1.0> " . $reg['situacao'] . " </font></th>";
        echo "<th align='center' valign='middle' width=10%><font size=1.0>". ($reg['situacao'] == $situacao_saida ? '---' : ($reg['data'] . " " . $reg['hora']) ) ."</font></th>";
        echo "<th align='center' valign='middle' width=10%><font size=1.0>". ($reg['situacao'] == $situacao_entrada ? '---' : ($reg['data'] . " " . $reg['hora']) ) ."</font></th>";
        echo "</tr>\n";
      }
      echo "</table>\n";
    } else if ($relatorio == 'Militares e Visitantes que Pernoitaram na OM') {
      echo "<table border=1 width=100% cellpadding=3 cellspacing=0>\n";
      echo "<tr>";
      echo "<th align='center' valign='middle' width=25%><font size=1.0> MILITAR </font></th>";
      echo "<th align='center' valign='middle' width=35%><font size=1.0> VISITANTE </font></th>";
      echo "<th align='center' valign='middle' width=20%><font size=1.0> ALOJAMENTO </font></th>";
      echo "<th align='center' valign='middle' width=10%><font size=1.0> DATA </font></th>";
      echo "</tr>\n";
      for ($i = 0; $i < $consulta_total_registro; $i++) {
        $reg = $consulta_rel_total[$i];
        $reg3 = get_visitante_by_id($reg['idvisitante']);
        $postograd_visitante = str_replace('Civil', '', (getPGrad($reg3['idpgrad']) ?? ''));
        $nome_visitante = $reg3['nomecompleto'];
        $tipo_visitante = $reg3['tipo'];
        echo "<tr>";
        echo "<th align='center' valign='middle' width=25%><font size=1.0>". ($reg['idmembro'] == 0 ? '---' : consultaMilitar3($reg['idmembro']) ) ."</font></th>";
        echo "<th align='center' valign='middle' width=35%><font size=1.0>". ($reg['idvisitante'] == 0 ? '---' : ($nome_visitante . " (" . $tipo_visitante . ")") ) ."</font></th>";
        echo "<th align='center' valign='middle' width=20%><font size=1.0> " . $reg['situacao'] . " </font></th>";
        echo "<th align='center' valign='middle' width=10%><font size=1.0> " . $reg['data'] . " </font></th>";
        echo "</tr>\n";
      }
      echo "</table>\n";
    } else if ($relatorio == 'Roteiro da Guarda e dos Postos') {
      echo "<table border=1 width=100% cellpadding=3 cellspacing=0><tr><th align='center' valign='middle'><font size=1.0> ROTEIRO DA GUARDA </font></th></tr></table>";
      echo "<table border=1 width=100% cellpadding=3 cellspacing=0>\n";
      echo "<tr>";
      echo "<th align='center' valign='middle' width=20%><font size=1.0> FUNÇÃO </font></th>";
      echo "<th align='center' valign='middle' width=35%><font size=1.0> MILITAR </font></th>";
      echo "<th align='center' valign='middle' width=35%><font size=1.0> ARMAMENTO </font></th>";
      echo "<th align='center' valign='middle' width=15%><font size=1.0> QUARTO HORA </font></th>";
      echo "</tr>\n";
      for ($i = 0; $i < $consulta_total_registro; $i++) {
        $reg = $consulta_rel_total[$i];
        echo "<tr>";
        echo "<th align='center' valign='middle' width=20%><font size=1.0> " . $reg['nomefuncao'] . " </font></th>";
        echo "<th align='center' valign='middle' width=35%><font size=1.0> " . consultaMilitar3($reg['idmembro']) . " </font></th>";
        echo "<th align='center' valign='middle' width=35%><font size=1.0>";
        if ($reg['num_armamento1'] == '0' and $reg['num_armamento2'] == '0') {
          echo "Sem armamento";
        } else if ($reg['num_armamento2'] == '0') {
          echo $reg['armamento1'] . ": " . $reg['num_armamento1'];
        } else if ($reg['num_armamento1'] == '0') {
          echo $reg['armamento2'] . ": " . $reg['num_armamento2'];
        } else {
          echo $reg['armamento1'] . ": " . $reg['num_armamento1'] . " / " . $reg['armamento2'] . ": " . $reg['num_armamento2'];
        }
        echo "</font></th>";
        echo "<th align='center' valign='middle' width=15%><font size=1.0>". ($reg['idquarto'] == 0 ? '---' : $reg['nomequarto']) ."</font></th>";
        echo "</tr>\n";
      }
      echo "</table>\n";
      echo "<br>";
      echo "<table border=1 width=100% cellpadding=3 cellspacing=0>\n";
      echo "<tr><th align='center' valign='middle'><font size=1.0> ROTEIRO DOS POSTOS </font></th></tr>";
      echo "</table>";
      
      echo "<table border=1 width=100% cellpadding=3 cellspacing=0>\n";
      echo "<tr>";
      echo "<th align='center' valign='middle' width=10%><font size=1.0> HORA </font></th>";
      echo "<th align='center' valign='middle'><font size=1.0> P1 </font></th>";
      echo "<th align='center' valign='middle'><font size=1.0> P2 </font></th>";
      echo "<th align='center' valign='middle'><font size=1.0> P3 </font></th>";
      echo "<th align='center' valign='middle'><font size=1.0> P4 </font></th>";
      echo "<th align='center' valign='middle'><font size=1.0> Aloj </font></th>";
      echo "<th align='center' valign='middle'><font size=1.0> Bia Msl </font></th>";
      echo "</tr>";
      for ($i = 0; $i < $consulta_postos_count; $i++) {
        $reg_postos = $consulta_postos_total[$i];
        $postos_sv = array('p1', 'p2', 'p3', 'p4', /*'p5', 'p6',*/ 'aloj1', 'aloj2');
        echo "<tr>";
        echo "<th align='center' valign='middle'><font size=1.0> " . $reg_postos['quartohora'] . " </font></th>";
        foreach($postos_sv as $posto_sv) {
          echo "<th align='center' valign='middle'><font size=1.0> " . (($reg_postos[$posto_sv] > 0) ? consultaMilitar3($reg_postos[$posto_sv]) : 'Sem Militar') . " </font></th>";
        }
        echo "</tr>";
      }
      echo "</table>\n";
    } else if ($relatorio == 'Roteiro de Ronda e Permanência') {
      /* Início da tabela permanencia */
        echo "<table border=1 width=100% cellpadding=3 cellspacing=0>\n";
        echo "<tr><th align='center' valign='middle' width=100%><font size=1.5> PERMANÊNCIA </font></th></tr>\n";
        echo "<table border=1 width=100% cellpadding=3 cellspacing=0>\n";
        echo "<tr>";
        echo "<th align='center' valign='middle' width=15%><font size=1.0> HORÁRIO </font></th>";
        echo "<th align='center' valign='middle' width=15%><font size=1.0> MILITAR </font></th>";
        echo "<th align='center' valign='middle' width=15%><font size=1.0> FUNÇÃO </font></th>";
        echo "<th align='center' valign='middle' width=7%><font size=1.0> ALTERAÇÃO </font></th>";
        echo "<th align='center' valign='middle' width=50%><font size=1.0> OBSERVAÇÕES </font></th>";
        echo "</tr>\n";
        for ($i = 0; $i < $consulta_total_registro; $i++) {
            $reg = $consulta_rel_total[$i];
            if($reg['tipo'] == 'ronda') {
                continue;
            }

          echo "<tr>";
          echo "<th align='center' valign='middle' width=15%><font size=1.0> " . $reg['hora_p'] . " " . $reg['data_p'] . " </font></th>";
          echo "<th align='center' valign='middle' width=15%><font size=1.0> " . consultaMilitar3($reg['idmembro']) . " </font></th>";
          echo "<th align='center' valign='middle' width=15%><font size=1.0> " . $reg['nomefuncao'] . " </font></th>";
          echo "<th align='center' valign='middle' width=7%><font size=1.0> ". ($reg['alteracao'] == 0 ? 'Sem' : 'Com') ." alteração </font></th>";
          echo "<th align='center' valign='middle' width=65%><font size=1.0> " . $reg['obs'] . " </font></th>";
          echo "</tr>\n";
        }
        echo "<table border=1 width=100% cellpadding=3 cellspacing=0>\n";
        echo ("<p>");
        echo "<tr><th align='center' valign='middle' width=100%><font size=1.5> RONDA </font></th></tr>\n";
        echo "<table border=1 width=100% cellpadding=3 cellspacing=0>\n";
        echo "<tr>";
        echo "<th align='center' valign='middle' width=15%><font size=1.0> HORÁRIO </font></th>";
        echo "<th align='center' valign='middle' width=15%><font size=1.0> RONDANTE </font></th>";
        echo "<th align='center' valign='middle' width=9%><font size=1.0> P1 </font></th>";
        echo "<th align='center' valign='middle' width=9%><font size=1.0> P2 </font></th>";
        echo "<th align='center' valign='middle' width=9%><font size=1.0> P3 </font></th>";
        echo "<th align='center' valign='middle' width=9%><font size=1.0> P4 </font></th>";
        echo "<th align='center' valign='middle' width=8%><font size=1.0> Aloj </font></th>";
        echo "<th align='center' valign='middle' width=8%><font size=1.0> Bia Msl </font></th>";
        echo "</tr>\n";
        for ($i = 0; $i < $consulta_total_registro; $i++) {
          $reg = $consulta_rel_total[$i];
          if($reg['data_r'] == null) {
            continue;
          }
          echo "<tr>";
          echo "<th align='center' valign='middle' width=15%><font size=1.0> " . $reg['hora_r'] . " " . $reg['data_r'] . " </font></th>";
          echo "<th align='center' valign='middle' width=15%><font size=1.0> " . consultaMilitar3($reg['idmembro']) . " </font></th>";
          $postos_sv = array('p1', 'p2', 'p3', 'p4', /*'p5', 'p6',*/ 'aloj1', 'aloj2');
          foreach($postos_sv as $posto_sv) {
            $mil = consultaMilitar3($reg[$posto_sv]);
            echo "<th align='center' valign='middle' width=9%><font size=1.0> " . (($mil == '' || $mil == ' ') ? '---' : $mil) . " </font></th>"; 
          }
          echo "</tr>\n";
        }        
    
    } else if ($relatorio == 'Livro de Partes do Oficial de Dia') { ?>
      <table border=1 width=100% cellpadding=3 cellspacing=0>
        <tr>
          <td width=100%><font size="2" style="font-weight: bold;">1. Recebimento do Serviço:</font>
            <font size="2"> Recebi do <?= consultaMilitar($consulta_livroOfDia_reg['idofdia_anterior']) ?>, com todas as ordens em vigor.</font>
            <br>
            <font size="2" style="font-weight: bold;">2. Pessoal de Serviço:</font>
            <font size="2"> De acordo com o publicado no BI Nr <?= $consulta_livroOfDia_reg['bi'] ?>, de <?= date_converter2($consulta_livroOfDia_reg['bi_data']) ?>.</font>
            <br>
            <font size="2" style="font-weight: bold;">3. Parada Diária:</font>
            <font size="2"> <?php if ($consulta_livroOfDia_reg['parada'] == "S") echo "Sem Alteração." ?></font>
            <font size="2"> <?php if ($consulta_livroOfDia_reg['parada'] == "C") echo "Com Alteração." ?></font>
            <font size="2"> <?php if ($consulta_livroOfDia_reg['parada'] == "N") echo "Não Realizado." ?></font>
            <br>
            <?php if ($consulta_livroOfDia_reg['parada_obs'] <> "") { ?>
              <font size="2" style="font-weight: bold; padding-left: 8px;">3.1. Observações da Parada Diária:</font>
              <font size="2"> <?= $consulta_livroOfDia_reg['parada_obs'] ?></font>
              <br>
            <?php } ?>
            <font size="2" style="font-weight: bold;">4. Punidos:</font>
            <font size="2"> <?= ($consulta_livroOfDia_reg['punidos'] == "S") ? "Sem Alteração." : "Com Alteração." ?></font>
            <br>
            <?php if (count($consulta_livroOfDiaPunidos_reg) > 0) { ?>
              <font size="2" style="font-weight: bold; padding-left: 8px;">4.1. Tabela de Punidos:</font>
              <br>
              <table border=1 width=100% cellpadding=3 cellspacing=0>
                <tr>
                  <th align='center' valign='middle'><font size="1" style="font-weight: bold;">Militar</font></th>
                  <th align='center' valign='middle'><font size="1" style="font-weight: bold;">Punição</font></th>
                  <th align='center' valign='middle'><font size="1" style="font-weight: bold;">Data de Início</font></th>
                  <th align='center' valign='middle'><font size="1" style="font-weight: bold;">Data de Término</font></th>
                  <th align='center' valign='middle'><font size="1" style="font-weight: bold;">BI</font></th>
                  <th align='center' valign='middle'><font size="1" style="font-weight: bold;">Nr BI</font></th>
                </tr>
                <?php for ($i = 0; $i < count($consulta_livroOfDiaPunidos_reg); $i++) { ?>
                  <tr>
                    <td align='center' valign='middle'><font size="1"><?= consultaMilitar($consulta_livroOfDiaPunidos_reg[$i]['idpunido']) ?></font></td>
                    <td align='center' valign='middle'><font size="1"><?= $consulta_livroOfDiaPunidos_reg[$i]['punicao'] ?></font></td>
                    <td align='center' valign='middle'><font size="1"><?= date_converter2($consulta_livroOfDiaPunidos_reg[$i]['data_inicio']) ?></font></td>
                    <td align='center' valign='middle'><font size="1"><?= date_converter2($consulta_livroOfDiaPunidos_reg[$i]['data_termino']) ?></font></td>
                    <td align='center' valign='middle'><font size="1"><?= $consulta_livroOfDiaPunidos_reg[$i]['p_bi'] ?></font></td>
                    <td align='center' valign='middle'><font size="1"><?= date_converter2($consulta_livroOfDiaPunidos_reg[$i]['p_bi_data']) ?></font></td>
                  </tr>
                <?php } ?>
              </table>
            <?php } ?>
            <font size="2" style="font-weight: bold;">5. Instalações:</font>
            <font size="2"> <?= ($consulta_livroOfDia_reg['instalacoes'] == "S") ? "Sem Alteração." : "Com Alteração." ?></font>
            <br>
            <?php if ($consulta_livroOfDia_reg['instalacoes_obs'] <> "") { ?>
              <font size="2" style="font-weight: bold; padding-left: 8px;">5.1. Observações das Instalações:</font>
              <font size="2"> <?= $consulta_livroOfDia_reg['instalacoes_obs'] ?></font>
              <br>
            <?php } ?>
            <font size="2" style="font-weight: bold;">6. Material Carga e Relacionado:</font>
            <font size="2"> <?= ($consulta_livroOfDia_reg['carga'] == "S") ? "Sem Alteração." : "Com Alteração." ?></font>
            <br>
            <?php if ($consulta_livroOfDia_reg['carga_obs'] <> "") { ?>
              <font size="2" style="font-weight: bold; padding-left: 8px;">6.1. Observações de Material Carga:</font>
              <font size="2"> <?= $consulta_livroOfDia_reg['carga_obs'] ?></font>
              <br>
            <?php } ?>
            <font size="2" style="font-weight: bold;">7. Leituras:</font>
            <table border=1 width=100% cellpadding=3 cellspacing=0>
              <tr>
                <th align='center' valign='middle'><font size="1" style="font-weight: bold;">Tipo de Leitura</font></th>
                <th align='center' valign='middle'><font size="1" style="font-weight: bold;">Anterior</font></th>
                <th align='center' valign='middle'><font size="1" style="font-weight: bold;">Leitura 13:30</font></th>
                <th align='center' valign='middle'><font size="1" style="font-weight: bold;">Leitura 18:00</font></th>
                <th align='center' valign='middle'><font size="1" style="font-weight: bold;">Leitura 06:30 D+1</font></th>
                <th align='center' valign='middle'><font size="1" style="font-weight: bold;">Cons Manhã</font></th>
                <th align='center' valign='middle'><font size="1" style="font-weight: bold;">Cons Tarde</font></th>
                <th align='center' valign='middle'><font size="1" style="font-weight: bold;">Cons Noite</font></th>
                <th align='center' valign='middle'><font size="1" style="font-weight: bold;">Cons Total</font></th>
              </tr>
              <tr>
                <td align='center' valign='middle'><font size="1" style="font-weight: bold;">Energia (Kw/h)</font></td>
                <td align='center' valign='middle'><font size="1"><?= number_format($consulta_livroOfDia_reg['energia_anterior'], 0, ',', '.') ?></font></td>
                <td align='center' valign='middle'><font size="1"><?= number_format($consulta_livroOfDia_reg['energia1'], 0, ',', '.') ?></font></td>
                <td align='center' valign='middle'><font size="1"><?= number_format($consulta_livroOfDia_reg['energia2'], 0, ',', '.') ?></font></td>
                <td align='center' valign='middle'><font size="1"><?= number_format($consulta_livroOfDia_reg['energia_atual'], 0, ',', '.') ?></font></td>
                <td align='center' valign='middle'><font size="1" style="font-weight: bold;"><?= number_format(($consulta_livroOfDia_reg['energia1'] - $consulta_livroOfDia_reg['energia_anterior']), 0, ',', '.') ?></font></td>
                <td align='center' valign='middle'><font size="1" style="font-weight: bold;"><?= number_format(($consulta_livroOfDia_reg['energia2'] - $consulta_livroOfDia_reg['energia1']), 0, ',', '.') ?></font></td>
                <td align='center' valign='middle'><font size="1" style="font-weight: bold;"><?= number_format(($consulta_livroOfDia_reg['energia_atual'] - $consulta_livroOfDia_reg['energia2']), 0, ',', '.') ?></font></td>
                <td align='center' valign='middle'><font size="1" style="font-weight: bold;"><?= number_format(($consulta_livroOfDia_reg['energia_atual'] - $consulta_livroOfDia_reg['energia_anterior']), 0, ',', '.') ?></font></td>
              </tr>
              <tr>
                <td align='center' valign='middle'><font size="1" style="font-weight: bold;">Água Externo (m³)</font></td>
                <td align='center' valign='middle'><font size="1"><?= number_format(($consulta_livroOfDia_reg['agua_ext_anterior'] / 100), 2, ',', '.') ?></font></td>
                <td align='center' valign='middle'><font size="1">-----</font></td>
                <td align='center' valign='middle'><font size="1">-----</font></td>
                <td align='center' valign='middle'><font size="1"><?= number_format(($consulta_livroOfDia_reg['agua_ext_atual'] / 100), 2, ',', '.') ?></font></td>
                <td align='center' valign='middle'><font size="1" style="font-weight: bold;">-----</font></td>
                <td align='center' valign='middle'><font size="1" style="font-weight: bold;">-----</font></td>
                <td align='center' valign='middle'><font size="1" style="font-weight: bold;">-----</font></td>
                <td align='center' valign='middle'><font size="1" style="font-weight: bold;"><?= number_format((($consulta_livroOfDia_reg['agua_ext_atual'] - $consulta_livroOfDia_reg['agua_ext_anterior']) / 100), 2, ',', '.') ?></font></td>
              </tr>
              <tr>
                <td align='center' valign='middle'><font size="1" style="font-weight: bold;">Água Interno (m³)</font></td>
                <td align='center' valign='middle'><font size="1"><?= number_format(($consulta_livroOfDia_reg['agua_int_anterior'] / 10), 1, ',', '.') ?></font></td>
                <td align='center' valign='middle'><font size="1">-----</font></td>
                <td align='center' valign='middle'><font size="1">-----</font></td>
                <td align='center' valign='middle'><font size="1"><?= number_format(($consulta_livroOfDia_reg['agua_int_atual'] / 10), 1, ',', '.') ?></font></td>
                <td align='center' valign='middle'><font size="1">-----</font></td>
                <td align='center' valign='middle'><font size="1">-----</font></td>
                <td align='center' valign='middle'><font size="1">-----</font></td>
                <td align='center' valign='middle'><font size="1" style="font-weight: bold;"><?= number_format((($consulta_livroOfDia_reg['agua_int_atual'] - $consulta_livroOfDia_reg['agua_int_anterior']) / 10), 1, ',', '.') ?></font></td>
              </tr>
              <!--  -->
              <tr>
                <td align='center' valign='middle'><font size="1" style="font-weight: bold;">Temp. Simulador RBS (ºC)</font></td>
                <td align='center' valign='middle'><font size="1">-----</font></td>
                <td align='center' valign='middle'><font size="1"><?= $consulta_livroOfDia_reg['temp1'] == null ? '-----' : number_format(($consulta_livroOfDia_reg['temp1'] > 100 ? $consulta_livroOfDia_reg['temp1'] / 10  : $consulta_livroOfDia_reg['temp1']), 1, ',', '.') ?></font></td>
                <td align='center' valign='middle'><font size="1"><?= $consulta_livroOfDia_reg['temp2'] == null ? '-----' : number_format(($consulta_livroOfDia_reg['temp2'] > 100 ? $consulta_livroOfDia_reg['temp2'] / 10  : $consulta_livroOfDia_reg['temp2']), 1, ',', '.') ?></font></td>
                <td align='center' valign='middle'><font size="1"><?= $consulta_livroOfDia_reg['temp3'] == null ? '-----' : number_format(($consulta_livroOfDia_reg['temp3'] > 100 ? $consulta_livroOfDia_reg['temp3'] / 10  : $consulta_livroOfDia_reg['temp3']), 1, ',', '.') ?></font></td>
                <td align='center' valign='middle'><font size="1">-----</font></td>
                <td align='center' valign='middle'><font size="1">-----</font></td>
                <td align='center' valign='middle'><font size="1">-----</font></td>
                <td align='center' valign='middle'><font size="1">-----</font></td>
              </tr>
              <tr>
                <td align='center' valign='middle'><font size="1" style="font-weight: bold;">Umid. Simulador RBS (%)</font></td>
                <td align='center' valign='middle'><font size="1">-----</font></td>
                <td align='center' valign='middle'><font size="1"><?= $consulta_livroOfDia_reg['umid1'] == null ? '-----' : number_format(($consulta_livroOfDia_reg['umid1'] > 100 ? $consulta_livroOfDia_reg['umid1'] / 10 : $consulta_livroOfDia_reg['umid1']), 1, ',', '.') ?></font></td>
                <td align='center' valign='middle'><font size="1"><?= $consulta_livroOfDia_reg['umid2'] == null ? '-----' : number_format(($consulta_livroOfDia_reg['umid2'] > 100 ? $consulta_livroOfDia_reg['umid2'] / 10 : $consulta_livroOfDia_reg['umid2']), 1, ',', '.') ?></font></td>
                <td align='center' valign='middle'><font size="1"><?= $consulta_livroOfDia_reg['umid3'] == null ? '-----' : number_format(($consulta_livroOfDia_reg['umid3'] > 100 ? $consulta_livroOfDia_reg['umid3'] / 10 : $consulta_livroOfDia_reg['umid3']), 1, ',', '.') ?></font></td>
                <td align='center' valign='middle'><font size="1">-----</font></td>
                <td align='center' valign='middle'><font size="1">-----</font></td>
                <td align='center' valign='middle'><font size="1">-----</font></td>
                <td align='center' valign='middle'><font size="1">-----</font></td>
              </tr>
              <!--  -->
              <tr>
                <td align='center' valign='middle'><font size="1" style="font-weight: bold;">Temp. RBS (ºC)</font></td>
                <td align='center' valign='middle'><font size="1">-----</font></td>
                <td align='center' valign='middle'><font size="1"><?= $consulta_livroOfDia_reg['temp5'] == null ? '-----' : number_format(($consulta_livroOfDia_reg['temp5'] > 100 ? $consulta_livroOfDia_reg['temp5'] / 10  : $consulta_livroOfDia_reg['temp5']), 1, ',', '.') ?></font></td>
                <td align='center' valign='middle'><font size="1"><?= $consulta_livroOfDia_reg['temp6'] == null ? '-----' : number_format(($consulta_livroOfDia_reg['temp6'] > 100 ? $consulta_livroOfDia_reg['temp6'] / 10  : $consulta_livroOfDia_reg['temp6']), 1, ',', '.') ?></font></td>
                <td align='center' valign='middle'><font size="1"><?= $consulta_livroOfDia_reg['temp7'] == null ? '-----' : number_format(($consulta_livroOfDia_reg['temp7'] > 100 ? $consulta_livroOfDia_reg['temp7'] / 10  : $consulta_livroOfDia_reg['temp7']), 1, ',', '.') ?></font></td>
                <td align='center' valign='middle'><font size="1">-----</font></td>
                <td align='center' valign='middle'><font size="1">-----</font></td>
                <td align='center' valign='middle'><font size="1">-----</font></td>
                <td align='center' valign='middle'><font size="1">-----</font></td>
              </tr>
              <tr>
                <td align='center' valign='middle'><font size="1" style="font-weight: bold;">Umid. RBS (%)</font></td>
                <td align='center' valign='middle'><font size="1">-----</font></td>
                <td align='center' valign='middle'><font size="1"><?= $consulta_livroOfDia_reg['umid5'] == null ? '-----' : number_format(($consulta_livroOfDia_reg['umid5'] > 100 ? $consulta_livroOfDia_reg['umid5'] / 10 : $consulta_livroOfDia_reg['umid5']), 1, ',', '.') ?></font></td>
                <td align='center' valign='middle'><font size="1"><?= $consulta_livroOfDia_reg['umid6'] == null ? '-----' : number_format(($consulta_livroOfDia_reg['umid6'] > 100 ? $consulta_livroOfDia_reg['umid6'] / 10 : $consulta_livroOfDia_reg['umid6']), 1, ',', '.') ?></font></td>
                <td align='center' valign='middle'><font size="1"><?= $consulta_livroOfDia_reg['umid7'] == null ? '-----' : number_format(($consulta_livroOfDia_reg['umid7'] > 100 ? $consulta_livroOfDia_reg['umid7'] / 10 : $consulta_livroOfDia_reg['umid7']), 1, ',', '.') ?></font></td>
                <td align='center' valign='middle'><font size="1">-----</font></td>
                <td align='center' valign='middle'><font size="1">-----</font></td>
                <td align='center' valign='middle'><font size="1">-----</font></td>
                <td align='center' valign='middle'><font size="1">-----</font></td>
              </tr>
              <!--  -->
            </table>
            <font size="2" style="font-weight: bold;">8. Rancho:</font>
            <font size="2"> <?= ($consulta_livroOfDia_reg['rancho'] == "S") ? "Sem Alteração." : "Com Alteração." ?></font>
            <br>
            <font size="2" style="font-weight: bold; padding-left: 8px;">8.1. Apresentação do Fisc Dia Rancho por início do SV:</font>
            <font size="2"> <?= ($consulta_livroOfDia_reg['rancho_fiscdia'] == "S") ? "Sem Alteração." : "Com Alteração." ?></font>
            <br>
            <font size="2" style="font-weight: bold; padding-left: 8px;">8.2. Apresentação do Coz-de-dia por término de SV:</font>
            <font size="2"> <?= ($consulta_livroOfDia_reg['rancho_cozdia'] == "S") ? "Sem Alteração." : "Com Alteração." ?></font>
            <br>
            <?php if ($consulta_livroOfDia_reg['rancho_obs'] <> "") { ?>
              <font size="2" style="font-weight: bold; padding-left: 8px;">8.3. Observações do Rancho:</font>
              <font size="2"> <?= $consulta_livroOfDia_reg['rancho_obs'] ?></font>
              <br>
            <?php } ?>
            <font size="2" style="font-weight: bold;">9. Abastecimento e Movimento de Viaturas fora do expediente:</font>
            <font size="2"> <?= ($consulta_livroOfDia_reg['abastecimento'] == "S") ? "Sem Alteração." : "Com Alteração." ?></font>
            <br>
            <?php if ($consulta_livroOfDia_reg['abastecimento_obs'] <> "") { ?>
              <font size="2" style="font-weight: bold; padding-left: 8px;">9.1. Observações do Abastecimento:</font>
              <font size="2"> <?= $consulta_livroOfDia_reg['abastecimento_obs'] ?></font>
              <br>
            <?php } ?>
            <font size="2" style="font-weight: bold;">10. Apresentação de Militares de deslocamentos para fora de sede:</font>
            <font size="2"> <?= ($consulta_livroOfDia_reg['apresentacaomil'] == "S") ? "Sem Alteração." : "Com Alteração." ?></font>
            <br>
            <?php if ($consulta_livroOfDia_reg['apresentacaomil_obs'] <> "") { ?>
              <font size="2" style="font-weight: bold; padding-left: 8px;">10.1. Observações de deslocamentos fora da sede:</font>
              <font size="2"> <?= $consulta_livroOfDia_reg['apresentacaomil_obs'] ?></font>
              <br>
            <?php } ?>
            <font size="2" style="font-weight: bold;">11. Ocorrências:</font>
            <font size="2"> <?= ($consulta_livroOfDia_reg['ocorrencias'] == "S") ? "Sem Alteração." : "Com Alteração." ?></font>
            <br>
            <?php if ($consulta_livroOfDia_reg['ocorrencias_obs'] <> "") { ?>
              <font size="2" style="font-weight: bold; padding-left: 8px;">11.1. Observações das Ocorrências:</font>
              <font size="2"> <?= $consulta_livroOfDia_reg['ocorrencias_obs'] ?></font>
              <br>
            <?php } ?>
            <font size="2" style="font-weight: bold;">12. Anexos:</font>
            <font size="2"> <?= $consulta_livroOfDia_reg['anexos'] ?></font>
            <br>
            <font size="2" style="font-weight: bold;">13. Passagem do Serviço:</font>
            <font size="2"> Fiz ao <?= consultaMilitar($consulta_livroOfDia_reg['idofdia_proximo']) ?>, com todas as ordens em vigor.</font>
            <br>
            <p style="font-size: small; text-align: center;">Quartel em <?= diaPorExtensoComLocal(date("d", strtotime($data_final2)), date("m", strtotime($data_final2)), date("Y", strtotime($data_final2)), CIDADE, UF) ?>.</p>
            <br>
            <p style="font-size: medium; text-align: center; margin-bottom: 0px; font-weight: bold"><?= consultaMilitarAssinatura($consulta_livroOfDia_reg['idofdia']) ?></p>
            <p style="font-size: medium; text-align: center; margin-top: 0px; font-weight: bold">Oficial de Dia <?=PREPOSICAO?> <?=ABR_OM?></p> 
          </td>
        </tr>
      </table>

    <?php } else {
      $msgerro = base64_encode('Erro na tentativa de gerar relatório!');
      header('Location: documentos.php?token=' . $msgerro);
      exit();
    }
    try { ?>
      <table border='1' cellpadding='2' cellspacing='0' style='width: 100%'>
        <br>
        <tbody>
          <tr>
            <td align="center"><font face='font-family:trebuchet ms,helvetica,sans-serif;' size=2>
              <?php if (gerar_log_usuario($sistema, $obs)) { ?>
                Documento gerado pelo <?= ($quemgrava) ?> em <?= $dataatual ?> às <?= $horaatual ?></font>
              <?php } else { ?>
                Erro ao gravar os dados.<br>Documento gerado pelo <?= $quemgrava ?> em <?= $dataatual ?> às <?= $horaatual ?></font>
            <?php }
            } catch (PDOException $e) {
              echo $e->getMessage();
            } ?>
            </td>
          </tr>
        </tbody>
      </table>
</body>
</html>