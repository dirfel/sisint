<?php

require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
date_default_timezone_set("America/Cuiaba");
$pdo = conectar("membros");
$pdo2 = conectar("arranchamento");
$cafe = filter_input(INPUT_POST, "cafe");
$almoco = filter_input(INPUT_POST, "almoco");
$jantar = filter_input(INPUT_POST, "jantar");
$datarancho = filter_input(INPUT_POST, "datarancho");
$idusuario = filter_input(INPUT_POST, "usuario");
$convdata = strtotime(date_converter($datarancho));
$ds = date('D'); // pega o dia da semana da data atual

$dtlimite = date('Y-m-d', strtotime("+1 days"));
$dtcinco = date('Y-m-d', strtotime("+5 days"));
$dtquatro = date('Y-m-d', strtotime("+4 days"));
$dttres = date('Y-m-d', strtotime("+3 days"));
$erro = 0;
if ($convdata <= strtotime($dtlimite)) {
    $msgerro = base64_encode('DATA DEVE TER MAIS DE DOIS DIAS!');
    $erro = 1;
}

if ($ds == 'Thu') { //verifica se o dia da semana é quinta-feira
    if ($convdata < strtotime($dtquatro)) {
        $msgerro = base64_encode("ARRANCHAMENTO LIBERADO SOMENTE A PARTIR DE " . date('d/m/Y', strtotime($dtquatro)));
        $erro = 1;
    }
}

if ($ds == 'Fri') { //verifica se o dia da semana é sexta-feira
    if ($convdata <= strtotime($dttres)) {
        $msgerro = base64_encode("ARRANCHAMENTO LIBERADO SOMENTE A PARTIR DE " . date('d/m/Y', strtotime($dttres)));
        $erro = 1;
    }
}

if ($ds == 'Sat') { //verifica se o dia da semana é sábado
    if ($convdata <= strtotime($dttres)) {
        $msgerro = base64_encode("ARRANCHAMENTO LIBERADO SOMENTE A PARTIR DE " . date('d/m/Y', strtotime($dttres)));
        $erro = 1;
    }
}
if ($erro < 1) {
    $consulta = $pdo->prepare("SELECT * FROM usuarios WHERE id = $idusuario AND userativo = 'S'");
    $consulta->execute();
    $users = $consulta->fetchAll(PDO::FETCH_ASSOC);
    $user = $users[0];
    $nomeguerra = $user['nomeguerra'];
    $idpgrad = $user['idpgrad'];
    $idsu = $user['idsubunidade'];
    date_default_timezone_set("America/Cuiaba");
    $hoje = date("d/m/Y");
    $agora = date("H:i:s");
    $quemgrava = getPGrad($_SESSION['auth_data']['idpgrad']) . " " . $_SESSION['auth_data']['nomeguerra'];
    $iduser = $_SESSION['auth_data']['id'];
    if ($cafe != "SIM") {
        $cafe = "";
    }
    if ($almoco != "SIM") {
        $almoco = "";
    }
    if ($jantar != "SIM") {
        $jantar = "";
    }
    $pesquisa = "SELECT * FROM arranchado WHERE iduser = :idusuario and data = :data";
    $stmt = $pdo2->prepare($pesquisa);
    $stmt->bindParam(':idusuario', $idusuario);
    $stmt->bindParam(':data', $datarancho);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($result) < 1) {
        if ($cafe == "" && $almoco == "" && $jantar == "") {
            
        } else {
            $modo = "Criado";
            $stmtez = $pdo2->prepare("INSERT INTO arranchado(data, iduser, idpgrad, idsu, nomeguerra, cafe, almoco, jantar, datagrava, horagrava, quemgrava, modo) "
                    . "VALUES (:data, :iduser, :idpgrad, :idsu, :nomeguerra, :cafe, :almoco, :jantar, :datagrava, :horagrava, :quemgrava, :modo)");
            $stmtez->bindParam(":data", $datarancho, PDO::PARAM_STR);
            $stmtez->bindParam(":iduser", $idusuario, PDO::PARAM_INT);
            $stmtez->bindParam(":idpgrad", $idpgrad, PDO::PARAM_INT);
            $stmtez->bindParam(":idsu", $idsu, PDO::PARAM_INT);
            $stmtez->bindParam(":nomeguerra", $nomeguerra, PDO::PARAM_STR);
            $stmtez->bindParam(":cafe", $cafe, PDO::PARAM_STR);
            $stmtez->bindParam(":almoco", $almoco, PDO::PARAM_STR);
            $stmtez->bindParam(":jantar", $jantar, PDO::PARAM_STR);
            $stmtez->bindParam(":datagrava", $hoje, PDO::PARAM_STR);
            $stmtez->bindParam(":horagrava", $agora, PDO::PARAM_STR);
            $stmtez->bindParam(":quemgrava", $quemgrava, PDO::PARAM_STR);
            $stmtez->bindParam(":modo", $modo, PDO::PARAM_STR);
            $executa = $stmtez->execute();
            $msgerro = base64_encode("Dados cadastrados");
        }
    } else {
        $dadosrancho = $result[0];
        $modo = "Atualizado";
        if ($cafe <> $dadosrancho['cafe'] || $almoco <> $dadosrancho['almoco'] || $jantar <> $dadosrancho['jantar']) {
            $stmtup = $pdo2->prepare("UPDATE arranchado SET idsu = :idsu, cafe = :cafe, almoco = :almoco, jantar = :jantar, datagrava = :datagrava, horagrava = :horagrava, quemgrava = :quemgrava, idpgrad = :idpgrad, nomeguerra = :nomeguerra, modo = :modo WHERE iduser = :iduser and data = :data");
            $stmtup->bindParam(":idsu", $idsu, PDO::PARAM_STR);
            $stmtup->bindParam(":cafe", $cafe, PDO::PARAM_STR);
            $stmtup->bindParam(":almoco", $almoco, PDO::PARAM_STR);
            $stmtup->bindParam(":jantar", $jantar, PDO::PARAM_STR);
            $stmtup->bindParam(":datagrava", $hoje, PDO::PARAM_STR);
            $stmtup->bindParam(":horagrava", $agora, PDO::PARAM_STR);
            $stmtup->bindParam(":quemgrava", $quemgrava, PDO::PARAM_STR);
            $stmtup->bindParam(":idpgrad", $idpgrad, PDO::PARAM_INT);
            $stmtup->bindParam(":nomeguerra", $nomeguerra, PDO::PARAM_STR);
            $stmtup->bindParam(":modo", $modo, PDO::PARAM_STR);
            $stmtup->bindParam(":iduser", $idusuario, PDO::PARAM_INT);
            $stmtup->bindParam(":data", $datarancho, PDO::PARAM_STR);
            $execup = $stmtup->execute();
            $msgerro = base64_encode("Dados atualizados");
        }
    }
}
header('Location: individual.php?token=' . $msgerro);
?>