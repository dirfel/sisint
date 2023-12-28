<?php
require "../recursos/models/versession.php";
require "../recursos/models/conexao.php";

if (!isset($_POST['btn_conf_bairro_edit'])) {
  header('Location: index.php');
  exit();
} else if (!$_SESSION['user_numconta'] >= "2") {
  header('Location: index.php');
  exit();
}

$idBairro = base64_decode(filter_input(INPUT_GET, "token", FILTER_SANITIZE_STRING));
$nomeBairro = filter_input(INPUT_POST, "nomeBairro", FILTER_SANITIZE_STRING);
$setorBairro = filter_input(INPUT_POST, "setorBairro", FILTER_SANITIZE_NUMBER_INT);
$tabela = "bairros";
$pdo = conectar("membros");
try {
  $stmte = $pdo->prepare("UPDATE $tabela SET bairro = :bairro, setor = :setor WHERE id = :id");
  $stmte->bindParam(":id", $idBairro, PDO::PARAM_INT);
  $stmte->bindParam(":bairro", $nomeBairro, PDO::PARAM_STR);
  $stmte->bindParam(":setor", $setorBairro, PDO::PARAM_INT);
  $executa = $stmte->execute();
  if ($executa) {
    $sistema = base64_encode("CONTROLE DE PESSOAL");
    $obs = "Dados atualizados do Bairro ID" . $idBairro . " " . $nomeBairro;
    $executa2 = gerar_log_usuario($sistema, $obs);
  } else {
    $msgerro = base64_encode('Erro na base de dados!');
    header("Location: cad_bairro.php?token=" . $msgerro);
    exit();
  }
} catch (PDOException $e) {
  echo $e->getMessage();
}
$msgsuccess = base64_encode('Dados alterado com sucesso!');
header("Location: cad_bairro.php?token2=" . $msgsuccess);
exit();
