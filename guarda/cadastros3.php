<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
if (!isset($_POST['action'])) {
  header('Location: cadastros1.php');
  exit();
}
$id_rel = base64_decode(filter_input(INPUT_GET, "id", FILTER_SANITIZE_SPECIAL_CHARS));
$rel = base64_decode(filter_input(INPUT_GET, "rel", FILTER_SANITIZE_SPECIAL_CHARS));
$dataatual = date("d/m/Y");
$horaatual = date("H:i:s");
$situacao_excluir = filter_input(INPUT_POST, "action", FILTER_SANITIZE_STRING);

$pdo1 = conectar("guarda");
$pdo2 = conectar("membros");

$select_relatorio = $pdo1->prepare("SELECT placa, modelo, marca, cor, tipo
        FROM $rel WHERE id = :id");
$select_relatorio->bindParam(":id", $id_rel, PDO::PARAM_INT);
$select_relatorio->execute();
while ($reg = $select_relatorio->fetch(PDO::FETCH_ASSOC)) {
  $placa = $reg['placa'];
  $modelo = $reg['modelo'];
  $marca = $reg['marca'];
  $cor = $reg['cor'];
  $tipo = $reg['tipo'];
}

$sistema = base64_encode('GUARDA');
$obs = $situacao_excluir . " ID" . $id_rel . ": " . $placa . " - " . $modelo . " - " . $marca . " - " . $cor . " - (" . $tipo . ")";

$excluir_rel = $pdo1->prepare("DELETE FROM $rel WHERE id = :id");
$excluir_rel->bindParam(":id", $id_rel, PDO::PARAM_INT);

if (
  $id_rel == '' or $id_rel == ' ' or $id_rel == '0' or
  $rel == '' or $rel == ' '
) {
  $msgerro = base64_encode('Erro na tentativa de excluir o cadastro!');
  header('Location: cadastros1.php?token=' . $msgerro);
  exit();
} else {
  $executa = $excluir_rel->execute();
  $executa2 = gerar_log_usuario($sistema, $obs);
  $msgsuccess = base64_encode('Cadastro excluído com sucesso!');
  header('Location: cadastros1.php?token2=' . $msgsuccess);
  exit();
}
