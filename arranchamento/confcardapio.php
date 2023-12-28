<?php

require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
$pdo2 = conectar("arranchamento");
$cardcafe = filter_input(INPUT_POST, "cardcafe");
$cardalmoco = filter_input(INPUT_POST, "cardalmoco");
$cardjantar = filter_input(INPUT_POST, "cardjantar");
$datacardapio = filter_input(INPUT_GET, "dtc");
date_default_timezone_set("America/Cuiaba");
$hoje = date("d/m/Y");
$agora = date("H:i:s");
$quemgrava = getPGrad($_SESSION['auth_data']['idpgrad']) . " " . $_SESSION['auth_data']['nomeguerra'];
$pesquisa = "SELECT * FROM cardapio WHERE data = :datacardapio";
$stmt = $pdo2->prepare($pesquisa);
$stmt->bindParam(':datacardapio', $datacardapio);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (count($result) < 1) {
    $stmtez = $pdo2->prepare("INSERT INTO cardapio(data, cafe, almoco, jantar, responsavel, datacadastro, horacadastro) "
            . "VALUES (:data, :cafe, :almoco, :jantar, :responsavel, :datacadastro, :horacadastro)");
    $stmtez->bindParam(":data", $datacardapio, PDO::PARAM_STR);
    $stmtez->bindParam(":cafe", $cardcafe, PDO::PARAM_STR);
    $stmtez->bindParam(":almoco", $cardalmoco, PDO::PARAM_STR);
    $stmtez->bindParam(":jantar", $cardjantar, PDO::PARAM_STR);
    $stmtez->bindParam(":responsavel", $quemgrava, PDO::PARAM_STR);
    $stmtez->bindParam(":datacadastro", $hoje, PDO::PARAM_STR);
    $stmtez->bindParam(":horacadastro", $agora, PDO::PARAM_STR);
    $executa = $stmtez->execute();
} else {
    $dadosrancho = $result[0];
    $stmtup = $pdo2->prepare("UPDATE cardapio SET cafe = :cafe, almoco = :almoco, jantar = :jantar, responsavel = :responsavel, datacadastro = :datacadastro, horacadastro = :horacadastro WHERE data = :data");
    $stmtup->bindParam(":data", $datacardapio, PDO::PARAM_STR);
    $stmtup->bindParam(":cafe", $cardcafe, PDO::PARAM_STR);
    $stmtup->bindParam(":almoco", $cardalmoco, PDO::PARAM_STR);
    $stmtup->bindParam(":jantar", $cardjantar, PDO::PARAM_STR);
    $stmtup->bindParam(":responsavel", $quemgrava, PDO::PARAM_STR);
    $stmtup->bindParam(":datacadastro", $hoje, PDO::PARAM_STR);
    $stmtup->bindParam(":horacadastro", $agora, PDO::PARAM_STR);
    $execup = $stmtup->execute();
}
$msgerro = base64_encode("Dados gravados/atualizados em " . $datarancho);
header('Location: cadcardapio.php?token=' . $msgerro);
?>