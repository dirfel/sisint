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

$servico = filter_input(INPUT_POST, "servico", FILTER_SANITIZE_STRING);
$qtdservico = '0';
$tabela = "servico";
try {
  $stmte = $pdo->prepare("INSERT INTO $tabela(servico, qtdservico) VALUES (:servico, :qtdservico)");
  $stmte->bindParam(":servico", $servico, PDO::PARAM_STR);
  $stmte->bindParam(":qtdservico", $qtdservico, PDO::PARAM_INT);
  $executa = $stmte->execute();
  if ($executa) {
    $sistema = base64_encode('HELPDESK');
    $obs = "Cadastrou o Serviço " . $servico;
    $executa2 = gerar_log_usuario($sistema, $obs);
  } else {
    $msgerro = base64_encode('Erro na base de dados!');
    header("Location: cad_servicos.php?token=" . $msgerro);
    exit();
  }
} catch (PDOException $e) {
  echo $e->getMessage();
}
$msgsuccess = base64_encode('Dados alterado com sucesso!');
header("Location: cad_servicos.php?token2=" . $msgsuccess);
exit();
