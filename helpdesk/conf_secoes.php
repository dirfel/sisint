<?php
require "../recursos/models/versession.php";
require "../recursos/models/conexao.php";

if (!isset($_POST['action'])) {
  header('Location: index.php');
  exit();
} else if ($_SESSION['nivel_helpdesk'] != "Administrador") {
  header('Location: index.php');
  exit();
}

$pdo = conectar("helpdesk");
$pdo2 = conectar("membros");

$secao = filter_input(INPUT_POST, "secao", FILTER_SANITIZE_STRING);
$qtdchamados = '0';
$tabela = "secao";
try {
  $stmte = $pdo->prepare("INSERT INTO $tabela(secao, qtdchamados) VALUES (:secao, :qtdchamados)");
  $stmte->bindParam(":secao", $secao, PDO::PARAM_STR);
  $stmte->bindParam(":qtdchamados", $qtdchamados, PDO::PARAM_INT);
  $executa = $stmte->execute();
  if ($executa) {
    $sistema = base64_encode('HELPDESK');
    $obs = "Cadastrou Seção " . $secao;
    $executa2 = gerar_log_usuario($sistema, $obs);
  } else {
    $msgerro = base64_encode('Erro na base de dados!');
    header("Location: cad_secoes.php?token=" . $msgerro);
    exit();
  }
} catch (PDOException $e) {
  echo $e->getMessage();
}
$msgsuccess = base64_encode('Dados alterado com sucesso!');
header("Location: cad_secoes.php?token2=" . $msgsuccess);
exit();
