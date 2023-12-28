<?php
require "../recursos/models/versession.php";
require "../recursos/models/conexao.php";

if (!isset($_POST['action'])) {
  header('Location: index.php');
  exit();
} else if (!$_SESSION['user_numconta'] >= "2") {
  header('Location: index.php');
  exit();
}
$setor = filter_input(INPUT_POST, "setor", FILTER_SANITIZE_STRING);
try {
  $executa = criar_setor_de_bairro($setor);
  if ($executa) {
    $executa2 = gerar_log_usuario("CONTROLE DE PESSOAL", "Cadastrou Setor " . $setor);
  } else {
    $msgerro = base64_encode('Erro na base de dados!');
    header("Location: cad_setor.php?token=" . $msgerro);
    exit();
  }
} catch (PDOException $e) {
  echo $e->getMessage();
}
$msgsuccess = base64_encode('Dados alterado com sucesso!');
header("Location: cad_setor.php?token2=" . $msgsuccess);
exit();
