<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
if (!isset($_POST['action_edit'])) {
  header('Location: cadastros1.php');
  exit();
}
$id_rel = base64_decode(filter_input(INPUT_GET, "id", FILTER_SANITIZE_SPECIAL_CHARS));
$rel = base64_decode(filter_input(INPUT_GET, "rel", FILTER_SANITIZE_SPECIAL_CHARS));
$dataatual = date("d/m/Y");
$horaatual = date("H:i:s");
$situacao_editar = filter_input(INPUT_POST, "action_edit", FILTER_SANITIZE_STRING);
$tipo = filter_input(INPUT_POST, "tipo", FILTER_SANITIZE_STRING);
$marca = filter_input(INPUT_POST, "marca", FILTER_SANITIZE_STRING);
$cor = filter_input(INPUT_POST, "cor", FILTER_SANITIZE_STRING);
$placa = filter_input(INPUT_POST, 'placa', FILTER_SANITIZE_STRING);
$placa = mb_strtoupper($placa, 'UTF-8');
$modelo = filter_input(INPUT_POST, "modelo", FILTER_SANITIZE_STRING);
$modelo = mb_strtoupper($modelo, 'UTF-8');

$pdo1 = conectar("guarda");
$pdo2 = conectar("membros");

$sistema = base64_encode('GUARDA');
$obs = $situacao_editar . " ID" . $id_rel . " para: " . $placa . " - " . $modelo . " - " . $marca . " - " . $cor . " - (" . $tipo . ")";

$edit_rel = $pdo1->prepare("UPDATE $rel SET "
  . "tipo = :tipo, "
  . "marca = :marca, "
  . "cor = :cor, "
  . "placa = :placa, "
  . "modelo = :modelo WHERE id = :id");
$edit_rel->bindParam(":id", $id_rel, PDO::PARAM_INT);
$edit_rel->bindParam(":tipo", $tipo, PDO::PARAM_STR);
$edit_rel->bindParam(":marca", $marca, PDO::PARAM_STR);
$edit_rel->bindParam(":cor", $cor, PDO::PARAM_STR);
$edit_rel->bindParam(":placa", $placa, PDO::PARAM_STR);
$edit_rel->bindParam(":modelo", $modelo, PDO::PARAM_STR);

if (
  $id_rel == '' or $id_rel == ' ' or $id_rel == '0' or
  $rel == '' or $rel == ' '
) {
  $msgerro = base64_encode('Erro na tentativa de editar o cadastro!');
  header('Location: cadastros1.php?token=' . $msgerro);
  exit();
} else {
  $executa = $edit_rel->execute();
  $executa2 = gerar_log_usuario($sistema, $obs);
  $msgsuccess = base64_encode('Cadastro editado com sucesso!');
  header('Location: cadastros1.php?token2=' . $msgsuccess);
  exit();
}
