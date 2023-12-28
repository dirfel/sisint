<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
if($_SESSION['nivel_fatos_observados'] == 'Sem Acesso') {
    header('Location: ../sistemas');
}
if (!isset($_POST['action'])) {
  header('Location: index.php');
  exit();
}

$militar = base64_decode(filter_input(INPUT_POST, "militar", FILTER_SANITIZE_SPECIAL_CHARS));

$dataatual = date("d/m/Y");
$horaatual = date("H:i:s");
$quemgrava = getPGrad($_SESSION['auth_data']['idpgrad']) . " " . $_SESSION['auth_data']['nomeguerra'];
$sistema = base64_encode('FATOS OBSERVADOS');
$foTipoToSet = ['B' => '<span style="color: red">NEGATIVO</span>', 'P' => '<span style="color: blue">POSITIVO</span>', 'N' => 'NEUTRO'];

if ($militar > 0 && is_int($militar)) {
  $obs = "Consultou FO: " .  consultaMilitar($militar);
  $titulo = "Consulta FO: " .  consultaMilitar($militar);
} else {
  $obs = "Consultou FO: " . $militar;
  $titulo = "Consulta FO: " . $militar;
}
$query = "";
if ($militar == 'TODOS OS MILITARES') { $query = "SELECT id, nomecompleto, nomeguerra, idpgrad FROM usuarios WHERE idpgrad >= 14 AND userativo = 'S' ORDER BY idpgrad, nomeguerra";
} else if ($militar == 'TODOS OS CABOS') { $query = "SELECT id, nomecompleto, nomeguerra, idpgrad FROM usuarios WHERE idpgrad = 14 AND userativo = 'S' ORDER BY idpgrad, nomeguerra";
} else if ($militar == 'TODOS OS SOLDADOS EP') { $query = "SELECT id, nomecompleto, nomeguerra, idpgrad FROM usuarios WHERE idpgrad = 15 AND userativo = 'S' ORDER BY idpgrad, nomeguerra";
} else if ($militar == 'TODOS OS SOLDADOS EV') { $query = "SELECT id, nomecompleto, nomeguerra, idpgrad FROM usuarios WHERE idpgrad = 16 AND userativo = 'S' ORDER BY idpgrad, nomeguerra";
} else if ($militar > 0) { $query = "SELECT id, nomecompleto, nomeguerra, idpgrad FROM usuarios WHERE id = :militar AND userativo = 'S'";
} else { header('Location: index.php?token='.base64_encode('Ocorreu um erro inexperado.')); exit();
}
$selectMil = conectar('membros')->prepare($query);
if (!($militar > 0)) { $selectMil->bindParam(":militar", $militar, PDO::PARAM_INT); }
$selectMil->execute();
$selectMilDados = $selectMil->fetchAll();
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="description" content="Sistema Fatos Observados <?=strtoupper(NOME_OM)?>">
  <meta name="author" content="Cap Stockey - 3ª Bia AAAe">
  <title><?= $titulo ?></title>
  <link rel="apple-touch-icon" sizes="120x120" href="../recursos/assets/favicon.png">
  <link rel="icon" type="image/png" sizes="192x192" href="../recursos/assets/favicon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="../recursos/assets/favicon.png">
  <link rel="icon" type="image/png" sizes="16x16" href="../recursos/assets/favicon.png">
  <style type="text/css" media="print"> @media print { @page { size: portrait } } </style>
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
        <font size=4> <?=strtoupper(NOME_OM)?> </font><br><br>
        <font size=3>Fatos Observados</font><br>
        <font size=2><?= $titulo ?></font><br>
      </th>
      <th valign='middle' width=10%>
        <font size=1 align='left'>Visto:</font><br /><br />
        <font size=1 align='center'>_______________</font><br />
        <font size=1 align='center'>Ch 1ª Seç</font><br />
      </th>
    </tr>
  </table>
  <?php for ($i = 0; $i < count($selectMilDados); $i++) { ?>
    <br>
    <table border=3 width=100% cellpadding=3 cellspacing=0>
      <tr>
        <th align='left' valign='middle' width=10%>
        <td align='left' valign='middle' width=80%>
          <font size=5>Nome completo: <?php echo consultaMilitar($selectMilDados[$i]["id"]) ?></font>
          <br>
          <?php $selectMilObs = conectar('membros')->prepare("SELECT * FROM fo WHERE fo.id_usuario = :id");
          $selectMilObs->bindParam(":id", $selectMilDados[$i]["id"], PDO::PARAM_INT);
          $selectMilObs->execute();
          $selectMilObsDados = $selectMilObs->fetchAll();
          if (count($selectMilObsDados) == 0) { ?> <font size=3 align='left' style="margin-left: 12px"> ◙ Nenhum FO </font> <?php }
          for ($ii = 0; $ii < count($selectMilObsDados); $ii++) { ?>
            <font size=3 align='left' style="margin-left: 12px"> ◙ FO - <?php echo strtr($selectMilObsDados[$ii]["tipo"], $foTipoToSet) ?> - Lançado por: <?php echo consultaMilitar($selectMilObsDados[$ii]["idmembro"]) ?> em 
            <?php echo $selectMilObsDados[$ii]["datahora"] ?>: <?php echo $selectMilObsDados[$ii]["obs"] ?> </font>
            <br><br>
          <?php } ?>
      </tr>
    </table>
  <?php } ?>
  <?php try { $mem_rel = gerar_log_usuario($sistema, $obs); ?>
    <table border='1' cellpadding='2' cellspacing='0' style='width: 100%'>
      <br>
      <tbody>
        <tr>
          <td align="center">
            <?php if ($mem_rel) { ?>
              <font face='font-family:trebuchet ms,helvetica,sans-serif;' size=2>
                Consulta gerado pelo <?= $quemgrava ?> em <?= $dataatual ?> às <?= $horaatual ?>
              </font>
            <?php } else { ?>
              <font face='font-family:trebuchet ms,helvetica,sans-serif;' size=2>
                Erro ao gravar os dados.<br>Consulta gerado pelo <?= $quemgrava ?> em <?= $dataatual ?> às <?= $horaatual ?>
              </font>
          <?php }
          } catch (PDOException $e) { echo $e->getMessage(); } ?>
          </td>
        </tr>
      </tbody>
    </table>

</body>

</html>