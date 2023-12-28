<?php

require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
date_default_timezone_set("America/Cuiaba");
$pdo_2 = conectar("arranchamento");
$datarancho = filter_input(INPUT_GET, "datarancho");
$qtduser = filter_input(INPUT_GET, "qtduser");
$horaini = date("H:i:s");
$grav = $atualz = 0;
$hoje = date("d/m/Y");
$quemgrava = getPGrad($_SESSION['auth_data']['idpgrad']) . " " . $_SESSION['auth_data']['nomeguerra'];
for ($i = 0; $i < $qtduser; $i++) {
    $userid = "userid" . $i;
    $m_userid = $_SESSION[$userid];
    $nomeguerra = "nomeguerra" . $i;
    $m_nomeguerra = $_SESSION[$nomeguerra];
    $useridpg = "useridpg" . $i;
    $m_useridpg = $_SESSION[$useridpg];
    $useridsu = "useridsu" . $i;
    $m_useridusu = $_SESSION[$useridsu];
    $m_cafe = filter_input(INPUT_POST, "ocafe" . $i . "");
    $m_almoco = filter_input(INPUT_POST, "oalmoco" . $i . "");
    $m_jantar = filter_input(INPUT_POST, "ojantar" . $i . "");
    if ($m_cafe != "SIM") {
        $m_cafe = "";
    }
    if ($m_almoco != "SIM") {
        $m_almoco = "";
    }
    if ($m_jantar != "SIM") {
        $m_jantar = "";
    }
    $filtrorancho = "SELECT * FROM arranchado WHERE iduser = :iduser and data = :data";
    $stmt = $pdo_2->prepare($filtrorancho);
    $stmt->bindParam(':iduser', $m_userid);
    $stmt->bindParam(':data', $datarancho);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($result) < 1) {
        if ($m_cafe == "" && $m_almoco == "" && $m_jantar == "") {
            
        } else {
            $modo = "Criado";
            $agora = date("H:i:s");
            $stmtez = $pdo_2->prepare("INSERT INTO arranchado(data, iduser, idpgrad, idsu, nomeguerra,"
                    . "cafe, almoco, jantar, "
                    . "datagrava, horagrava, quemgrava, modo) "
                    . "VALUES (:data, :iduser, :idpgrad, :idsu, :nomeguerra, "
                    . ":cafe, :almoco, :jantar, "
                    . ":datagrava, :horagrava, :quemgrava, :modo)");
            $stmtez->bindParam(":data", $datarancho, PDO::PARAM_STR);
            $stmtez->bindParam(":iduser", $m_userid, PDO::PARAM_INT);
            $stmtez->bindParam(":idpgrad", $m_useridpg, PDO::PARAM_INT);
            $stmtez->bindParam(":idsu", $m_useridusu, PDO::PARAM_INT);
            $stmtez->bindParam(":nomeguerra", $m_nomeguerra, PDO::PARAM_STR);
            $stmtez->bindParam(":cafe", $m_cafe, PDO::PARAM_STR);
            $stmtez->bindParam(":almoco", $m_almoco, PDO::PARAM_STR);
            $stmtez->bindParam(":jantar", $m_jantar, PDO::PARAM_STR);
            $stmtez->bindParam(":datagrava", $hoje, PDO::PARAM_STR);
            $stmtez->bindParam(":horagrava", $agora, PDO::PARAM_STR);
            $stmtez->bindParam(":quemgrava", $quemgrava, PDO::PARAM_STR);
            $stmtez->bindParam(":modo", $modo, PDO::PARAM_STR);
            $stmtez->execute();
            $grav++;
        }
    } else {
        $dadosrancho = $result[0];
        $modo = "Atualizado";
        $agora = date("H:i:s");
        if ($m_cafe <> $dadosrancho['cafe'] || $m_almoco <> $dadosrancho['almoco'] || $m_jantar <> $dadosrancho['jantar']) {
            $stmtup = $pdo_2->prepare("UPDATE arranchado SET idsu = :idsu, cafe = :cafe, almoco = :almoco, jantar = :jantar, datagrava = :datagrava, horagrava = :horagrava, quemgrava = :quemgrava, idpgrad = :idpgrad, nomeguerra = :nomeguerra, modo = :modo WHERE iduser = :iduser and data = :data");
            $stmtup->bindParam(":data", $datarancho, PDO::PARAM_STR);
            $stmtup->bindParam(":iduser", $m_userid, PDO::PARAM_INT);
            $stmtup->bindParam(":idpgrad", $m_useridpg, PDO::PARAM_INT);
            $stmtup->bindParam(":idsu", $m_useridusu, PDO::PARAM_INT);
            $stmtup->bindParam(":nomeguerra", $m_nomeguerra, PDO::PARAM_STR);
            $stmtup->bindParam(":cafe", $m_cafe, PDO::PARAM_STR);
            $stmtup->bindParam(":almoco", $m_almoco, PDO::PARAM_STR);
            $stmtup->bindParam(":jantar", $m_jantar, PDO::PARAM_STR);
            $stmtup->bindParam(":datagrava", $hoje, PDO::PARAM_STR);
            $stmtup->bindParam(":horagrava", $agora, PDO::PARAM_STR);
            $stmtup->bindParam(":quemgrava", $quemgrava, PDO::PARAM_STR);
            $stmtup->bindParam(":modo", $modo, PDO::PARAM_STR);
            $execup = $stmtup->execute();
            $atualz++;
        }
    }
}
$horafim = date("H:i:s");
$diftime = calculaTempo($horaini, $horafim);
$msgsuccess = base64_encode("Total de registros gravados: " . $grav . " ;  Total de registros atualizados: " . $atualz . " .");
header('Location: select.php?token2=' . $msgsuccess);