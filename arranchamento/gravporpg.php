<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

$datarancho = filter_input(INPUT_POST, "datarancho");

// VERIFICA A DISPONIBILIDADE DE DATAS
$convdata = strtotime(date_converter($datarancho));

$ds = date('D'); // pega o dia da semana da data atual

$dtlimite = date('Y-m-d', strtotime("+1 days"));
$dtcinco = date('Y-m-d', strtotime("+5 days"));
$dtquatro = date('Y-m-d', strtotime("+4 days"));
$dttres = date('Y-m-d', strtotime("+3 days"));
$erro = 0;
if ($convdata < strtotime($dtlimite)) {
  $msgerro = base64_encode('DATA DEVE SER MAIOR QUE A ATUAL!');
  $erro = 1;
}

if ($ds == 'Fri') { //verifica se o dia da semana é sexta-feira
  if ($convdata < strtotime($dtquatro)) {
    $msgerro = base64_encode("ARRANCHAMENTO LIBERADO SOMENTE A PARTIR DE " . date('d/m/Y', strtotime($dttres)));
    $erro = 1;
  }
}

if ($ds == 'Sat') { //verifica se o dia da semana é sábado
  if ($convdata < strtotime($dttres)) {
    $msgerro = base64_encode("ARRANCHAMENTO LIBERADO SOMENTE A PARTIR DE " . date('d/m/Y', strtotime($dttres)));
    $erro = 1;
  }
}
if ($erro < 1) {
  $pdo = conectar("membros");
  $pdo2 = conectar("arranchamento");
  $cafe = filter_input(INPUT_POST, "cafe");
  $almoco = filter_input(INPUT_POST, "almoco");
  $jantar = filter_input(INPUT_POST, "jantar");
  $postogradid = filter_input(INPUT_POST, "postograd");
  $meuidsu = $_SESSION['auth_data']['idsubunidade'];
  $hoje = date("d/m/Y");
  $quemgrava = getPGrad($_SESSION['auth_data']['idpgrad']) . " " . $_SESSION['auth_data']['nomeguerra'];
  if ($cafe != "SIM") {
    $cafe = "";
  }
  if ($almoco != "SIM") {
    $almoco = "";
  }
  if ($jantar != "SIM") {
    $jantar = "";
  }
  if ($_SESSION['auth_data']['contarancho'] == "2") { // CONTA DO FURRIEL
    $consultausu = $pdo->prepare("SELECT * FROM usuarios WHERE idpgrad = :idpgrad AND idsubunidade = :idsubunidade AND userativo = 'S' ");
    $consultausu->bindParam(":idpgrad", $postogradid, PDO::PARAM_STR);
    $consultausu->bindParam(":idsubunidade", $meuidsu, PDO::PARAM_STR);
  } else {
    $consultausu = $pdo->prepare("SELECT * FROM usuarios WHERE idpgrad = :idpgrad AND userativo = 'S'");
    $consultausu->bindParam(":idpgrad", $postogradid, PDO::PARAM_STR);
  }
  $consultausu->execute();
  $qtdusers = $consultausu->fetchAll(PDO::FETCH_ASSOC);
  $qtd_users = count($qtdusers);
  $grav = $atualz = 0;
  $horaini = date("H:i:s");
  for ($i = 0; $i < $qtd_users; $i++) { //AQUI ESTÁ VARRENDO TODOS OS REGISTROS DO FILTRO EM USUARIOS
    $reg = $qtdusers[$i];
    //pega os dados de cada usuario
    //pega o posto/grad do usuario e pesquisa na base de dados postograd
    $pgradusu = $reg['idpgrad'];
    $nmguerrausu = $reg['nomeguerra'];
    $idusu = $reg['id'];
    $idsu = $reg['idsubunidade'];
    $consultarancho = $pdo2->prepare("SELECT * FROM arranchado WHERE iduser = :iduser AND data = :data");
    $consultarancho->bindParam(":iduser", $idusu, PDO::PARAM_STR);
    $consultarancho->bindParam(":data", $datarancho, PDO::PARAM_STR);
    $consultarancho->execute();
    $totrancho = $consultarancho->fetchAll(PDO::FETCH_ASSOC);
    if (count($totrancho) < 1) {
      // INSERT
      if ($cafe == "" && $almoco == "" && $jantar == "") {
      } else {
        $modo = "Criado";
        $agora = date("H:i:s");
        $stmtez = $pdo2->prepare("INSERT INTO arranchado(data, iduser, idpgrad, idsu, nomeguerra, cafe, almoco, jantar, datagrava, horagrava, quemgrava, modo) "
          . "VALUES (:data, :iduser, :idpgrad, :idsu, :nomeguerra, :cafe, :almoco, :jantar, :datagrava, :horagrava, :quemgrava, :modo)");
        $stmtez->bindParam(":data", $datarancho, PDO::PARAM_STR);
        $stmtez->bindParam(":iduser", $idusu, PDO::PARAM_INT);
        $stmtez->bindParam(":idpgrad", $pgradusu, PDO::PARAM_INT);
        $stmtez->bindParam(":idsu", $idsu, PDO::PARAM_INT);
        $stmtez->bindParam(":nomeguerra", $nmguerrausu, PDO::PARAM_STR);
        $stmtez->bindParam(":cafe", $cafe, PDO::PARAM_STR);
        $stmtez->bindParam(":almoco", $almoco, PDO::PARAM_STR);
        $stmtez->bindParam(":jantar", $jantar, PDO::PARAM_STR);
        $stmtez->bindParam(":datagrava", $hoje, PDO::PARAM_STR);
        $stmtez->bindParam(":horagrava", $agora, PDO::PARAM_STR);
        $stmtez->bindParam(":quemgrava", $quemgrava, PDO::PARAM_STR);
        $stmtez->bindParam(":modo", $modo, PDO::PARAM_STR);
        $executa = $stmtez->execute();
        $grav++;
      }
    } else {
      // UPDATE
      $dadosrancho = $totrancho[0];
      $modo = "Atualizado";
      $agora = date("H:i:s");
      if ($cafe <> $dadosrancho['cafe'] || $almoco <> $dadosrancho['almoco'] || $jantar <> $dadosrancho['jantar']) {
        $stmtup = $pdo2->prepare("UPDATE arranchado SET idsu = :idsu, cafe = :cafe, almoco = :almoco, jantar = :jantar, datagrava = :datagrava, horagrava = :horagrava, quemgrava = :quemgrava, idpgrad = :idpgrad, nomeguerra = :nomeguerra, modo = :modo WHERE iduser = :iduser and data = :data");
        $stmtup->bindParam(":idsu", $idsu, PDO::PARAM_INT);
        $stmtup->bindParam(":cafe", $cafe, PDO::PARAM_STR);
        $stmtup->bindParam(":almoco", $almoco, PDO::PARAM_STR);
        $stmtup->bindParam(":jantar", $jantar, PDO::PARAM_STR);
        $stmtup->bindParam(":datagrava", $hoje, PDO::PARAM_STR);
        $stmtup->bindParam(":horagrava", $agora, PDO::PARAM_STR);
        $stmtup->bindParam(":quemgrava", $quemgrava, PDO::PARAM_STR);
        $stmtup->bindParam(":idpgrad", $pgradusu, PDO::PARAM_INT);
        $stmtup->bindParam(":nomeguerra", $nmguerrausu, PDO::PARAM_STR);
        $stmtup->bindParam(":modo", $modo, PDO::PARAM_STR);
        $stmtup->bindParam(":iduser", $idusu, PDO::PARAM_INT);
        $stmtup->bindParam(":data", $datarancho, PDO::PARAM_STR);
        $execup = $stmtup->execute();
        $atualz++;
      }
    }
  }
}
$horafim = date("H:i:s");
$diftime = calculaTempo($horaini, $horafim);
$msgsuccess = base64_encode("Total de registros gravados: " . $grav . " ;  Total de registros atualizados: " . $atualz . " .");
header('Location: porpg.php?token2=' . $msgsuccess);
