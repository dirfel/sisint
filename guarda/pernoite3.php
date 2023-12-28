<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if (!isset($_POST['action'])) {
  header('Location: pernoite1.php');
  exit();
}

$idvisitante = base64_decode(filter_input(INPUT_POST, "tkvisit", FILTER_SANITIZE_SPECIAL_CHARS));
$alojamento = filter_input(INPUT_POST, "tkaloj", FILTER_SANITIZE_STRING);
//$convertdata = filter_input(INPUT_POST, "data", FILTER_SANITIZE_STRING);
//$data = implode('/', array_reverse(explode('-', $convertdata)));
$data = filter_input(INPUT_POST, "data", FILTER_SANITIZE_STRING);
$data2 = date_converter($data);
$hora = date("H:i:s");
$situacao = filter_input(INPUT_POST, "action", FILTER_SANITIZE_STRING);
$situacao2 = ($situacao . " no " . $alojamento);
$idmembro = '';

$pdo1 = conectar("guarda");
$pdo2 = conectar("membros");

$select_visitante = $pdo1->prepare("SELECT visitante.nomecompleto, visitante.idveiculo, veiculo.placa FROM visitante LEFT JOIN veiculo ON (visitante.idveiculo = veiculo.id) WHERE visitante.id = :idvisitante");
$select_visitante->bindParam(":idvisitante", $idvisitante, PDO::PARAM_INT);
$select_visitante->execute();
while ($reg = $select_visitante->fetch(PDO::FETCH_ASSOC)) {
  $visit_nome = $reg['nomecompleto'];
  $visit_tipo = $reg['tipo'];
  $veic_placa = $reg['placa'];
  $idveiculo = $reg['idveiculo'];
}

$select_rel_pernoite = $pdo1->prepare("SELECT * FROM rel_pernoite WHERE idvisitante = :idvisitante AND data = :data ORDER BY id DESC");
$select_rel_pernoite->bindParam(":idvisitante", $idvisitante, PDO::PARAM_INT);
$select_rel_pernoite->bindParam(":data", $data, PDO::PARAM_STR);
$select_rel_pernoite->execute();
while ($reg = $select_rel_pernoite->fetch(PDO::FETCH_ASSOC)) {
  $rel_pern_data = $reg['data'];
}

$sistema = base64_encode('GUARDA');
if ($idveiculo > 0) {
  $obs = "Lançou Visitante ID" . $idvisitante . " " . $visit_nome . ", veículo placa " . $veic_placa . ": " . $situacao . " no " . $alojamento;
} else {
  $obs = "Lançou Visitante ID" . $idvisitante . " " . $visit_nome . ", nenhum veículo: " . $situacao . " no " . $alojamento;
}

$dataatual = date("d/m/Y");
$dataatual2 = date("Y-m-d");

$stmtez = $pdo1->prepare("INSERT INTO rel_pernoite(idmembro, idvisitante, data, idusuario, situacao) "
  . "VALUES (0, :idvisitante, :data, :idusuario, :situacao)");
// $stmtez->bindParam(":idmembro", $idmembro ?? 0, PDO::PARAM_INT);
$stmtez->bindParam(":idvisitante", $idvisitante, PDO::PARAM_INT);
$stmtez->bindParam(":data", $data, PDO::PARAM_STR);
$stmtez->bindParam(":idusuario", $_SESSION['auth_data']['id'], PDO::PARAM_STR);
$stmtez->bindParam(":situacao", $situacao2, PDO::PARAM_STR);

if (
  $idvisitante == '' or $idvisitante == ' ' or $idvisitante == '0' or
  $alojamento == '' or $alojamento == ' ' or $alojamento == '0' or
  $hora == '' or $hora == ' ' or $hora == '0' or
  $data == '' or $data == ' ' or $data == '0'
) {
  header('Location: pernoite1.php');
  exit();
} else if (strtotime($dataatual2) >= strtotime($data2)) {
  if ($data <> $rel_pern_data) {
    $executa = $stmtez->execute();
    $executa2 = gerar_log_usuario($sistema, $obs);
    $msgsuccess = base64_encode('Visitante lançado com sucesso!');
    header('Location: pernoite1.php?token2=' . $msgsuccess);
    exit();
  } else {
    $msgerro = base64_encode('Erro na tentativa de registro, visitante já foi lançado!');
    header('Location: pernoite1.php?token=' . $msgerro);
    exit();
  }
} else {
  $msgerro = base64_encode('Erro na tentativa de registro, data inválida!');
  header('Location: pernoite1.php?token=' . $msgerro);
  exit();
}
