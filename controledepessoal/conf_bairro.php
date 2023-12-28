<?php
require "../recursos/models/versession.php";
require "../recursos/models/conexao.php";

$pdo = conectar("membros");

$bairro = filter_input(INPUT_POST, "bairro");
$setor = filter_input(INPUT_POST, "setor");
$tabela = "bairros";

try {
  $stmte = $pdo->prepare("INSERT INTO $tabela(bairro, setor) VALUES (:bairro, :setor)");
  $stmte->bindParam(":bairro", $bairro, PDO::PARAM_STR);
  $stmte->bindParam(":setor", $setor, PDO::PARAM_INT);
  $executa = $stmte->execute();
  if ($executa) {
    $sistema = base64_encode("CONTROLE DE PESSOAL");
    $obs = "Cadastro do Bairro ID" . $idBairro . " " . $nomeBairro;
    $executa2 = gerar_log_usuario($sistema, $obs);
  } else {
    $msgerro = base64_encode('Erro na base de dados!');
    header("Location: cad_bairro.php?token=" . $msgerro);
    exit();
  }
} catch (PDOException $e) {
  echo $e->getMessage();
}
$msgsuccess = base64_encode('Cadastro realizado com sucesso!');
header("Location: cad_bairro.php?token2=" . $msgsuccess);
exit();
