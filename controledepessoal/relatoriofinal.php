<?php
require "../recursos/models/versession.php";
require "../recursos/models/conexao.php";
date_default_timezone_set("America/Cuiaba");
if ($_SESSION['nivel_plano_chamada'] == "Sem Acesso") {
    $msgerro = base64_encode('Usuário não possui acesso!');
    header('Location: ../sistemas/index.php?token=' . $msgerro);
    exit();
}
if (!isset($_POST['action'])) {
  header('Location: index.php');
  exit();
}

$pdo = conectar("membros");
$subunidade = filter_input(INPUT_POST, "subunidade");
$postograd = filter_input(INPUT_POST, "postograd");
$setor = filter_input(INPUT_POST, "setores");
$quemgrava = getPGrad($_SESSION['auth_data']['idpgrad']) . " " . $_SESSION['auth_data']['nomeguerra'];
$data = date('d/m/Y');
$hora = date('H:i:s');

if ($subunidade > 0) { // FOI ESCOLHIDO UMA SUBUNIDADE
  $SUnidade = listar_subunidades($subunidade)[0];

  if ($postograd == 0) { // ESCOLHIDO TODOS POSTO/GRADUAÇÃO
    $filtrorlt = $pdo->prepare("SELECT * FROM usuarios WHERE idsubunidade = :idsu AND userativo = 'S' ORDER BY idpgrad, nomecompleto ASC");
    $filtrorlt->bindParam(":idsu", $subunidade, PDO::PARAM_INT);
    $filtrorlt->execute();
    $qtdusers = $filtrorlt->fetchAll(PDO::FETCH_ASSOC);
    $qtd_users = count($qtdusers);
    $subtitulo = "RELATÓRIO COMPLETO - " . $SUnidade['descricao'];
  }
  if ($postograd == 1) { // ESCOLHIDO OFICIAIS
    $filtrorlt = $pdo->prepare("SELECT * FROM usuarios WHERE idsubunidade = :idsu AND userativo = 'S' AND idpgrad > 0 && idpgrad <= 8 ORDER BY idpgrad, nomecompleto ASC");
    $filtrorlt->bindParam(":idsu", $subunidade, PDO::PARAM_INT);
    $filtrorlt->execute();
    $qtdusers = $filtrorlt->fetchAll(PDO::FETCH_ASSOC);
    $qtd_users = count($qtdusers);
    $subtitulo = "RELATÓRIO DE TODOS OS OFICIAIS - " . $SUnidade['descricao'];
  }
  if ($postograd == 2) { // ST/SGT
    $filtrorlt = $pdo->prepare("SELECT * FROM usuarios WHERE idsubunidade = :idsu AND userativo = 'S' AND idpgrad > 8 && idpgrad < 13 ORDER BY idpgrad, nomecompleto ASC");
    $filtrorlt->bindParam(":idsu", $subunidade, PDO::PARAM_INT);
    $filtrorlt->execute();
    $qtdusers = $filtrorlt->fetchAll(PDO::FETCH_ASSOC);
    $qtd_users = count($qtdusers);
    $subtitulo = "RELATÓRIO DE TODOS OS SUBTENENTES E SARGENTOS - " . $SUnidade['descricao'];
  }
  if ($postograd == 3) { // ESCOLHIDO CB/SD
    $filtrorlt = $pdo->prepare("SELECT * FROM usuarios WHERE userativo = 'S' AND idsubunidade = :idsu AND idpgrad >= 13 ORDER BY idpgrad, nomecompleto ASC");
    $filtrorlt->bindParam(":idsu", $subunidade, PDO::PARAM_INT);
    $filtrorlt->execute();
    $qtdusers = $filtrorlt->fetchAll(PDO::FETCH_ASSOC);
    $qtd_users = count($qtdusers);
    $subtitulo = "RELATÓRIO DE TODOS OS CABOS E SOLDADOS - " . $SUnidade['descricao'];
  }
} else { // FOI ESCOLHIDO TODAS AS SU
  if ($postograd == 0) { // ESCOLHIDO TODOS POSTO/GRADUAÇÃO
    $filtrorlt = $pdo->prepare("SELECT * FROM usuarios WHERE userativo = 'S' ORDER BY idpgrad, nomecompleto ASC");
    $filtrorlt->execute();
    $qtdusers = $filtrorlt->fetchAll(PDO::FETCH_ASSOC);
    $qtd_users = count($qtdusers);
    $subtitulo = "RELATÓRIO COMPLETO";
  }
  if ($postograd == 1) { // ESCOLHIDO OF
    $filtrorlt = $pdo->prepare("SELECT * FROM usuarios WHERE idpgrad > 0 && idpgrad <= 8 AND userativo = 'S' ORDER BY idpgrad, nomecompleto ASC");
    $filtrorlt->bindParam(":data", $datarancho, PDO::PARAM_STR);
    $filtrorlt->execute();
    $qtdusers = $filtrorlt->fetchAll(PDO::FETCH_ASSOC);
    $qtd_users = count($qtdusers);
    $subtitulo = "RELATÓRIO COMPLETO DE TODOS OS OFICIAIS";
  }
  if ($postograd == 2) { // ESCOLHIDO ST/SGT
    $filtrorlt = $pdo->prepare("SELECT * FROM usuarios WHERE idpgrad > 8 && idpgrad < 13 AND userativo = 'S' ORDER BY idpgrad, nomecompleto ASC");
    $filtrorlt->bindParam(":data", $datarancho, PDO::PARAM_STR);
    $filtrorlt->execute();
    $qtdusers = $filtrorlt->fetchAll(PDO::FETCH_ASSOC);
    $qtd_users = count($qtdusers);
    $subtitulo = "RELATÓRIO COMPLETO DE TODOS OS SUBTENENTES E SARGENTOS";
  }
  if ($postograd == 3) { // ESCOLHIDO CB/SD
    $filtrorlt = $pdo->prepare("SELECT * FROM usuarios WHERE idpgrad >= 13 AND userativo = 'S' ORDER BY idpgrad, nomecompleto ASC");
    $filtrorlt->bindParam(":data", $datarancho, PDO::PARAM_STR);
    $filtrorlt->execute();
    $qtdusers = $filtrorlt->fetchAll(PDO::FETCH_ASSOC);
    $qtd_users = count($qtdusers);
    $subtitulo = "RELATÓRIO COMPLETO DE TODOS OS CABOS E SOLDADOS";
  }
}
?>
<!doctype html>
<html lang="pt-BR" class="fixed">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="description" content="Sistema CONTROLE DE PESSOAL 3ª Bia AAAe">

  <title><?php echo $subtitulo ?></title>

  <link rel="apple-touch-icon" sizes="120x120" href="../recursos/assets/favicon.png">
  <link rel="icon" type="image/png" sizes="192x192" href="../recursos/assets/favicon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="../recursos/assets/favicon.png">
  <link rel="icon" type="image/png" sizes="16x16" href="../recursos/assets/favicon.png">
