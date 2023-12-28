<?php

require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
$pdo2 = conectar("arranchamento");
$ocafe = filter_input(INPUT_POST, "ocafe");
$oalmoco = filter_input(INPUT_POST, "oalmoco");
$ojantar = filter_input(INPUT_POST, "ojantar");
$datarancho = filter_input(INPUT_GET, "dtr");
date_default_timezone_set("America/Cuiaba");
$hoje = date("d/m/Y");
$agora = date("H:i:s");
$quemgrava = getPGrad($_SESSION['auth_data']['idpgrad']) . " " . $_SESSION['auth_data']['nomeguerra'];
$iduser = $_SESSION['auth_data']['id'];
if($ocafe != "SIM"){
    $ocafe = "";
}
if($oalmoco != "SIM"){
    $oalmoco = "";
}
if($ojantar != "SIM"){
    $ojantar = "";
}
$pesquisa = "SELECT * FROM arranchado WHERE iduser = :iduser and data = :data";
$stmt = $pdo2->prepare($pesquisa);
$stmt->bindParam(':iduser', $iduser);
$stmt->bindParam(':data', $datarancho);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (count($result) < 1) {
    if ($ocafe == "" && $oalmoco == "" && $ojantar == "") {
        
    } else {
        $modo = "Criado";
        $stmtez = $pdo2->prepare("INSERT INTO arranchado(data, iduser, idpgrad, idsu, nomeguerra, cafe, almoco, jantar, datagrava, horagrava, quemgrava, modo) "
                . "VALUES (:data, :iduser, :idpgrad, :idsu, :nomeguerra, :cafe, :almoco, :jantar, :datagrava, :horagrava, :quemgrava, :modo)");
        $stmtez->bindParam(":data", $datarancho, PDO::PARAM_STR);
        $stmtez->bindParam(":iduser", $_SESSION['auth_data']['id'], PDO::PARAM_INT);
        $stmtez->bindParam(":idpgrad", $_SESSION['auth_data']['idpgrad'], PDO::PARAM_INT);
        $stmtez->bindParam(":idsu", $_SESSION['auth_data']['idsubunidade'], PDO::PARAM_INT);
        $stmtez->bindParam(":nomeguerra", $_SESSION['auth_data']['nomeguerra'], PDO::PARAM_STR);
        $stmtez->bindParam(":cafe", $ocafe, PDO::PARAM_STR);
        $stmtez->bindParam(":almoco", $oalmoco, PDO::PARAM_STR);
        $stmtez->bindParam(":jantar", $ojantar, PDO::PARAM_STR);
        $stmtez->bindParam(":datagrava", $hoje, PDO::PARAM_STR);
        $stmtez->bindParam(":horagrava", $agora, PDO::PARAM_STR);
        $stmtez->bindParam(":quemgrava", $quemgrava, PDO::PARAM_STR);
        $stmtez->bindParam(":modo", $modo, PDO::PARAM_STR);
        $executa = $stmtez->execute();
    }
} else {
    $dadosrancho = $result[0];
    $modo = "Atualizado";
    if ($ocafe <> $dadosrancho['cafe'] || $oalmoco <> $dadosrancho['almoco'] || $ojantar <> $dadosrancho['jantar']) {
        $stmtup = $pdo2->prepare("UPDATE arranchado SET idsu = :idsu, cafe = :cafe, almoco = :almoco, jantar = :jantar, datagrava = :datagrava, horagrava = :horagrava, quemgrava = :quemgrava, idpgrad = :idpgrad, nomeguerra = :nomeguerra, modo = :modo WHERE iduser = :iduser and data = :data");
        $stmtup->bindParam(":idsu", $_SESSION['auth_data']['idsubunidade'], PDO::PARAM_STR);
        $stmtup->bindParam(":cafe", $ocafe, PDO::PARAM_STR);
        $stmtup->bindParam(":almoco", $oalmoco, PDO::PARAM_STR);
        $stmtup->bindParam(":jantar", $ojantar, PDO::PARAM_STR);
        $stmtup->bindParam(":datagrava", $hoje, PDO::PARAM_STR);
        $stmtup->bindParam(":horagrava", $agora, PDO::PARAM_STR);
        $stmtup->bindParam(":quemgrava", $quemgrava, PDO::PARAM_STR);
        $stmtup->bindParam(":idpgrad", $_SESSION['auth_data']['idpgrad'], PDO::PARAM_INT);
        $stmtup->bindParam(":nomeguerra", $_SESSION['auth_data']['nomeguerra'], PDO::PARAM_STR);
        $stmtup->bindParam(":modo", $modo, PDO::PARAM_STR);
        $stmtup->bindParam(":iduser", $iduser, PDO::PARAM_INT);
        $stmtup->bindParam(":data", $datarancho, PDO::PARAM_STR);
        $execup = $stmtup->execute();
    }
}

$msgerro = base64_encode("Dados gravados/atualizados em ".$datarancho);
header('Location: index.php?token=' . $msgerro);
?>