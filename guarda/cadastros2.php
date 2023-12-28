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

$select_relatorio = $pdo1->prepare("SELECT $rel.nomecompleto, $rel.identidade, $rel.tipo 
        FROM $rel WHERE $rel.id = :id");
$select_relatorio->bindParam(":id", $id_rel, PDO::PARAM_INT);
$select_relatorio->execute();
while ($reg = $select_relatorio->fetch(PDO::FETCH_ASSOC)) {
  $nomecompleto = $reg['nomecompleto'];
  $identidade = $reg['identidade'];
  $tipo = $reg['tipo'];
}

$sistema = base64_encode('GUARDA');
$obs = $situacao_excluir . " ID" . $id_rel . ": " . $nomecompleto . " - " . $identidade . " - (" . $tipo . ")";

$excluir_rel = $pdo1->prepare("UPDATE $rel SET userativo = 'N'
        WHERE id = :id");
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
