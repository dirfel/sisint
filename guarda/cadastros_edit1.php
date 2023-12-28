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
$nomecompleto = filter_input(INPUT_POST, "nomecompleto", FILTER_SANITIZE_STRING);
$nomecompleto = mb_strtoupper($nomecompleto, 'UTF-8');
$tipo = filter_input(INPUT_POST, "tipo", FILTER_SANITIZE_STRING);

$pdo1 = conectar("guarda");
$pdo2 = conectar("membros");

$sistema = base64_encode('GUARDA');
$obs = $situacao_editar . " ID" . $id_rel . " para: " . $nomecompleto . " - " . $identidade . " - (" . $tipo . ")";

$edit_rel = $pdo1->prepare("UPDATE $rel SET "
  . "identidade = :identidade, "
  . "nomecompleto = :nomecompleto, "
  . "tipo = :tipo WHERE id = :id");
$edit_rel->bindParam(":id", $id_rel, PDO::PARAM_INT);
$edit_rel->bindParam(":nomecompleto", $nomecompleto, PDO::PARAM_STR);
$edit_rel->bindParam(":tipo", $tipo, PDO::PARAM_STR);

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
