<?php
require "../recursos/models/versession.php";
require "../recursos/models/conexao.php";
date_default_timezone_set("America/Cuiaba");

if (!isset($_POST['action'])) {
    header('Location: index.php');
    exit();
} else if (!$_SESSION['user_numconta'] == "3") {
    header('Location: index.php');
    exit();
}

$pdo = conectar("membros");
$subunidade = filter_input(INPUT_POST, "subunidade", FILTER_SANITIZE_STRING);
$tabela = "subunidade";
try {
    $stmte = $pdo->prepare("INSERT INTO $tabela(descricao) VALUES (:subunidade)");
    $stmte->bindParam(":subunidade", $subunidade, PDO::PARAM_STR);
    $executa = $stmte->execute();
    if ($executa) {
        $sistema = base64_encode("CONTROLE DE PESSOAL");
        $obs = "Cadastrou Subunidade " . $subunidade;
        $executa2 = gerar_log_usuario($sistema, $obs);
    } else {
        $msgerro = base64_encode('Erro na base de dados!');
        header("Location: cad_subunid.php?token=" . $msgerro);
        exit();
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}
$msgsuccess = base64_encode('Dados alterado com sucesso!');
header("Location: cad_subunid.php?token2=" . $msgsuccess);
exit();
