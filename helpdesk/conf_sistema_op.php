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

$sistema_op = filter_input(INPUT_POST, "sistema_op", FILTER_SANITIZE_STRING);
$qtdsisop = '0';
try {
  $stmte = $pdo->prepare("INSERT INTO sistoper (sistema, qtdsisop) VALUES (:sistema, :qtdsisop)");
  $stmte->bindParam(":sistema", $sistema_op, PDO::PARAM_STR);
  $stmte->bindParam(":qtdsisop", $qtdsisop, PDO::PARAM_INT);
  $executa = $stmte->execute();
  if ($executa) {
    $sistema = base64_encode('HELPDESK');
    $obs = "Cadastrou Sistema Operacional " . $sistema_op;
    $executa2 = gerar_log_usuario($sistema, $obs);
  } else {
    $msgerro = base64_encode('Erro na base de dados!');
    header("Location: cad_sistema_op.php?token=" . $msgerro);
    exit();
  }
} catch (PDOException $e) {
  echo $e->getMessage();
}
$msgsuccess = base64_encode('Dados alterado com sucesso!');
header("Location: cad_sistema_op.php?token2=" . $msgsuccess);
exit();