</head>

<body>
  <table border='0' cellpadding='1' cellspacing='1' style='width: 100%'>
    <tbody>
      <tr>
        <td style='text-align: center;'>
          <span style='font-family:calibri;'><strong><big><?=NOME_OM?></big></strong></span></td>
      </tr>
      <tr>
        <td style='text-align: center;'>
          <span style='font-family:calibri;'>SISTEMA CONTROLE DE PESSOAL</span></td>
      </tr>
    </tbody>
  </table>
  <p>
    <table style='font-family:calibri; width: 100%;' border='1' cellpadding='2' cellspacing='0'>
      <tbody>
        <?php
        for ($i = 0; $i < $qtd_users; $i++) {
          $reg = $qtdusers[$i];
          $su4 = listar_subunidades($reg['idsubunidade'])[0];
          $consultaSetor = $pdo->prepare("SELECT bairros.bairro, setores.setor FROM bairros LEFT JOIN setores ON (bairros.setor = setores.id) WHERE bairros.id = :idBairros");
          $consultaSetor->bindParam(":idBairros", $reg['bairro'], PDO::PARAM_STR);
          $consultaSetor->execute();
          $consultaSetor_total = $consultaSetor->fetchAll(PDO::FETCH_ASSOC);
          $bairroConsulta = $consultaSetor_total[0]['bairro'];
          $setorConsulta = $consultaSetor_total[0]['setor'];
          echo ("<tr>");
          echo ("<td width='45%'>");
          echo ("<font face = 'font-family:calibri;' size=2>");
          echo ("<b>" . getPGrad($reg[['idpgrad']]) . " " . $reg['nomeguerra'] . "</b>" . " (" . $reg['nomecompleto'] . ")");
          echo ("<br>");
          echo ("<b>FRAÇÃO: </b>" . $su4['descricao'] . " -<b> DATA NASCIMENTO: </b>" . $reg['datanascimento']);
          echo ("</font>");
          echo ("</td>");
          echo ("<td width='55%'>");
          echo ("<font face = 'font-family:calibri;' size=2>");
          echo ("<b>ENDEREÇO: </b><a target='_blank' href='https://www.google.com.br/maps/place/".$reg['endereco']. " " . $reg['cidade'] ."'>" . $reg['endereco'] . "</a>, " . $bairroConsulta . ", " . $reg['cidade'] . ", " . $reg['estado']);
          echo (", <b>SETOR: </b>" . $setorConsulta);
          echo ("<br>");
          echo ("<b>TELEFONE FIXO: </b>" . $reg['fixo'] . ", <b>CELULAR: </b><a target='_blank' href='https://wa.me/55". str_replace("-", "", $reg['celular']) ."'>" . $reg['celular'] . "</a>, <b>EMAIL: </b>" . $reg['email']);
          echo ("</font>");
          echo ("</td>");
          echo ("</tr>");
        }
        ?>
      </tbody>
    </table>
    <p>
    </p>
    <br>
    <?php
    try {
      $sistema = base64_encode("CONTROLE DE PESSOAL");
      $obs = $subtitulo;
      $executa = gerar_log_usuario($sistema, $obs);
    ?>
      <table style='font-family:calibri; width: 100%;' border='1' cellpadding='2' cellspacing='0' style='width: 100%'>
        <tbody>
          <tr>
            <td align="center">
              <font size=2>
              <?php if ($executa) { ?>
                  Relatório gerado pelo <?php echo ($quemgrava); ?> em <?php echo ($data); ?> às <?php echo ($hora); ?>
                </font>
              <?php } else { ?>
                <font size=2>
                  Erro ao gravar os dados.<br>Relatório gerado pelo <?php echo ($quemgrava); ?> em <?php echo ($data); ?> às <?php echo ($hora); ?>
                  </font>
              <?php }
            } catch (PDOException $e) {
            //   echo $e->getMessage();
            echo 'Erro desconhecido, contate o administrador do sistema';
            } ?>
            </td>
          </tr>
        </tbody>
      </table>
  </body>
</html>