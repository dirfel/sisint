<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if (!isset($_POST['action'])) {
  header('Location: rot_gda1.php');
  exit();
}

$data = base64_decode(filter_input(INPUT_GET, "data", FILTER_SANITIZE_SPECIAL_CHARS));
$data2 = date_converter($data);

$quartohora_id = base64_decode(filter_input(INPUT_POST, "quartohora", FILTER_SANITIZE_SPECIAL_CHARS));
$p1 = base64_decode(filter_input(INPUT_POST, "p1", FILTER_SANITIZE_SPECIAL_CHARS));
$p2 = base64_decode(filter_input(INPUT_POST, "p2", FILTER_SANITIZE_SPECIAL_CHARS));
$p3 = base64_decode(filter_input(INPUT_POST, "p3", FILTER_SANITIZE_SPECIAL_CHARS));
$p4 = base64_decode(filter_input(INPUT_POST, "p4", FILTER_SANITIZE_SPECIAL_CHARS));
$p5 = base64_decode(filter_input(INPUT_POST, "p5", FILTER_SANITIZE_SPECIAL_CHARS));
$p6 = base64_decode(filter_input(INPUT_POST, "p6", FILTER_SANITIZE_SPECIAL_CHARS));
$aloj1 = base64_decode(filter_input(INPUT_POST, "aloj1", FILTER_SANITIZE_SPECIAL_CHARS));
$aloj2 = base64_decode(filter_input(INPUT_POST, "aloj2", FILTER_SANITIZE_SPECIAL_CHARS));
$situacao = $_POST['action'];

$pdo1 = conectar("guarda");
$pdo2 = conectar("membros");

$select_lancamentos = $pdo1->prepare("SELECT id FROM rel_rot_postos WHERE idquartohora = :idquartohora AND data = :data");
$select_lancamentos->bindParam(":idquartohora", $quartohora_id, PDO::PARAM_INT);
$select_lancamentos->bindParam(":data", $data, PDO::PARAM_STR);
$select_lancamentos->execute();
$select_lancamentos_total = $select_lancamentos->fetchAll(PDO::FETCH_ASSOC);
$select_lancamentos_count = count($select_lancamentos_total);

$select_quartohora = $pdo1->prepare("SELECT quartohora FROM rot_postos_quartohora WHERE id = :id");
$select_quartohora->bindParam(":id", $quartohora_id, PDO::PARAM_INT);
$select_quartohora->execute();
while ($reg = $select_quartohora->fetch(PDO::FETCH_ASSOC)) {
  $quartohora_nome = $reg['quartohora'];
}

$sistema = base64_encode('GUARDA');
$obs = $situacao . " o Quarto de hora das " . $quartohora_nome . " " . $quartohora_tipo . ", no dia: " . $data;

$dataatual = date("d/m/Y");
$dataatual2 = date("Y-m-d");
$horaatual = date("H:i:s");

$stmtez = $pdo1->prepare("INSERT INTO rel_rot_postos(idusuario, idquartohora, data, p1, p2, p3, p4, p5, p6, aloj1, aloj2)"
  . "VALUES (:idusuario, :idquartohora, :data, :p1, :p2, :p3, :p4, :p5, :p6, :aloj1, :aloj2)");
$stmtez->bindParam(":idusuario", $_SESSION['auth_data']['id'], PDO::PARAM_INT);
$stmtez->bindParam(":idquartohora", $quartohora_id, PDO::PARAM_INT);
$stmtez->bindParam(":data", $data, PDO::PARAM_STR);
$stmtez->bindParam(":p1", $p1, PDO::PARAM_INT);
$stmtez->bindParam(":p2", $p2, PDO::PARAM_INT);
$stmtez->bindParam(":p3", $p3, PDO::PARAM_INT);
$stmtez->bindParam(":p4", $p4, PDO::PARAM_INT);
$stmtez->bindParam(":p5", $p5, PDO::PARAM_INT);
$stmtez->bindParam(":p6", $p6, PDO::PARAM_INT);
$stmtez->bindParam(":aloj1", $aloj1, PDO::PARAM_INT);
$stmtez->bindParam("aloj2", $aloj2, PDO::PARAM_INT);

if ($data == '' or $data == '0') {
  header('Location: rot_gda1.php');
} else if ($select_lancamentos_count > 2) {
  $msgerro = base64_encode('Erro no lançamento, o sistema possui DOIS lançamentos para o Quarto de hora das ' . $quartohora_nome . ' ' . $quartohora_tipo . ', no dia: ' . $data);
  header('Location: rot_gda1.php?token=' . $msgerro);
  exit();
} else if ($select_lancamentos_count == 1) {
  $executa = $stmtez->execute();
  $executa2 = gerar_log_usuario($sistema, $obs);
  $msgsuccess = base64_encode('Cadastro realizado com sucesso, porém o sistema possui UM lançamento para o Quarto de hora das ' . $quartohora_nome . ' ' . $quartohora_tipo . ', no dia: ' . $data);
  header('Location: rot_gda1.php?token2=' . $msgsuccess);
  exit();
} else {
  $executa = $stmtez->execute();
  $executa2 = gerar_log_usuario($sistema, $obs);
  $msgsuccess = base64_encode('Cadastro realizado com sucesso!');
  header('Location: rot_gda1.php?token2=' . $msgsuccess);
  exit();
}
